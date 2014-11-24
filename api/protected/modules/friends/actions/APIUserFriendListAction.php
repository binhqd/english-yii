<?php

/**
* This action is used to get friend by user
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 1.0
 */
class APIUserFriendListAction extends GNAction
{

	/**
	 * This method is used to run action
	 * @author ngocnm
	 * @param $limit limit in page reponse
	 * @return void
	 */
	public function run($limit = 10,$user_id =null)
	{
		ApiAccess::allow("GET");
		// check user
		$currentUser = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($user_id));
		if ($currentUser->id === -1) {
			throw new Exception(null, 403);
		}
		// get list friend pending
		Yii::import('application.modules.friends.models.APIZoneFriendship');
		$total = APIZoneFriendship::model()->countFriends($user_id,'');
		$pages = new CPagination($total);
		$pages->pageSize = $limit;
		$friends = APIZoneFriendship::model()->getFriends($user_id, '', $pages->limit, $pages->offset);

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