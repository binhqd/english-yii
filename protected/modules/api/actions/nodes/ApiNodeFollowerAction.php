<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
Yii::import('application.modules.followings.models.ZoneFollowing');

class ApiNodeFollowerAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id, $q = '') {
		ZoneInstanceRender::get($id);
		$count = $this->count($id, $q);
		$Paginate = $this->controller->paginate($count);
		$data = $this->get($id, $q, $Paginate->limit, $Paginate->offset);
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $data,
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit,
			'total' => $count
		));
	}

	public function count($id, $q = '') {
		$binObjectId = IDHelper::uuidToBinary($id);
		return ZoneFollowing::model()->countFollowers($binObjectId, $q);
	}

	/**
	 * This action is used to get list of videos of an user
	 */
	public function get($id, $q = '', $limit = 10, $offset = 0) {
		$followers = $this->paginate($id, $q, $limit, $offset);
		$CurrentUser = new ApiZoneUser(currentUser());
		$results = array();

		foreach ($followers as $follower) {
			try {
				$User = new ApiZoneUser($follower['user_id']);
			} catch (Exception $e) {
				continue;
			}
			//$userFollowersCount = 0; //ZoneFollowing::model()->countFollowers($User->id);
			$countFollowings = ZoneFollowing::countFollowings($User->id, '', $q);
			$data = array(
				'id' => $follower['user_id'],
				'displayname' => $User->displayname,
				'email' => $User->email,
				'username' => $User->username,
				'profile' => $User->profile(),
				//'countFollowers' => $userFollowersCount,
				//'countContributions' => 0,
				'countFollowings' => $countFollowings
			);
			$isFriend = $isPendingMe = $isPending = false;
			if (!$CurrentUser->isGuest && $CurrentUser->id != $User->id) {
				if (!($isFriend = $CurrentUser->isFriend($User->id))) {
					$isPending = $CurrentUser->isPendingBy($User->id);
				}
				if (!$isFriend && !$isPending) {
					$isPendingMe = $User->isPendingBy($CurrentUser->id);
				}
				$data['countMutualFriends'] = $CurrentUser->countMutualFriends($User->id);
				$data+= compact('isFriend', 'isPending', 'isPendingMe');
			}
			$results[] = $data;
		}

		return $results;
	}

	public function paginate($id, $q = '', $limit = 10, $offset = 0) {
		$binObjectId = IDHelper::uuidToBinary($id);
		return ZoneFollowing::model()->followers($binObjectId
						, '', $q, $limit, $offset);
	}

}