<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */

class ApiListTopicAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id = null) {
		$this->controller->out(200, $this->get($id));
	}

	public function get($id = null, $q = '') {
		$UserInfo = $this->controller->userInfo($id);
		$UserInfo->attachBehavior('UserFollowing'
				, 'application.modules.followings.components.behaviors.GNUserFollowingBehavior');
		// count total followings of current user
	
		$stats = $UserInfo->stats;
		$Paginate = $this->controller->paginate($stats['topics']);
		$topics = ZoneNodeRender::getOwnedNodes($UserInfo->hexID
				, $Paginate->limit, $Paginate->offset, $q);
		
		$arrTopics = array();
		$descriptionAlias = '/common/topic/description';

		$CurrentUser = currentUser();
		$CurrentUser->attachBehavior('CurrentUserFollowing', 
				'application.modules.followings.components.behaviors.GNUserFollowingBehavior');
		foreach ($topics as $node) {
			$prop = ZoneNodeRender::properties($node['zone_id'], $descriptionAlias);
			if (isset($prop[$descriptionAlias]['items'])) {
				$node['description'] = $prop[$descriptionAlias]['items'];
			}
			$binObjectID = IDHelper::uuidToBinary($node['zone_id']);
			// is following
			$node['isFollowing'] = $CurrentUser->isFollowing($binObjectID);
			// total followers
			$countFollowers = ZoneFollowing::model()->countFollowers($binObjectID);
			$node['countFollowers'] = $countFollowers;
			// Owner
			$creatorID = ZoneInstanceRender::get($node['zone_id'])->getCreatorID();
			$node['owner'] = ZoneUser::model()->get($creatorID);
			// images
			$arrTopics[] = ZoneInstanceRender::getResourceImage($node);
		}
		// parsing node data & get node description
		// Output as Json
		return array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $arrTopics,
			'total' => intval($stats['topics']),
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit
		);
	}

}