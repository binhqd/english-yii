<?php

/**
* This action is used to list follow
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 1.0
 */
class APIFollowingListAction extends GNAction
{

	/**
	 * This method is used to run action
	 * @author ngocnm
	 * @param $limit limit in page reponse
	 * @return void
	 */
	public function run($limit = 10,$page=1)
	{
		ApiAccess::allow("GET");

		//check user
		if (currentUser()->id === -1) {
			throw new Exception(null, 403);
		}

		// get list follow
		Yii::import('application.modules.followings.models.APIZoneFollowing');
		//$total = APIZoneFollowing::model()->countPeopleSuggestions(currentUser()->id);
		//$pages = new CPagination($total);
		$pages = new CPagination();
		$pages->pageSize = $limit;
		$peoples = APIZoneFollowing::model()->getListFollowingByUser(currentUser()->id,$pages->limit, $pages->offset);
		//resonpe
		Yii::app()->response->send(200, array(
			'items' => $peoples
		));
	}

}