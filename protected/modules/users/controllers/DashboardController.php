<?php
/**
 * This controller is used to show dashboard
 * @author huytbt <huytbt@gmail.com>
 */
class DashboardController extends ZoneController
{

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

		$this->render('index');
	}

	/**
	 * This action is used to get my friends
	 */
	public function actionMyFriends($limit = 4, $offset = 0)
	{
		$currentUser = currentUser();
		if ($currentUser->isGuest)
			ajaxOut(array(
				'error'	=> true,
				'msg'	=> 'Access denied',
			));

		// My Friends
		$currentUser->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
		$myFriends = $currentUser->friends('', '', $limit, $offset);
		$currentUser->detachBehavior('UserFriend');

		$data = $this->_formatUserData($myFriends);

		ajaxOut(array(
			'error'	=> false,
			'data'	=> $data,
		));
	}

	/**
	 * This action is used to get suggest topics
	 */
	public function actionSuggestTopics($limit = 4, $offset = 0)
	{
		$condition = InterestCondition::getValue('',InterestCondition::ALL);
		$nodes = ZoneInstanceRender::search('', $limit, $offset, $condition);

		ajaxOut(array(
			'error'	=> false,
			'data'	=> $nodes,
		));
	}

	/**
	 * This action is used to get popular videos
	 */
	public function actionPopularVideos($limit = 4, $offset = 0)
	{
		$videos = ZoneResourceVideo::model()->getPopularVideos($limit, $offset);
		$arrVideos = array();
		foreach ($videos as $video) {
			$arrVideos[] = array(
				'id'			=> IDHelper::uuidFromBinary($video->id, true),
				'title'			=> $video->title,
				'description'	=> $video->description,
				'views'			=> $video->views,
				'thumbnail'		=> $video->thumbnail,
				'type'			=> $video->type,
			);
		}
		ajaxOut(array(
			'error'	=> false,
			'data'	=> $arrVideos,
		));
	}

	/**
	 * This method is used to format user data
	 */
	private function _formatUserData($friends)
	{
		$currentUser = currentUser();
		$currentUser->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
		$arrFriends = array();
		foreach ($friends as $friend)
		{
			$userInfo = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($friend['user_id']));
			if ($currentUser->id != $userInfo->id)
				$userInfo->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
			$isFriend = $currentUser->isFriend($userInfo->id);
			$isPending = false;
			if (!$isFriend)
				$isPending = $currentUser->isPendingBy($userInfo->id);
			$isPendingMe = false;
			if (!$isFriend && !$isPending)
				$isPendingMe = $userInfo->isPendingBy($currentUser->id);

			$arrFriends[] = array(
				'user_id'		=> $userInfo->hexID,
				'username'		=> $userInfo->username,
				'displayname'		=> $userInfo->displayname,
				'email'			=> $userInfo->email,
				'isCurrentUser'	=> !$currentUser->isGuest && $currentUser->id == $userInfo->id,
				'isFriend'		=> $isFriend,
				'isPending'		=> $isPending,
				'isPendingMe'	=> $isPendingMe,
				'countFriends'	=> $userInfo->countFriends(),
				// Add more attribute here
			);
			if ($currentUser->id != $userInfo->id)
				$userInfo->detachBehavior('UserFriend');
		}

		$currentUser->detachBehavior('UserFriend');

		return $arrFriends;
	}

}