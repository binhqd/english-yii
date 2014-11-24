<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiListNewUserAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run() {
		$Paginate = $this->controller->paginate(0);
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $this->get($Paginate->limit, $Paginate->offset)));
	}

	public function get($limit = 10, $offset = 0) {
		$criteria = new CDbCriteria();
		$criteria->select = 'id';
		$criteria->order = 'created DESC';
		$criteria->limit = $limit;
		$criteria->offset = $offset;

		$result = array();
		$CurrentUser = new ApiZoneUser(currentUser());
		$users = ZoneUser::model()->findAll($criteria);
		foreach ($users as $user) {
			$UserInfo = new ApiZoneUser($user->id);
			if ($CurrentUser->id == $UserInfo->id) {
				continue;
			}
			$data = array(
				'id' => $UserInfo->hexID,
				'username' => $UserInfo->username,
				'displayname' => $UserInfo->displayname,
				'email' => $UserInfo->email,
				'profile' => $UserInfo->profile()
			);
			if (!$CurrentUser->isGuest) {
				$data['mutualFriends'] = array_map(function($val) {
							return $val['user_id'];
						}, $CurrentUser->mutualFriends($UserInfo->id));
				$isPendingMe = $isPending = false;
				if (!($isFriend = $CurrentUser->isFriend($UserInfo->id))) {
					$isPending = $CurrentUser->isPendingBy($UserInfo->id);
				}
				if (!$isFriend && !$isPending) {
					$isPendingMe = $UserInfo->isPendingBy($CurrentUser->id);
				}
				$data+= compact('isFriend', 'isPendingMe', 'isPending');
			}
			$result[] = $data;
		}
		return $result;
	}

}