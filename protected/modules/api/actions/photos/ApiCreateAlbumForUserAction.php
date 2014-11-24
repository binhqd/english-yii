<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiCreateAlbumForUserAction extends GNAction {

	public $scenario = 'create';

	/**
	 * This method is used to run action
	 */
	public function run() {
		$UserInfo = $this->controller->userInfo(null);
		if (empty($_POST['items']) ||
				!is_array($_POST['items'])) {
			throw new Exception(UsersModule::t('Your posted data is invalid.'), 400);
		}

		$isCreate = ($this->scenario == 'create');
		if ($isCreate && !empty($_POST['id'])) {
			throw new Exception(UsersModule::t('The create function does not supported id param.'));
		}
		if (!$isCreate && empty($_POST['id'])) {
			throw new Exception(UsersModule::t('Missing album id param.'));
		}
		$Model = new ZoneResourceAlbum();

		if (!empty($_POST['id'])) {
			$bindID = IDHelper::uuidToBinary($_POST['id'], true);
			$Album = ZoneResourceAlbum::model()->findByPk($bindID);
			if (empty($Album) || !$Album->owner ||
					$Album->owner->id != $UserInfo->id) {
				throw new Exception(UsersModule::t('This album is not found or does not belong to you.'), 403);
			}
			$Model = $Album;
			$namespace = $Model->AlbumNamespace;
			if ($namespace) {
				$_POST['objectId'] = IDHelper::uuidFromBinary($namespace->holder_id, true);
			} else {
				$_POST['objectId'] = $UserInfo->hexID;
				$Namespace = new ZoneAlbumNamespace();
				$Namespace->album_id = $Model->id;
				$Namespace->holder_id = IDHelper::uuidToBinary($_POST['objectId']);
				$Namespace->save();
			}
		} else {
			$Model->created = date("Y-m-d H:i:s");
			$Model->owner_id = $UserInfo->id;
			if(empty($_POST['title'])){
				$_POST['title'] = UsersModule::t('Untitled');
			}
		}

		$Node = ZoneInstanceRender::get($_POST['objectId']);
		!empty($_POST['title']) && $Model->title = $_POST['title'];
		if (!empty($_POST['description'])) {
			$Model->description = $_POST['description'];
		}
		set_time_limit(1000);
		if (!$Model->save()) {
			throw new Exception(UsersModule::t('The album could not saved'));
		}

		if ($isCreate) {
			$Namespace = new ZoneAlbumNamespace();
			$Namespace->album_id = $Model->id;
			$Namespace->holder_id = IDHelper::uuidToBinary($Node->zone_id);
			$Namespace->save();
		}
		/**
		  Assign images for albums
		 * */
		$zoneResourceImages = array();
		$photoMaxScore = ZoneResourceImage::model()->getMaxScore($Node->zone_id);
		$score = $photoMaxScore ? $photoMaxScore->score : 0;

		foreach ($_POST['items'] as $data) {
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
		// update photos count
		$Model->image_count = $Model->image_count + count($zoneResourceImages);
		$Model->save();

		if (!$zoneResourceImages) {
			throw new Exception(UsersModule::t('No photo has been posted'));
		}
		// ============
		$album = $Model->toArray();
		$result = $album;
		$result['totalAdded'] = count($zoneResourceImages);
		$firstImageID = IDHelper::uuidFromBinary($zoneResourceImages[0]->id, true);
		$image = ZoneResourceImage::model()->get($firstImageID);
		$result['photo'] = $image['photo'];
		/* Set like album */
		$result['like']['count'] = 0;

		$photos = array();
		foreach ($zoneResourceImages as $ZoneResourceImage) {
			$bindID = IDHelper::uuidFromBinary($ZoneResourceImage->id, true);
			$photo = ZoneResourceImage::model()->get($bindID);
			$photo['token'] = md5(uniqid(32));
			$photo['like']['token'] = $photo['token'];
			$photo['object_id'] = $_POST['objectId'];
			$photos[] = $photo;
		}
		$this->controller->out($isCreate ? 201 : 200, array(
			'album' => $album,
			'data' => $photos), false);

		// move to s3
		$albumID = IDHelper::uuidFromBinary($Model->id, true);
		$this->remote($zoneResourceImages, $albumID);
		// send activity
		if ($isCreate) {
			$this->_notify($Node, $result);
		}
	}

	public function remote(array $zoneResourceImages, $albumID) {
		$config = array(
			'class' => 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
			'uploadPath' => 'upload/gallery/',
			'storageEngines' => array(
				's3' => array(
					'class' => 'greennet.components.GNUploader.components.engines.s3.GNS3Engine',
					'serverInfo' => array(
						'accessKey' => Yii::app()->params['AWS']['S3']['upload']['accessKey'],
						'secretKey' => Yii::app()->params['AWS']['S3']['upload']['secretKey'],
						'bucket' => 'static.youlook.net'
					)
				)
			)
		);

		$webroot = Yii::getPathOfAlias('jlwebroot');
		$S3Uploader = Yii::createComponent($config);
		foreach ($zoneResourceImages as $ZoneResourceImage) {
			$filePath = "{$webroot}/upload/gallery/{$ZoneResourceImage->image}";
			$S3Uploader->store($filePath, array('s3path' => "upload/gallery/{$albumID}"));
		}
	}

	protected function _notify(ZoneInstance $Node, array $album) {
		Yii::import('application.modules.activities.models.*');
		Yii::import('application.components.notification.JLNotificationWriter');
		Yii::import('application.components.notification.ZoneStickerNotificationDocument');

		$binNodeID = IDHelper::uuidToBinary($Node->zone_id);
		$binAlbumID = IDHelper::uuidToBinary($album['id']);
		$currentUser = currentUser();
		$userData = ZoneUser::model()->get($currentUser->hexID);
		if ($Node->isUserNode()) {
			// 1. If album is posted on own timeline
			if ($Node->zone_id == $currentUser->hexID) {
				$currentUser->attachBehavior('UserFriend'
						, 'application.modules.friends.components.behaviors.GNUserFriendBehavior');
				$friendIDs = $currentUser->friends();
				/*
				 * Save Activities: 
				 * - Activity on own timeline
				 */
				ZoneAlbumActivity::model()->saveActivity($currentUser->id
						, $currentUser->id, $binAlbumID, ZoneActivity::TYPE_POST);
				// TODO: Post on friends' timeline
				/*
				 * Sidebar notification:
				 * - Notify to friends
				 * - Activities on friends' timeline
				 */
				foreach ($friendIDs as $friendInfo) {
					$data = array(
						'namespace' => 'zone-sticker',
						'data' => array(
							'object_type' => 'Album',
							'type' => 'self-posting',
							'user' => $userData,
							'album' => $album,
							'object' => null //
						),
						'userID' => $friendInfo['user_id']
					);
					$binFriendID = IDHelper::uuidToBinary($friendInfo['user_id']);
					ZoneAlbumActivity::model()->saveActivity($binFriendID
							, $currentUser->id, $binNodeID, ZoneActivity::TYPE_POST);
					JLNotificationWriter::push($data);
					JLNotificationWriter::savePushData($friendInfo['user_id'], $data);
				}
			} else {
				/*
				 * Save Activities:
				 * - Activity on receiver timeline
				 * - Activity on receiver's friend's timeline
				 */
				ZoneAlbumActivity::model()->saveActivity($binAlbumID
						, $currentUser->id, $binNodeID, ZoneActivity::TYPE_POST);
				/*
				 * Top notification:
				 * - Notify to receiver
				 */
				/*
				 * Sidebar notification:
				 * - Notify to receiver's friends
				 */
			}
		} else {
			Yii::import('application.modules.followings.models.ZoneFollowing');
			$relatedTypes = ZoneNodeRender::getCategories($Node->zone_id);
			foreach ($relatedTypes as $type => $subtypes) {
				ZoneAlbumActivity::model()->saveActivity(strtolower($type)
						, $currentUser->id, $binNodeID, ZoneActivity::TYPE_POST);
				foreach (array_keys($subtypes) as $id) {
					ZoneAlbumActivity::model()->saveActivity(IDHelper::uuidToBinary($id)
							, $currentUser->id, $binNodeID, ZoneActivity::TYPE_POST);
				}
			}
			ZoneAlbumActivity::model()->saveActivity($binNodeID
					, $currentUser->id, $binNodeID, ZoneActivity::TYPE_POST);
			$followers = ZoneFollowing::model()->followers($binNodeID);
			foreach ($followers as $follower) {
				$binFollowerID = IDHelper::uuidToBinary($follower['user_id']);
				if ($binFollowerID == $currentUser->id) {
					continue;
				}
				// Save activities
				$activity = ZoneAlbumActivity::model()->saveActivity($binFollowerID
						, $currentUser->id, $binNodeID, ZoneActivity::TYPE_POST);
				// Send notification to followers
				$data = array(
					'notifier_id' => $currentUser->hexID,
					'album_id' => $album['id'],
					'type' => 'postAlbum',
					'activity' => IDHelper::uuidFromBinary($activity['id'], true)
				);
				JLNotificationWriter::send(
						$follower['user_id'], "application.components.notification.renderer.ZoneAlbumNotification", $data
				);
			}
		}
	}

}