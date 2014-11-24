<?php

/**
* This action is used to deny friend current user
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 1.0
 */
class APIDenyFriendAction extends GNAction
{

	/**
	 * This method is used to run action
	 * @author ngocnm
	 * @param $limit limit in page reponse
	 * @return void
	 */
	public function run($request_id = '')
	{
		ApiAccess::allow("DELETE");
		$requestUser = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($request_id));

		//check user
		if (currentUser()->id === -1) {
			throw new Exception(null, 403);
		}
		if ($requestUser->id === -1) {
			throw new Exception(null, 403);
		}

		// check user unfriend yourself
		if ($requestUser->id == currentUser()->id) {
			throw new Exception(Yii::t("Youlook",'You cannot deny friend with yourself'),403);
		}

		// send request
		Yii::import('application.modules.friends.models.ZoneFriendship');
		$unFriend = ZoneFriendship::model()->deny(currentUser()->id,$requestUser->id);
		if ($unFriend == false) {
			throw new Exception('Cannot deny friend', 500);
		}

		//resonpe
		Yii::app()->response->send(200, array(
		),Yii::t("Youlook", 'Deny friend successful.'));
	}

}