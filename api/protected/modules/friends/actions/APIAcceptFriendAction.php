<?php

/**
* This action is used to accept friend current user
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 1.0
 */
class APIAcceptFriendAction extends GNAction
{

	/**
	 * This method is used to run action
	 * @author ngocnm
	 * @param $limit limit in page reponse
	 * @return void
	 */
	public function run($request_id = '')
	{
		ApiAccess::allow("PUT");

		//check user
		if (currentUser()->id === -1) {
			throw new Exception(null, 403);
		}
		// get params
		$requestUser = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($request_id));
		if ($requestUser->id === -1) {
			throw new Exception(null, 403);
		}

		
		// check user unfriend yourself
		if ($requestUser->id == currentUser()->id) {
			throw new Exception(Yii::t("Youlook",'You cannot accept friend with yourself'),403);
		}

		// check is friend
		Yii::import('application.modules.friends.models.ZoneFriendship');
		// check user and user request is friend
		$isFriend = ZoneFriendship::model()->isFriend(currentUser()->id,$requestUser->id);
		if ($isFriend == true) {
			//resonpe
			Yii::app()->response->send(200, array(
				'friendship' => array(
					'id'		=> IDHelper::uuidFromBinary($requestUser->id,true),
					'user_id'	=> IDHelper::uuidFromBinary(currentUser()->id,true),
					'friend_id'	=> IDHelper::uuidFromBinary($requestUser->id,true)
				),
			),Yii::t("Youlook", 'You made friend with user request'));
		}

		// accept request friend
		$acceptFriend = ZoneFriendship::model()->accept(currentUser()->id,$requestUser->id);
		if ($acceptFriend == false) {
			throw new Exception('Cannot accept friend', 500);
		}

		//resonpe
		Yii::app()->response->send(200, array(
			'friendship' => array(
				'id'		=> IDHelper::uuidFromBinary($requestUser->id,true),
				'user_id'	=> IDHelper::uuidFromBinary(currentUser()->id,true),
				'friend_id'	=> IDHelper::uuidFromBinary($requestUser->id,true)
			),
		),Yii::t("Youlook", 'Accept friend successful.'));
	}

}