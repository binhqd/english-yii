<?php

/**
* This action is used to request friend 
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 1.0
 */
class APIRequestFriendAction extends GNAction
{

	/**
	 * This method is used to run action
	 * @author ngocnm
	 * @return void
	 */
	public function run()
	{
		ApiAccess::allow("POST");
		if (empty($_POST)) {
			$message = Yii::t("Youlook", 'Invalid Request');
			throw new Exception($message, 400);
		}
		$user_id = $_POST['user_id'];
		//check user
		if (currentUser()->id === -1) {
			throw new Exception(null, 403);
		}

		// get params
		$requestUser = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($user_id));
		if ($requestUser->id === -1) {
			throw new Exception(null, 403);
		}

		// check user unfriend yourself
		if ($requestUser->id == currentUser()->id) {
			throw new Exception(Yii::t("Youlook",'You cannot request friend with yourself'),403);
		}

		Yii::import('application.modules.friends.models.ZoneFriendship');
		// check user and user request is friend
		$isFriend = ZoneFriendship::model()->isFriend(currentUser()->id,$requestUser->id);
		if ($isFriend == true) {
			//resonpe
			Yii::app()->response->send(200, array(
				'friendship' => array(
					'id'		=> IDHelper::uuidFromBinary($user_id),
					'user_id'	=> IDHelper::uuidFromBinary(currentUser()->id,true),
					'friend_id'	=> IDHelper::uuidFromBinary($user_id)
				),
			),Yii::t("Youlook", 'You made friend with user request'));
		}
		// send request
		$requesttFriend = ZoneFriendship::model()->requestFriend(currentUser()->id,$requestUser->id);
		if ($requesttFriend == false) {
			throw new Exception('Cannot request friend', 500);
		}

		//resonpe
		Yii::app()->response->send(200, array(
			'friendship' => array(
				'id'		=> IDHelper::uuidFromBinary($user_id),
				'user_id'	=> IDHelper::uuidFromBinary(currentUser()->id,true),
				'friend_id'	=> IDHelper::uuidFromBinary($user_id)
			),
		),Yii::t("Youlook", 'Request friend successful.'));
	}

}