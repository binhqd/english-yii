<?php
/**
 * This controller is used to show welcome page
 * @author huytbt <huytbt@gmail.com>
 */
class WelcomeController extends ZoneController
{
	public $layout = "//layouts/master/new_profile";

	public function allowedActions()
	{
		return '*';
	}

	/**
	 * This action is used to show dashboard
	 */
	public function actionIndex()
	{
		$currentUser = currentUser();
		if ($currentUser->isGuest)
			throw new Exception('Access denied');

		$objNode = ZoneInstanceRender::get(currentUser()->hexID );
		$Manager = new ZoneInstanceManager('/people/user');
		$results = $Manager->values($objNode);

		$this->render('application.modules.users.views.welcome.index', array(
			'user'	=> $currentUser,
			'form'	=> array(
				'userinfo'	=> $results,
				'token'		=> $results['token']
			)
		));
	}

	/**
	 * This action is used to get friend requests
	 */
	public function actionPendingFriends($limit = 4, $offset = 0)
	{
		$currentUser = currentUser();
		if ($currentUser->isGuest)
			ajaxOut(array(
				'error'	=> true,
				'msg'	=> 'Access denied',
			));

		$currentUser->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
		$pendings = $currentUser->pendingFriends('', '', $limit, $offset);
		$currentUser->detachBehavior('UserFriend');

		ajaxOut(array(
			'error'	=> false,
			'data'	=> $this->_formatData($pendings),
		));
	}

	/**
	 * This method is used to format data to response
	 */
	private function _formatData($friends)
	{
		$currentUser = currentUser();
		$currentUser->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
		$arrFriends = array();
		foreach ($friends as $friend)
		{
			$userInfo = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($friend['user_id']));
			if ($currentUser->id != $userInfo->id)
				$userInfo->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
			$isFriend = false;
			$isPending = false;
			$isPendingMe = true;
			$arrFriends[] = array(
				'user_id'		=> $userInfo->hexID,
				'username'		=> $userInfo->username,
				'displayname'		=> $userInfo->displayname,
				'email'			=> $userInfo->email,
				'location'		=> !empty($userInfo->profile) ? MyZoneHelper::formatLocation($userInfo->profile->location) : "",
				'isCurrentUser'	=> !$currentUser->isGuest && $currentUser->id == $userInfo->id,
				'isFriend'		=> $isFriend,
				'isPending'		=> $isPending,
				'isPendingMe'	=> $isPendingMe,
				'countFriends'	=> $userInfo->countFriends(),
				'avatar'		=> !empty($userInfo->profile) ? $userInfo->profile->image : "",
				// Add more attribute here
			);
			if ($currentUser->id != $userInfo->id)
				$userInfo->detachBehavior('UserFriend');
		}

		$currentUser->detachBehavior('UserFriend');

		return $arrFriends;
	}

}