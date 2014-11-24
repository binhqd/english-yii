<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiArticleCreateAction extends GNAction {

	/**
	 * This action is used to get list of videos of an user
	 */
	public function run() {
		if (empty($_POST)) {
			$message = UsersModule::t('A request was made of a resource using a request method not supported by that resource');
			throw new Exception($message, 405);
		}

		if (empty($_POST['title']) || empty($_POST['content']) || (!empty($_POST['images']) &&
				!is_array($_POST['images']))) {
			throw new Exception(UsersModule::t('Your posted data is invalid.') , 400);
		}

		// process type
		unset($_POST['image']);
		unset($_POST['alias']);
		unset($_POST['id']);

		$Model = new ZoneArticle();
		$Model->setAttributes($_POST, false);
		$Model->created = date("Y-m-d H:i:s");
		if ($Model->type == ZoneArticle::TYPEIMAGE) {
			$Model->scenario = 'post';
		} else {
			$Model->scenario = 'normal';
		}
		$description = GNStringHelper::htmlPurify($Model->content);
		$Model->description = $description;

		if (!$Model->validate()) {
			$this->controller->out(400, array(
				'message' => UsersModule::t('The data is invalid.'),
				'validationErrors' => $Model->getErrors()
			));
		}
		set_time_limit(1000);
		if (!$Model->save()) {
			throw new Exception(UsersModule::t('The article could not be saved'));
		}
		$CurrentUser = currentUser();
		if (empty($_POST['objectId'])) {
			$_POST['objectId'] = $CurrentUser->hexID;
		}
		$Node = ZoneInstanceRender::get($_POST['objectId']);

		$Author = new ZoneArticleAuthor();
		$Author->article_id = $Model->id;
		$Author->holder_id = $CurrentUser->id;
		$Author->save();

		if ($Node->zone_id != $CurrentUser->hexID) {
			$Namespace = new ZoneArticleNamespace();
			$Namespace->article_id = $Model->id;
			$Namespace->holder_id = IDHelper::uuidToBinary($Node->zone_id);
			$Namespace->save();
		}

		// process image
		$photoMaxScore = ZoneResourceImage::model()->getMaxScore($Node->zone_id);
		$score = $photoMaxScore ? $photoMaxScore->score : 0;
		$zoneResourceImages = array();

		if (empty($_POST['images'])) {
			$_POST['images'] = array();
		}
		foreach ($_POST['images'] as $data) {
			$imageID = $data;
			$description = '';
			if (is_array($data)) {
				$imageID = @$data['id'];
				$description = @$data['description'];
			}
			$bindID = IDHelper::uuidToBinary($imageID);
			$ZoneResourceImage = ZoneResourceImage::model()->findByPk($bindID);
			if (!$ZoneResourceImage) {
				continue;
			}
			$ZoneResourceImage->object_id = $Node->zone_id;
			$ZoneResourceImage->album_id = $Model->id;
			$ZoneResourceImage->description = $description;
			/* Set score */
			$ZoneResourceImage->score = floatval(round($score - 0.1, 4));
			if ($ZoneResourceImage->save()) {
				ZoneResourceImage::model()->deleteCache($imageID);
				$zoneResourceImages[] = $ZoneResourceImage;
			}
		}
		$articleID = IDHelper::uuidFromBinary($Model->id, true);
		// -----------------------
		$this->controller->out(200, array(
			'message' => UsersModule::t('Article has been created successful'),
			'type' => 1,
			'id' => $articleID), false);
		// remote image
		$actions = array(
			'createAlbum' => 'application.modules.api.actions.photos.ApiCreateAlbumForUserAction'
		);
		$Action = $this->controller->initAction('createAlbum', $actions);
		$Action->remote($zoneResourceImages, $articleID);
		// send activity
		$this->_notify($Model, $Node, $CurrentUser);
	}

	protected function _notify($Model, $Node, $CurrentUser) {
		// ====================================================================
		Yii::import('application.modules.activities.models.*');
		Yii::import('application.components.notification.JLNotificationWriter');
		$binObjectID = IDHelper::uuidToBinary($Node->zone_id);
		ZoneArticleActivity::model()->saveActivity($CurrentUser->id, $CurrentUser->id, $Model->id, ZoneActivity::TYPE_POST);
		if ($CurrentUser->id != $binObjectID) {
			ZoneArticleActivity::model()->saveActivity($binObjectID, $CurrentUser->id, $Model->id, ZoneActivity::TYPE_POST);
		}
		if ($Node->isUserNode()) {
			return;
		}
		// notify to followers
		Yii::import('application.modules.followings.models.ZoneFollowing');
		$articleID = IDHelper::uuidFromBinary($Model->id, true);
		$followers = ZoneFollowing::model()->followers($Node->zone_id);
		foreach ($followers as $follower) {
			$followerID = $follower['user_id'];
			$binFollowerID = IDHelper::uuidToBinary($followerID);
			if ($binFollowerID != $CurrentUser->id) {
				// Save activities
				$activity = ZoneArticleActivity::model()->saveActivity($binFollowerID, $CurrentUser->id, $Model->id, ZoneActivity::TYPE_POST);
				// Send notification to followers
				$data = array(
					//'friend_id'	=> currentUser()->hexID,
					'notifier_id' => $CurrentUser->hexID,
					'article_id' => $articleID,
					'type' => 'postArticle',
					'activity' => IDHelper::uuidFromBinary($activity['id'], true),
					'created' => $Model->created
				);
				JLNotificationWriter::send(
						$followerID, "application.components.notification.renderer.ZoneArticleNotification", $data
				);
			}
		}
	}

}