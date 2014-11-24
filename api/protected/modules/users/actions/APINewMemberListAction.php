<?php

/**
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 2.0
 */
class APINewMemberListAction extends GNAction
{

	/**
	 * This method is used to run action
	 * @author ngocnm
	 * @param string $limit Limit 
	 * @return void
	 */
	public function run($limit= 10,$page=null)
	{
		ApiAccess::allow("GET");

		// get new members
		Yii::import('api_app.modules.users.models.APIZoneUser');
		$total = APIZoneUser::model()->countNewMembers();
		$pages = new CPagination($total);
		$pages->pageSize = $limit;
		$newMembers = APIZoneUser::model()->getNewMembers($pages->limit, $pages->offset);

		// proccess data
		Yii::import('application.modules.friends.models.ZoneFriendship');
		foreach ($newMembers as $index => $member) {
			$newMembers[$index]['is_friend'] = false;
			if (currentUser()->id !== -1) {
				$newMembers[$index]['is_friend'] = ZoneFriendship::model()->isFriend(currentUser()->id, IDHelper::uuidToBinary($member['id']));
			}
		}

		// response
		Yii::app()->response->send(200, array(
			'items'	=> $newMembers,
			'pages'	=> array(
				'total'	=> (int)$pages->itemCount,
				'limit'	=> (int)$pages->limit,
				'pages'	=> (int)$pages->currentPage + 1,
			),
		));
	}
}