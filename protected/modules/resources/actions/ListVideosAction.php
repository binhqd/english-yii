<?php
class ListVideosAction extends GNAction {
	public $requestType;
	/**
	 * The main action that handles the list photo request.
	 */
	public function run() {
		$controller = $this->controller;
		
		$id = Yii::app()->request->getParam('id',null);
		if($id == null) {
			$out = array(
				'error'		=> true,
				'message'	=> "Invalid object ID"
			);
			ajaxOut($out);
		}

		$page = abs(intval(Yii::app()->request->getParam('page', 1)));
		$limit = Yii::app()->request->getParam('limit', 15);
		$type = Yii::app()->request->getParam('type', ZoneResourceVideo::TYPE_ALL);
		
		if (!in_array($type, array(ZoneResourceVideo::TYPE_FULL, ZoneResourceVideo::TYPE_TRAILER, ZoneResourceVideo::TYPE_OTHER))) {
			$type = ZoneResourceVideo::TYPE_ALL;
		}
		
		$offset = ($page - 1) * $limit;
		
		$stats = ZoneStat::model()->get($id);
		
		$videos = ZoneResourceVideo::model()->getVideosByObjectID($id, $type, $limit, $offset);
		$out = array(
			'page'		=> $page,
			'limit'		=> $limit,
			'total'		=> $stats['videos'],
			'type'		=> $type,
			'videos'	=> $videos,
		);
		ajaxOut($out);
		
	}
}