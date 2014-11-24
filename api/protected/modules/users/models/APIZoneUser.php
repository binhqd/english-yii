<?php
/**
 * APIZoneUser - This is the model class for extends ZoneUser
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 1.0
 * @created 2014-08-06 10:00 AM
 */

class APIZoneUser extends ZoneUser
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param $className Class name of model
	 * @return GNUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This is method used to get new member
	 *  @author ngocnm
	 */
	public function countNewMembers()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "superuser <> 1";
		$criteria->order = "created DESC";
		$count = ZoneUser::model()->count($criteria);
		return $count;
	}

	/**
	 * This is method used to get new member
	 *  @author ngocnm
	 */
	public function getNewMembers($limit = 10, $offset = -1)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "superuser <> 1";
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		$criteria->order = "created DESC";
		$newMembers = ZoneUser::model()->findAll($criteria);

		// get info new member
		$arrayNewMember = array();
		if(!empty($newMembers)){
			foreach ($newMembers as $key => $user) {
				if ($user->id !== -1) {
					$user->setExtraInfo();
					$userInfo = ZoneApiResourceFormat::formatData('user', $user->toArray(true));
					$userInfo['stats'] = $user->stats;
					unset($userInfo['email']);
					unset($userInfo['location']);
				}
				$arrayNewMember[] = $userInfo;
			}
		}
		return $arrayNewMember;
	}

	/**
	 * Create user from facebook account
	 * @author huytbt <huytbt@gmail.com>
	 * @param array $fbUserInfo Facebook user information
	 * @return APIZoneUser
	 */
	public function createUserFromFacebook($fbUserInfo = array())
	{
		Yii::import('greennet.helpers.Sluggable');

		$password = uniqid();
		if(!isset($fbUserInfo['birthday'])){
			$bithday = time();
		} elseif (preg_match('/\/\d{4}$/', $fbUserInfo['birthday'])) {
			$pieces = array_reverse(explode('/', $fbUserInfo['birthday']));
			$bithday = strtotime(implode('-', $pieces));
		} else {
			$bithday = strtotime(str_replace('/', '-', $fbUserInfo['birthday']));
		}
		$gender = null;
		switch (@$fbUserInfo['gender']) {
			case 'male':
				$gender = 0;
				break;
			case 'female':
				$gender = 1;
				break;
		}
		$attributes = array(
			'firstname' => Sluggable::convertToLatin($fbUserInfo['first_name']),
			'lastname' => Sluggable::convertToLatin($fbUserInfo['last_name']),
			'email' => $fbUserInfo['email'],
			'password' => $password,
			'confirmPassword' => $password,
			'daybirth' => date('d', $bithday),
			'monthbirth' => date('m', $bithday),
			'yearbirth' => date('Y', $bithday),
		);
		$user = $this->createUser($attributes);
		$profile = array(
			'gender' => $gender,
			'birth' => date('d-m-Y', $bithday),
			'location' => @$result['location']['name']
		);
		$image = $this->_migrateFacebookAvatar($user->hexID, $fbUserInfo['id']);
		if (!empty($image)) {
			$profile['image'] = $image;
		}

		// Create user profile
		$modelProfile = new GNUserProfile;
		$modelProfile->createProfile($user->id, $profile);

		// Assign Permissions
		Rights::assign(Yii::app()->params['roles']['MEMBER'], $user->id);

		// Saved network linked
		$network = GNNetwork::model()->find('alias=:alias', array(
			':alias' => 'facebook'
		));
		$linkedAccount = GNLinkedAccount::model()->find('user_id=:user_id and network_id=:network_id', array(
			':user_id' => $user->id,
			':network_id' => $network->id
		));
		if (empty($linkedAccount)) {
			$linkedAccount = new GNLinkedAccount();
			$linkedAccount->user_id = $user->id;
			$linkedAccount->network_id = $network->id;
		}
		$linkedAccount->network_account_id = $fbUserInfo['id'];
		$linkedAccount->network_account_data = serialize($fbUserInfo);
		$linkedAccount->save();

		return $user;
	}

	/**
	 * Migrate facebook avatar
	 * @author huytbt <huytbt@gmail.com>
	 * @param string $userId Id of user
	 * @param string $facebookId If of facebook user
	 * @return string Filename
	 */
	private function _migrateFacebookAvatar($userId, $facebookId)
	{
		$userPhotoPath = YOULOOKROOT . "/wwwroot/jlwebroot/upload/user-photos/{$userId}/";
		//Get the file
		list(, $response) = InstanceCrawler::transport("https://graph.facebook.com/{$facebookId}/picture?type=large&redirect=false");
		if (!($json = json_decode($response, true)) || empty($json['data']['url'])) {
			return false;
		}
		// get ext image
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
		file_put_contents($userPhotoPath . "{$userId}.{$ext}", $content);

		return "{$userId}.{$ext}";
	}
}