<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiListFollowingAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id = '', $q = '') {
		$UserInfo = $this->controller->userInfo($id);
		$total = $UserInfo->stats['followings'];
		$Paginate = $this->controller->paginate($total);
		$result = array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $this->get($UserInfo->hexID, $q , $Paginate->limit , $Paginate->offset),
			'total' => $total,
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit
		);
		$this->controller->out(200, $result);
	}

	function get($id, $q = '', $limit = 10, $offset = 0) {
		$descriptionAlias = '/common/topic/description';
		Yii::import('application.modules.followings.models.ZoneFollowing');

		// get all followings node id
		$result = array();
		$followings = $this->paginate($id, $q, $limit, $offset);

		$CurrentUser = currentUser();
		$CurrentUser->attachBehavior('CurrentUserFollowing'
				, 'application.modules.followings.components.behaviors.GNUserFollowingBehavior');
		foreach ($followings as $item) {
			$NodeObject = ZoneInstanceRender::get($item['object_id']);
			$node = array_merge(array(
				'description' => '',
				'owner' => array()
					), $NodeObject->toArray());
			$prop = ZoneNodeRender::properties($item['object_id'], $descriptionAlias);
			if (isset($prop[$descriptionAlias]['items'])) {
				$node['description'] = $prop[$descriptionAlias]['items'];
			}
			$binObjectID = IDHelper::uuidToBinary($item['object_id']);
			// is following
			$node['isFollowing'] = $CurrentUser->isFollowing($binObjectID);
			// total followers
			$countFollowers = ZoneFollowing::model()->countFollowers($binObjectID);
			$node['countFollowers'] = $countFollowers;

			$followers = ZoneFollowing::model()->followers($binObjectID, '', '', 5);
			foreach ($followers as &$follower) {
				$follower = ZoneUser::model()->get($follower['user_id']);
			}
			$node['followers'] = $followers;

			// Owner
			$creatorID = $NodeObject->getCreatorID();
			$node['owner'] = ZoneUser::model()->get($creatorID);
			// images
			$result[] = ZoneInstanceRender::getResourceImage($node);
		}
		return $result;
	}

	public function count($id) {
		$UserInfo = $this->controller->userInfo($id);
		return $UserInfo->stats['followings'];
	}

	public function paginate($id, $q = '', $limit = 10, $offset = 0) {
		$UserInfo = $this->controller->userInfo($id);
		$UserInfo->attachBehavior('UserFollowing'
				, 'application.modules.followings.components.behaviors.GNUserFollowingBehavior');
		// count total followings of current user
		return $UserInfo->followingsByObjectType('object', ''
						, $q, $limit, $offset);
	}

}