<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiListVideoByUserAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id = '', $type = ZoneResourceVideo::TYPE_ALL) {
		$UserInfo = $this->controller->userInfo($id);
		$total = intval($UserInfo->stats['videos']);
		$Paginate = $this->controller->paginate($total);
		$result = $this->get($UserInfo->hexID, $type, $Paginate->limit, $Paginate->offset);
		$result['page'] = $Paginate->currentPage + 1;
		$result['cdn'] = ZoneRouter::CDNUrl('/');
		$result['total'] = $total;
		
		$result['poster'] = array(
			'id' => $UserInfo->hexID,
			'username' => $UserInfo->username,
			'displayname' => $UserInfo->displayname,
			'email' => $UserInfo->email,
			'profile' => $UserInfo->profile()
		);
		$this->controller->out(200, $result);
	}

	/**
	 * This action is used to get list of videos of an user
	 */
	public function get($id, $type = '', $limit = 10, $offset = 0) {
		$types = array(
			ZoneResourceVideo::TYPE_FULL,
			ZoneResourceVideo::TYPE_TRAILER,
			ZoneResourceVideo::TYPE_OTHER
		);
		if (!in_array($type, $types)) {
			$type = ZoneResourceVideo::TYPE_ALL;
		}
		$_videos = ZoneResourceVideo::model()->getVideosByUserID(
				IDHelper::uuidToBinary($id), $type, $limit, $offset);
		$videos = array();
		foreach ($_videos as $video) {
			// Get thumbnail of video
			unset($video['video']['poster'], $video['pagination']);
			if (empty($video['video']['thumbnail'])) {
				$video['video']['thumbnail'] = '';
			}
			if (empty($video['video']['object_id'])) {
				$video['video']['object_id'] = $id;
			}
			try {
				$node = ZoneInstanceRender::get($video['video']['object_id']);
			} catch (Exception $e) {
				continue;
			}
			if (!$node->isUserNode()) {
				$video['node'] = $node;
			}
			$video['video']['created'] = date(DATE_ISO8601, strtotime($video['video']['created']));
			$video['video']['timestamp'] = strtotime($video['video']['created']);
			$videos[] = $video;
		}
		$result = array(
			'thumbnailDefault' => '/myzone_v1/img/video-default.jpg',
			'type' => $type,
			'data' => $videos,
			'types' => $types,
		);
		return $result;
	}

}