<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
Yii::import('application.modules.activities.models.*');

class ApiListActivityAction extends GNAction {

	public $types = array(
		ZoneActivity::OBJECT_TYPE_ARTICLE,
		ZoneActivity::OBJECT_TYPE_ALBUM,
		ZoneActivity::OBJECT_TYPE_IMAGE,
		ZoneActivity::OBJECT_TYPE_NODE,
		ZoneActivity::OBJECT_TYPE_VIDEO
	);

	/**
	 * This method is used to run action
	 */
	public function run($id, $t = null) {

		if ($t && !$this->controller->isValidTimestamp($t)) {
			throw new Exception(UsersModule::t('The t param is invalid unix timestamp'), 400);
		}
		$timestamp = time();
		ZoneInstanceRender::get($id);
		$Paginate = $this->controller->paginate(0);
		$data = $this->get($id, $Paginate->limit, $Paginate->offset, $t);
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $data,
			'id' => $id,
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit,
			'timestamp' => $timestamp
		));
	}

	/**
	 * This action is used to get list of videos of an user
	 */
	public function get($id, $limit = 10, $offset = 0, $timestamp = null) {
		$types = array_map(function($v) {
					return "'{$v}'";
				}, $this->types);
		$strInObjectTypes = implode(',', $types);

		$criteria = new CDbCriteria();
		$criteria->select = 'id, object_type';
		$criteria->condition = '(receiver_id = :receiver_id) AND object_type in (' . $strInObjectTypes . ')';
		$criteria->params = array(
			':receiver_id' => IDHelper::uuidToBinary($id)
		);
		$criteria->order = 'created DESC';
		$criteria->limit = $limit;
		$criteria->offset = $offset;

		if ($timestamp && is_numeric($timestamp)) {
			$criteria->addCondition('created <=:times', 'AND');
			$criteria->params[':times'] = date('Y-m-d H:i:s', $timestamp);
		}

		$results = ZoneActivity::model()->findAll($criteria);
		$activities = array();
		foreach ($results as $item) {
			try {
				$hexID = IDHelper::uuidFromBinary($item->id, true);
				$activity = ZoneActivity::model()->get($hexID);
				$activity['timestamp'] = strtotime($activity['created']);
				$activity['like']['count'] = @intval($activity['like']['count']);
				$activities[] = $activity;
			} catch (Exception $ex) {
				continue;
			}
		}
		return $activities;
	}

}