<?php
/**
 * NodeController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Sep 6, 2013 3:54:30 PM
 */
//Yii::import('import something here');
class ProfileController extends ZoneApiController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}

	public function actions(){
		return array(

		);
	}

	public function actionIndex() {
		$id = Yii::app()->request->getParam('id');

		//$user = ZoneUser::model()->get($id);
		$usermodel = ZoneUser::model()->get($id);
		$userprofile = $usermodel['profile'];
		//$user = ZoneUser::model()->findByUsername($user['username']);

		$user = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($id));
		// dump($user);
		// unset($user['email']);
		unset($user['password']);
		unset($user['superuser']);
		unset($user['saltkey']);
		unset($user['created']);
		unset($user['status']);
		unset($user['lastvisited']);

		$user->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
		$countFriends = $user->countFriends();
		$user->detachBehavior('UserFriend');

		//if (currentUser()->isGuest || currentUser()->id != $user->id)
			//$urlFriends = GNRouter::createUrl('/friends/list', array('username'=>$user->username));

		$user->attachBehavior('UserFollowing', 'application.modules.followings.components.behaviors.GNUserFollowingBehavior'); // Attach behavior following for user
		$countFollowings = $user->countFollowingsByObjectType('object');
		$user->detachBehavior('UserFollowing');

		//$urlFollowings = GNRouter::createUrl('/followings/list/followings');
		//if (currentUser()->isGuest || currentUser()->id != $user->id)
			//$urlFollowings = GNRouter::createUrl('/followings/list/followings', array('username'=>$user->username));

		// if($userprofile){
		// 	$avatar_image_path = ZoneRouter::CDNUrl("/upload/user-photos/{$usermodel['id']}/fill/40-40/{$userprofile['image']}?album_id={$usermodel['id']}");
		// }

		// $totalPhotos = ZoneUser::model()->countPhotos($user->id);
		// dunghd fix count photo
		// count photo from 6 source 
		/**
		 * get other photos
		 * 1. photos posted on own timeline (object_id = user_id, album_id, owner_id)
		 * 2. photos posted on another timeline (object_id = another_id, album_id, owner_id)
		 * 3. photos posted on nodes
		 * 4. photos posted on articles (belongs to node by object_id)
		 * 5. photos sync from Facebook
		 * 6. photos posted by other users
		 */
		$totalPhotos = ZoneResourceImage::model()->countImages($user->id);

		$return_user = array(
			'user' => array(
				'id' => IDHelper::uuidFromBinary($user->id,true),
				'displayname' => $user->firstname,
				'firstname' => $user->firstname,
				'lastname' => $user->lastname,
				'username' => $user->username,
				'email' => $user->email,
				'profile' => $user->profile,
				"cdn" 	=>ZoneRouter::CDNUrl("/"),
				// 'avatar_image_path' => isset($avatar_image_path)?$avatar_image_path:null
			),
			'stats' => array(
				'count_friends' => $countFriends,
				'count_following' => $countFollowings,
				'count_contributions' => 0,
				'count_photo' => $totalPhotos,
				'count_videos' => 0,
			)
		);

		ajaxOut($return_user);
	}
}