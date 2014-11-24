<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiListPhotoAvatarAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id = null) {
		$Paginate = $this->controller->paginate(0);
		$data = $this->get($id, $Paginate->limit, $Paginate->offset);
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit,
				) + $data);
	}

	function get($id, $limit = 10, $offset = 0) {
		$UserInfo = $this->controller->userInfo($id);
		// === get photos ======
		// Get total avatar of user
		$total = ZoneUserAvatar::model()->getTotal($UserInfo->hexID);

		$avatars = ZoneUserAvatar::model()->getAvatars($UserInfo->hexID, $limit, $offset);

		$result = array();
		$currentUserID = currentUser()->id;
		foreach ($avatars as $item) {

			$item['type'] = 'user';
			$item['photo']['timestamp'] = strtotime($item['photo']['created']);
			$item['photo']['created'] = date(DATE_ISO8601, $item['photo']['timestamp']);

			$totalComments = ZoneComment::model()->countComments($item['photo']['id']);
			$item['comment'] = array(
				'total' => intval($totalComments),
				'items' => $item['comments']
			);
			$binID = IDHelper::uuidToBinary($item['photo']['id']);
			$item['like'] = LikeObject::model()->getLikeInfo($binID, $currentUserID);
			unset($item['comments'], $item['photo']['url']);
			$result[] = $item;
		}

		return array(
			'total' => intval($total),
			'data' => $result,
		);
	}

}