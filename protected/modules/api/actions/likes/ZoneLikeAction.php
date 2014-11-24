<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * Dont user
 */
Yii::import('application.modules.like.models.*');
Yii::import('application.modules.resources.models.*');
Yii::import('application.modules.status.models.ZoneStatus');
Yii::import('application.modules.articles.models.ZoneArticle');

class ZoneLikeAction extends GNAction {

	public $type = '';
	protected $_status = null;
	public $modelClass = null;
	public $onNotify = null;

	/**
	 * This method is used to run action
	 */
	public function run($id) {
		set_time_limit(1000);
		$data = $this->like($id);
		$this->controller->out(200, $data, false);
		// notify
		$this->notify($id, $data);
	}

	function like($id) {
		$UserInfo = $this->controller->userInfo(null);
		$this->setModel($this->modelClass);
		if (!$this->model) {
			throw new Exception(UsersModule::t('The model class {class} is not exists.', array(
				'{class}' => $this->modelClass
			)));
		}
		if (!($this->model instanceof CActiveRecord)) {
			throw new Exception(UsersModule::t('The model class is not extends CActiveRecord class.', array(
				'{class}' => $this->modelClass
			)));
		}
		$binID = IDHelper::uuidToBinary($id);
		$model = $this->model->findByPk($binID);
		if (!$model && $this->model instanceof ZoneResourceImage) {
			$model = ZoneUserAvatar::model()->findByPk($binID);
		}
		if (!$model) {
			throw new Exception(UsersModule::t('The object is not exists.'));
		}
		$this->setModel($model);
		
		$status = LikeObject::model()->getLikeInfo($binID, $UserInfo->id);
		if (!empty($status['value'])) {
			$this->_status = $status['value'];
		}

		$likeStatus = ZoneLike::model()->likeStatus($binID);
		$likeInfo = LikeObject::model()->getLikeInfo($binID, $UserInfo->id);

		return array(
			'objectId' => $id,
			'message' => UsersModule::t('This object has been updated.'),
			'type' => LikeStatistic::TYPE_RATING_LIKE,
			'value' => $likeStatus == "like" ? LikeObject::VALUE_RATING_UNLIKE : LikeObject::VALUE_RATING_LIKE,
			'people' => $likeInfo['text'],
			'number' => intval($likeInfo['count'])
		);
	}

	public function notify($id, $data) {
		if ($this->onNotify === false || !$this->model) {
			return false;
		}
		if (!$this->type) {
			switch (true) {
				case $this->model instanceof ZoneArticle:
					$this->type = 'likeArticle';
					break;
				case $this->model instanceof ZoneResourceAlbum:
					$this->type = 'likeAlbum';
					break;
				case $this->model instanceof ZoneResourceVideo:
					$this->type = 'likeVideo';
					break;
				case $this->model instanceof ZoneUserAvatar:
				case $this->model instanceof ZoneResourceImage:
					$this->type = 'likeImage';
					break;
				case $this->model instanceof ZoneStatus:
					$this->type = 'likeStatus';
					break;
			}
		}
		if (!$this->type) {
			return false;
		}
		$this->onNotify($id, $data);
		if ($data['value'] != LikeObject::VALUE_RATING_LIKE) {
			return false;
		}

		Yii::import('application.components.notification.JLNotificationWriter');
		$UserInfo = $this->controller->userInfo(null);
		$friends = $UserInfo->friends('', '', 1000, 0);
		if (!empty($friends)) {
			foreach ($friends as $friend) {
				$data = array(
					'notifier_id' => $UserInfo->hexID,
					'receive_id' => $friend['user_id'],
					'object_id' => $id,
					'type' => $this->type
				);
				JLNotificationWriter::send(
						$friend['user_id'], "application.components.notification.renderer.ZoneLikeNotification", $data
				);
			}
		}
		return true;
	}

	public function onNotify($id, $data) {
		// Send notification to liked
		if (!is_callable($this->onNotify)) {
			return;
		}
		call_user_func($this->onNotify, $this, $id, $data);
	}

}