<?php

/**
 * Users Controller
 * 
 * @author TienVV
 * @version 1.0
 */
class UsersController extends ApiController {

	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions() {
		return 'login,status,forgot,captcha,register,connectFacebook,country,following,topic,photo,video,stat,sticker,album,article,newMember,avatar,activity,facebookAccessToken';
	}

	public function actions() {
		return array(
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
				'testLimit' => 0,
			),
			'forgot' => 'application.modules.api.actions.users.ApiForgotPasswordAction',
			'register' => array(
				'class' => 'application.modules.users.actions.ZoneUserRegisterAction',
				'getAccessToken' => true
			),
			'connectFacebook' => 'application.modules.users.actions.ZoneConnectFacebookAction',
			'facebookAccessToken' => 'application.modules.api.actions.users.ApiFacebookAccessToken',
			'following' => 'application.modules.api.actions.users.ApiListFollowingAction',
			'topic' => 'application.modules.api.actions.users.ApiListTopicAction',
			'photo' => 'application.modules.api.actions.photos.ApiListPhotoByOwnerAction',
			'video' => 'application.modules.api.actions.users.ApiListVideoByUserAction',
			'notification' => 'application.modules.api.actions.users.ApiPullNotificationAction',
			'album' => 'application.modules.api.actions.photos.ApiListAlbumByOwnerAction',
			'newMember' => 'application.modules.api.actions.users.ApiListNewUserAction',
			'article' => 'application.modules.api.actions.articles.ApiListArticleAction',
			'activity' => 'application.modules.api.actions.activities.ApiListActivityAction',
			'changeAvatar' => array(
				'class' => 'application.modules.users.actions.ZoneChangeAvatarAction',
				'model' => array(
					'class' => 'application.modules.users.models.ZoneUserAvatar',
				),
				'fieldName' => 'image',
				'uploadPath' => 'upload/user-photos/'
			),
			'avatar' => 'application.modules.api.actions.photos.ApiListPhotoAvatarAction'
		);
	}

	public function actionArticle($id = '', $q = '') {
		$UserInfo = $this->userInfo($id);
		$this->initAction('article')->run($UserInfo->hexID, $q);
	}

	public function actionSticker($id = '') {
		$UserInfo = $this->userInfo($id);
		$Paginate = $this->paginate(0);

		Yii::import('application.components.notification.ZoneStickerNotificationDocument');
		$stickerItems = ZoneStickerNotificationDocument::getStickerItems(
						$UserInfo->hexID, $Paginate->limit, $Paginate->offset);

		$items = array();
		foreach ($stickerItems as $item) {
			$items[] = $item->data;
		}
		$this->out(200, array('data' => $items));
	}

	public function actionCountry() {
		$data = array();
		foreach (ZoneRegisterForm::getLocations() as $key => $val) {
			$data[] = array(
				'id' => $key,
				'name' => $val
			);
		}
		$this->out(200, array(
			'data' => $data
		));
	}

	public function actionLogin() {
		if (empty($_POST)) {
			$message = UsersModule::t('A request was made of a resource using a request method not supported by that resource');
			throw new Exception($message, 405);
		}
		$Model = new ZoneLoginForm();
		$Model->attributes = array(
			'email' => @$_POST['username'],
			'password' => @$_POST['password']
		);
		if (!$Model->validate()) { // Validate form login
			$this->out(401, array(
				'message' => UsersModule::t('Your email or password does not exist in our system.')
			));
		}
		$Model->user->forceLogin();
		$CurrentUser = new ApiZoneUser($Model->user);
		$this->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'accessToken' => $CurrentUser->accessToken(),
			'data' => array(
				'id' => $CurrentUser->hexID,
				'username' => $CurrentUser->username,
				'displayname' => $CurrentUser->displayname,
				'email' => $CurrentUser->email,
				'profile' => $CurrentUser->profile()
			)
		));
	}

	/**
	 * 	Register user
	 * 
	 * - firstname: Tien
	 * - lastname: Van Vo
	 * - email: tienvv@appdev.vn
	 * - password: 123456
	 * - confirmPassword: 123456
	 * - monthbirth: 2
	 * - daybirth: 2
	 * - yearbirth: 2012
	 * - location: location id
	 * - gender: 1/2/3
	 * - verifyCode: captcha code
	 * 
	 * @return void
	 */
	public function actionRegister() {
		if (empty($_POST)) {
			$message = UsersModule::t('A request was made of a resource using a request method not supported by that resource');
			throw new Exception($message, 405);
		}
		if (!currentUser()->isGuest) {
			$this->redirect('/user/profile');
		}
		$secretKey = 'MDNlYzE5NTQ5OWUzZmFiNGM2ZTkzYmRhY2YyZmE4ZWY1MmE1N2RhYjAxYTA0ZTMzODdjMzA4NzhjYmRkNTZjYg';
		if (isset($_POST['verifyCode']) && $_POST['verifyCode'] == $secretKey) {
			$_POST['verifyCode'] = $this->initAction('captcha')->getVerifyCode();
		}
		$Action = $this->initAction('register');
		list($code, $data) = $Action->register($_POST);
		if ($code == 200) {
			$Action->notify();
		}
		$this->out($code, $data);
	}

	public function actionConnectFacebook() {
		if (!currentUser()->isGuest) {
			$this->redirect('/user/profile');
		}

		$Action = $this->initAction('connectFacebook');
		list($code, $data) = $Action->connect(@$_GET['id'], @$_GET['token']);
		$this->out($code, $data);
	}

	public function actionTest() {
		$CurrentUser = new ApiZoneUser(currentUser());
		$this->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => array(
				'id' => $CurrentUser->hexID,
				'username' => $CurrentUser->username,
				'displayname' => $CurrentUser->displayname,
				'email' => $CurrentUser->email,
				'profile' => $CurrentUser->profile()
			)
		));
	}

	public function actionLogout() {
		ApiAccessToken::clearCurrentToken();
		Yii::app()->user->logout();

		$this->out(200, array(
			'message' => UsersModule::t('You are logged out')
		));
	}

	/**
	 * This action is used to get activities of a user
	 */
	public function actionActivity($id = null, $t = '') {
		$UserInfo = $this->userInfo($id);
		$Action = $this->initAction('activity');
		$Action->types = array(
			ZoneActivity::OBJECT_TYPE_ARTICLE,
			ZoneActivity::OBJECT_TYPE_ALBUM,
			ZoneActivity::OBJECT_TYPE_NODE,
			ZoneActivity::OBJECT_TYPE_VIDEO
		);
		$Action->run($UserInfo->hexID, $t);
	}

	public function actionStat($id = null, $full = true) {
		$UserInfo = $this->userInfo($id);
		$actions = array(
			'friend' => 'application.modules.api.actions.friends.ApiListFriendAction'
		);
		if($UserInfo->hexID != ZoneBaseContainer::SYSTEM_USERID){
			$stats = $UserInfo->stats;
		}
		$topics = array(
			'total' => intval(@$stats['topics'])
		);
		$articles = array(
			'total' => intval(@$stats['articles']),
		);
		$followings = array(
			'total' => intval(@$stats['followings']),
		);
		$videos = array(
			'total' => intval(@$stats['videos']),
		);
		$photos = array(
			'total' => intval(@$stats['photos']),
		);
		$FriendAction = $this->initAction('friend', $actions);
		$friends = array(
			'total' => $FriendAction->count($UserInfo->hexID),
		);
		$totalPending = $FriendAction->countPendingFriends();
		if ($totalPending !== false) {
			$friends['totalPending'] = $totalPending;
		}
		if ($full) {
			// topic
			$topics['items'] = array();
			// article
			$articles['items'] = array();
			// get photos info
			$PhotoAction = $this->initAction('photo');
			$_photos = $PhotoAction->get($UserInfo->hexID, false, 6);
			foreach ($_photos as &$photo) {
				unset($photo['photo']['poster']);
				$photos['items'][] = $photo['photo'];
			}
			// get followings
			$_followings = $this->initAction('following')->paginate($UserInfo->hexID, '', 6);
			foreach ($_followings as $val) {
				$image = ZoneResourceImage::getNamespaceImage($val['object_id']);
				if ($image) {
					unset($image['photo']['poster']);
					$followings['items'][] = $image['photo'];
				}
			}
			// get friends
			$friends['items'] = $FriendAction->get($UserInfo->hexID, '', 6);
			$_videos = $this->initAction('video')->get($UserInfo->hexID, '', 6);
			$videos['types'] = $_videos['types'];
			$videos['thumbnailDefault'] = $_videos['thumbnailDefault'];
			$videos['items'] = array();
			foreach ($_videos['data'] as $v) {
				$videos['items'][] = $v['video'];
			}
		}
		Yii::import('application.components.notification.JLNotificationReader');
		$result = array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'status' => array(
				'notification' => JLNotificationReader::countNotifications($UserInfo->hexID),
				'pendingFriends' => $UserInfo->countPendingFriends()
			),
			'id' => $UserInfo->hexID,
			'username' => $UserInfo->username,
			'displayname' => $UserInfo->displayname,
			'profile' => $UserInfo->profile(true),
			'following' => $followings,
			'video' => $videos,
			'photo' => $photos,
			'friend' => $friends,
			'topic' => $topics,
			'article' => $articles
		);
		$CurrentUser = new ApiZoneUser(currentUser());
		if (!$CurrentUser->isGuest && $UserInfo->id != $CurrentUser->id) {
			$isFriend = $isPendingMe = $isPending = false;
			if (!($isFriend = $CurrentUser->isFriend($UserInfo->id))) {
				$isPending = $CurrentUser->isPendingBy($UserInfo->id);
			}
			if (!$isFriend && !$isPending) {
				$isPendingMe = $UserInfo->isPendingBy($CurrentUser->id);
			}
			$result += compact('isFriend', 'isPendingMe', 'isPending');
		}
		$this->out(200, $result);
	}

}