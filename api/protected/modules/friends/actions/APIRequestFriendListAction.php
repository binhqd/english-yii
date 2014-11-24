<?php

/**
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 1.0
 */
class APIRequestFriendListAction extends GNAction
{

	/**
	 * This method is used to run action
	 * @author ngocnm
	 * @param $limit limit in page reponse
	 * @return void
	 */
	public function run($limit = 10)
	{
		ApiAccess::allow("GET");

		// check user
		if (currentUser()->id === -1) {
			throw new Exception(null, 401);
		}
		// get list friend pending
		Yii::import('application.modules.friends.models.APIZoneFriendship');
		$total = APIZoneFriendship::model()->countPendingFriends(currentUser()->id);
		$pages = new CPagination($total);
		$pages->pageSize = $limit;
		$friends = APIZoneFriendship::model()->getPendingFriends(currentUser()->id,$pages->limit, $pages->offset);

		// response
		Yii::app()->response->send(200, array(
			'items'	=> $friends,
			'pages'	=> array(
				'total'	=> (int)$pages->itemCount,
				'limit'	=> (int)$pages->limit,
				'pages'	=> (int)$pages->currentPage + 1,
			),
		));
	}

}