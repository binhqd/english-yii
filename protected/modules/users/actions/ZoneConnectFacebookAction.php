<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 */
class ZoneConnectFacebookAction extends GNAction {

	/**
	 * Define view file (path)
	 */
	public $emailViewPath = 'application.views.mail';
	public $activeUrl = '/users/registration/activate/';
	public $scenario = 'Registration';

	/**
	 * This method is used to run action
	 */
	public function run($id = null, $token = null) {
		try {
			list($code, $data) = $this->connect($id, $token);
			$data['code'] = $code;
			ajaxOut($data);
		} catch (Exception $e) {
			ajaxOut(array(
				'error' => true,
				'type' => 'error',
				'autoHide' => true,
				'message' => $e->getMessage()
			));
		}
	}

	function connect($id, $token) {
		if (!$id || !$token) {
			throw new Exception(UsersModule::t('The data is invalid.'), 400);
		}
		@set_time_limit(60);
		$url = sprintf('https://graph.facebook.com/%s?access_token=%s', $id, $token);
		list($code, $response) = InstanceCrawler::transport($url);
		$result = @json_decode($response, true);
		if ($code != 200 || empty($result) || !empty($result['error'])) {
			throw new Exception(UsersModule::t('Can not connect to facebook with your id and access token.'), 500);
		}

		if (empty($result['email'])) {
			throw new Exception(UsersModule::t('We could not get your email address from Facebook response.
				Make sure your email address in your Facebook account is valid or being verified by Facebook.'), 500);
		}

		$statusCode = 200;
		$User = ZoneUser::model()->findByEmail($result['email']);
		if (!$User) {
			$statusCode = 201;
			$password = uniqid();
			Yii::import('greennet.helpers.Sluggable');

			// create new user
			if(!isset($result['birthday'])){
				$bithday = time();
			} elseif (preg_match('/\/\d{4}$/', $result['birthday'])) {
				$pieces = array_reverse(explode('/', $result['birthday']));
				$bithday = strtotime(implode('-', $pieces));
			} else {
				$bithday = strtotime(str_replace('/', '-', $result['birthday']));
			}
			$gender = null;
			switch (@$result['gender']) {
				case 'male':
					$gender = 0;
					break;
				case 'female':
					$gender = 1;
					break;
			}
			$attributes = array(
				'firstname' => Sluggable::convertToLatin($result['first_name']),
				'lastname' => Sluggable::convertToLatin($result['last_name']),
				'email' => $result['email'],
				'password' => $password,
				'confirmPassword' => $password,
				'daybirth' => date('d', $bithday),
				'monthbirth' => date('m', $bithday),
				'yearbirth' => date('Y', $bithday),
			);
			$User = ZoneUser::model()->createUser($attributes);
			$profile = array(
				'gender' => $gender,
				'birth' => date('d-m-Y', $bithday),
				'location' => @$result['location']['name']
			);
			$image = $this->_downloadAvatar($User->hexID, $id);
			if ($image) {
				$profile['image'] = $image;
			}
			// Create user profile
			$ModelProfile = new GNUserProfile;
			$ModelProfile->createProfile($User->id, $profile);
			// Assign Permissions
			Rights::assign(Yii::app()->params['roles']['MEMBER'], $User->id);
			
			// Saved network linked
			$Network = GNNetwork::model()->find('alias=:alias', array(
				':alias' => 'facebook'
			));
			$LinkedAccount = GNLinkedAccount::model()->find('user_id=:user_id and network_id=:network_id', array(
				':user_id' => $User->id,
				':network_id' => $Network->id
			));
			if (empty($LinkedAccount)) {
				$LinkedAccount = new GNLinkedAccount();
				$LinkedAccount->user_id = $User->id;
				$LinkedAccount->network_id = $Network->id;
			}
			$LinkedAccount->network_account_id = $id;
			$LinkedAccount->network_account_data = serialize($result);
			$LinkedAccount->save();
		}

		$User->forceLogin();
		$UserInfo = new ApiZoneUser($User);

		return array($statusCode, array(
				'cdn' => ZoneRouter::CDNUrl("/"),
				'accessToken' => $UserInfo->accessToken(),
				'data' => array(
					'id' => $UserInfo->hexID,
					'username' => $UserInfo->username,
					'displayname' => $UserInfo->displayname,
					'email' => $UserInfo->email,
					'profile' => $UserInfo->profile(),
				)
		));
	}

	protected function _downloadAvatar($userID, $facebookID) {
		// get ext image
		$userPhotoPath = dirname(Yii::app()->request->scriptFile) . "/upload/user-photos/{$userID}/";
		//Get the file
		list(, $response) = InstanceCrawler::transport("https://graph.facebook.com/{$facebookID}/picture?type=large&redirect=false");
		if (!($json = json_decode($response, true)) || empty($json['data']['url'])) {
			return false;
		}
		$e = explode('.', $json['data']['url']);
		$ext = array_pop($e);
		//Store in the filesystem.
		if (!file_exists($userPhotoPath)) {
			@mkdir($userPhotoPath, 0755, true);
		}
		$content = @file_get_contents($json['data']['url']);
		if (!$content) {
			return false;
		}
		file_put_contents($userPhotoPath . "{$userID}.{$ext}", $content);

		return "{$userID}.{$ext}";
	}

}