<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * Dont user
 */
class ZoneCommentAction extends GNAction {

	public $type = '';
	public $modelClass = null;
	public $onNotify = null;

	/**
	 * This method is used to run action
	 */
	public function run() {
		set_time_limit(1000);
		if (empty($_POST['id']) || empty($_POST['content'])) {
			throw new Exception(UsersModule::t('Your posted data is invalid.'));
		}
		extract($_POST);
		$data = $this->comment($id, $content);
		$this->controller->out(200, $data, false);
		// notify
		$this->notify($id, $data);
	}

	function comment($id, $content) {
		$binObjectID = IDHelper::uuidToBinary($id);
		$model = new ZoneComment;
		$model = $model->createComment($content, $binObjectID);
		// return data
		$cntComments = ZoneComment::model()->countComments($id);
		//$token = !empty($_POST['token']) ? $_POST['token'] : '';
		$comment = $model->toArray(true);
		$comment['timestamp'] = strtotime($comment['created']);
		$comment['created'] = date(DATE_ISO8601, $comment['timestamp']);
		
		return array(
			'message' => UsersModule::t('Comment has been saved successfuly!'),
			'total' => $cntComments,
			'id' => IDHelper::uuidFromBinary($model->id, true),
			//'token' => $token,
			'data' => $comment
		);
	}

	public function notify($id, $data) {
		$this->onNotify($id, $data);
	}

	public function onNotify($id, $data) {
		// Send notification to liked
		if (!is_callable($this->onNotify)) {
			return;
		}
		call_user_func($this->onNotify, $this, $id, $data);
	}

}