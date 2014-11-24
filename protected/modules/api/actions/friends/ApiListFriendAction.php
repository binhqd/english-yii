<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
Yii::import('application.modules.followings.models.ZoneFollowing');

class ApiListFriendAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id = null, $q = '') {
		$count = $this->count($id, $q);
		$Paginate = $this->controller->paginate($count);
		$result = array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $this->get($id, $q, $Paginate->limit, $Paginate->offset),
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit,
			'total' => $count,
		);
		$totalPending = $this->countPendingFriends();
		if ($totalPending !== false) {
			$result['totalPending'] = $totalPending;
		}
		$this->controller->out(200, $result);
	}

	function get($id, $q = '', $limit = 10, $offset = 0) {
		$UserInfo = $this->controller->userInfo($id);
		$friends = $UserInfo->friends('', $q, $limit, $offset);

		$CurrentUser = new ApiZoneUser(currentUser());

		$result = array();
		$config = preg_split('/[\s,]+/', @$_GET['field'], -1, PREG_SPLIT_NO_EMPTY);
		foreach ($friends as $friend) {
			$User = new ApiZoneUser($friend['user_id']);
			$data = array(
				'id' => $User->hexID,
				'username' => $User->username,
				'displayname' => $User->displayname,
				'email' => $User->email,
				'totalFriends' => $User->countFriends(),
				'profile' => $User->profile()
			);
			if (!$CurrentUser->isGuest && $CurrentUser->id !=
					$UserInfo->id && $User->id != $CurrentUser->id) {
				$isFriend = $isPendingMe = $isPending = false;
				if (!($isFriend = $CurrentUser->isFriend($User->id))) {
					$isPending = $CurrentUser->isPendingBy($User->id);
				}
				if (!$isFriend && !$isPending) {
					$isPendingMe = $User->isPendingBy($CurrentUser->id);
				}
				$data += compact('isFriend', 'isPendingMe', 'isPending');
			}
			foreach ($config as $key) {
				switch ($key) {
					case 'mutualFriends' :
						$data['mutualFriends'] = $UserInfo->mutualFriends($User->id);
						break;
					case 'countMutualFriends' :
						$data['countMutualFriends'] = $UserInfo->countMutualFriends($User->id);
						break;
					case 'countFollowings' :
						$data['countFollowings'] = ZoneFollowing::countFollowings($User->id);
						break;
				}
			}
			$result[] = $data;
		}
		return $result;
	}

	public function count($id, $q = '') {
		$UserInfo = $this->controller->userInfo($id);
		return intval($UserInfo->countFriends($q));
	}

	public function countPendingFriends() {
		if (currentUser()->isGuest) {
			return false;
		}
		$count = $this->controller->userInfo(null)->countPendingFriends();
		return intval($count);
	}

}