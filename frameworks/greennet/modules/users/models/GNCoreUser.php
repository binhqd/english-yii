<?php
/**
 * GNUser - This model is used to process the data on the table userbase_users
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 24-Jan-2013 2:29:12 PM
 * @modified 29-Jan-2013 11:09:18 AM
 */
//Yii::import('greennet.modules.users.components.cache.*');
//Yii::import('greennet.modules.users.components.cache.mongo.*');
Yii::import('greennet.modules.social.behaviors.*');
Yii::import('greennet.modules.users.components.validators.*');
Yii::import('greennet.components.cache.memcache.*');

class GNCoreUser extends GNActiveRecord
{
	const STATUS_ACTIVATED = 1;
	const STATUS_NOACTIVE = 0;
	const STATUS_BANNED = 2;
	const STATUS_SUSPEND = 3;
	
	public $hexID;
	public $is_activated;
	private $_isGuest = true;
	private $_profile;
	private $_stats;
	public static $currentUser = null;
	public static $loadedUsers = array();
	protected $_assignedRoles;
	
	public function getAssignedRoles() {
		if (!isset($this->_assignedRoles)) {
			$this->_assignedRoles = Rights::getAssignedRoles($this->id);
		}
		
		return $this->_assignedRoles;
	}
	
	private $_isExist = true;
	public function getIsExist() {
		return $this->_isExist;
	}
	public function setIsExist($value) {
		$this->_isExist = ($value === 'false' || empty($value)) ? false : true;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'userbase_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, password', 'required'), //
			array('email', 'email'),
			array('email', 'unique', 'message' => Yii::t("greennet", "Your email has been already exists in system.")),
			array('created, lastvisited, superuser, status', 'numerical', 'integerOnly'=>true),
			array('password', 'length', 'max' => 64, 'min' => 6),
			array('saltkey', 'length', 'max'=>8),
			array('firstname, lastname', 'required'),
			array('firstname', 'length', 'max'=>30),
			array('lastname', 'length', 'max'=>20),
			// array('firstname', 'greennet.modules.users.components.validators.FirstnameValidator'),
			// array('lastname', 'greennet.modules.users.components.validators.LastnameValidator'),
			//array('firstname, lastname', 'safe', 'on'=>'updateBasicInfo'),
		);
	}
	
	public function behaviors()
	{
		
		return array(
			/*'cache'	=> array(
				'class'		=> 'greennet.components.cache.memcache.GNMemcacheBehavior',
				'prefix'	=> 'user_'
			),*/
// 			'cache' 	=> array(
// 				'class'	=>	'greennet.modules.users.components.cache.mongo.GNMongoUserCache'
// 			),
			'social'	=> array(
				'class'	=>	'greennet.modules.social.behaviors.GNUserSocial'
			)
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'profile' => array(self::HAS_ONE, 'GNUserProfile', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t("greennet", 'ID'),
			'username' => Yii::t("greennet", 'Username'),
			'displayname' => Yii::t("greennet", 'Display Name'),
			'password' => Yii::t("greennet", 'Password'),
			'email' => Yii::t("greennet", 'Email'),
			'created' => Yii::t("greennet", 'Date Join'),
			'lastvisited' => Yii::t("greennet", 'Last Visited'),
			'superuser' => Yii::t("greennet", 'Superuser'),
			'status' => Yii::t("greennet", 'Status'),
			'firstname' => Yii::t("greennet", 'First Name'),
			'lastname' => Yii::t("greennet", 'Last Name'),
		);
	}

	/**
	 * This method is used to check this user is current user logged or not
	 */
	public function getIsCurrent()
	{
		return (isset(Yii::app()->user->model) && $this->id == Yii::app()->user->model->id);
	}

	/**
	 * This method is used to set this used has authenticated
	 */
	public function isAuthenticated()
	{
		return $this->_isGuest == false;
	}
	
	public function getDisplayName() {
		$arr = array();
		if (!empty($this->firstname)) $arr[] = $this->firstname;
		if (!empty($this->lastname)) $arr[] = $this->lastname;
		return implode(' ', $arr);
	}
	/**
	 * This method is used to set this used has authenticated
	 */
	public function getIsGuest()
	{
		return Yii::app()->user->isGuest;
	}
	
	/**
	 * This method is used to get User from Email
	 * 
	 * @param string $strEmail
	 * @return NULL|GNUser
	 */
	public function findByEmail($strEmail)
	{
		if (empty($strEmail)) return null;
		
		$user = $this->find('email=:email', array(
			':email' => $strEmail,
		));
		
		if (!empty($user)) $user->setExtraInfo();
		
		return $user;
	}
	
	/**
	 * Similar with getUserInfo, but get a fresh record, not from cache
	 *
	 * @param unknown_type $strUsername
	 * @return NULL|Ambigous <CActiveRecord, mixed, NULL, multitype:, multitype:unknown Ambigous <CActiveRecord, NULL> , multitype:unknown >
	 */
	public function findByID($id) {
		if (empty($id)) return null;
		$user = $this->find('id=:id', array(
			':id' => $id,
		));
	
		if (!empty($user)) $user->setExtraInfo();
	
		return $user;
	}
	
	/**
	 * 
	 * @param unknown_type $strUsername
	 * @return NULL|Ambigous <CActiveRecord, mixed, NULL, multitype:, multitype:unknown Ambigous <CActiveRecord, NULL> , multitype:unknown >
	 */
	public function findByUsername($strUsername) {
		if (empty($strUsername)) return null;
		$user = $this->find('username=:username', array(
			':username' => $strUsername,
		));
		
		if (!empty($user)) $user->setExtraInfo();
		
		return $user;
	}
	
	
	/**
	 * This method is used to get user information, include firstname, last name, status text, ...
	 * 
	 * @param $binUserID Binary value of User ID
	 */
	public function getUserInfo($binUserID = null)
	{
		$hexID = IDHelper::uuidFromBinary($binUserID, true);

		$user = null;
		
// 		$cookieName = "userInfoCache-{$hexID}";
// 		$cookies = Yii::app()->request->cookies;
// 		if (isset($cookies[$cookieName])) {
// 			$since = time() - $cookies[$cookieName]->value;
// 			if ($since > Yii::app()->params['cacheResetTime']) {
// 				$user = new GNUser();
// 				$user = $user->loadFromCache($hexID);
// 				$user->updateState();
// 			}
// 		}
		
		if (!isset($user)) {
			if (isset(self::$loadedUsers[$hexID])) {
				$user = self::$loadedUsers[$hexID];
			} else {
				if (!$this->cacheable) {
					$user = $this->findByPk($binUserID);
					if (!empty($user)) { 
						$user->setExtraInfo();
					
						$this->set($hexID, $user->toArray(true));
					}
				} else {
					$userInfo = $this->cache->get($hexID);
					
					if (empty($userInfo)) {
						$user = $this->findByPk($binUserID);
						if (!empty($user)) {
							$user->setExtraInfo();
							$this->set($hexID, $user->toArray(true));
						}
					} else {
						
						$user = $this->model();
						
						unset($userInfo['id']); // keep id as binary
						$user->setAttributes($userInfo, false);
						$user->setExtraInfo();
// 						dump($user);
					}
					//$user = $this->cache->loadFromCache($hexID);
				}
				
				if (empty($user)) {
					//$class = get_called_class();
					$className = get_class($this);
					$user = new $className;
					$user->displayname = Yii::t("greennet", 'Not a user');
					$user->isExist = false;
					$user->id = -1;
				}
				
				self::$loadedUsers[$hexID] = $user;
			}
		}
		
		
		return $user;
	}
	
	public function setExtraInfo() {
		$this->hexID = IDHelper::uuidFromBinary($this->id, true);
	}
	/**
	 * This method is used to create a member.
	 * Return false if cannot create user
	 *
	 * @param arrInformation    Array of Information of User
	 */
	public function createUser($arrInformation, $className=__CLASS__) {
		// Set information
		if (!$this->isNewRecord) {
			$user = new $className;
		} else {
			$user = $this;
		}
		
		if (empty($arrInformation['firstname']) && empty($arrInformation['lastname'])) {
			throw new Exception(Yii::t("greennet", "Firstname & Lastname can't be both left empty"));
		}
		
		if (empty($arrInformation['firstname'])) $arrInformation['firstname'] = '';
		if (empty($arrInformation['lastname'])) $arrInformation['lastname'] = '';
		
		$user->setAttributes($arrInformation, false);
		
		
		if (!isset($arrInformation['displayname'])) {
			$arrInformation['displayname'] = self::createDisplayName($arrInformation['firstname'], $arrInformation['lastname']);
		}
		$user->displayname = $arrInformation['displayname'];
		
		if (!isset($arrInformation['username']) || empty($arrInformation['username'])) {
			$username = Sluggable::slug($arrInformation['email']);
			$username = preg_replace("/@/", '.', $username);
			$username = preg_replace("/(\.[a-z0-9]+)$/", '', $username);
			$user->username = $username;
		}
		
		$user->status = self::STATUS_ACTIVATED;
		$user->lastvisited = time();
		$user->superuser = 0;

		// Begin transaction
		//$transaction = Yii::app()->db->beginTransaction();
		// Validate information and create user
		
		$save = $user->save();
		if ($save) {
			//$transaction->commit(); // commit transaction
			$user->hexID = IDHelper::uuidFromBinary($user->id, true);
			return $user;
		} else {
			//$transaction->rollback(); // rollback transaction
			return false;
		}
	}
	
	/**
	 * This method is used to create display name from firstname & lastname
	 * @param string $firstname
	 * @param string $lastname
	 */
	public static function createDisplayName($firstname, $lastname) {
		return ucfirst($firstname) . " " . ucfirst($lastname);
	}

	/**
	 * This method is used to create display name from firstname & lastname
	 * @param string $firstname
	 * @param string $lastname
	 */
	public static function createUsername($firstname, $lastname, $email = null)
	{
		$username = strtolower($firstname).strtolower($lastname);
		if (!empty($email))
			$username = $email;
		$username = Sluggable::slug($username);
		$username = preg_replace("/@/", '.', $username);
		$username = preg_replace("/(\.[a-z0-9]+)$/", '', $username);
		// Check username exists?
		$model = self::model()->findByUsername($username);
		if (!empty($model)) {
			$username .= '-' . uniqid();
		}
		return $username;
	}

	/**
	 * This method is used to delete an account
	 *
	 * @param binUserId    Binary of User ID
	 */
	public function deleteUser($binUserId = null)
	{
		if ($binUserId == null) {
			return $this->delete();
		} else {
			$user = $this->findByPk($binUserId);
			
			return (!empty($user)) ? $user->delete() : true;
		}
	}

	/**
	 * This method is used to force login
	 *
	 * @param boolean $isRemember    Remember me or not
	 */
	public function forceLogin($isRemember = false)
	{
		$user = $this;
		$hexID = IDHelper::uuidFromBinary($user->id, true);
		$identity = new GNUserIdentity($user->id, $user->password);
		$identity->setAuthenticate($user);
		
		// Update time last visited
		$user->lastvisited = time();
		$user->save();
		$user = clone $user;
		
		$user->hexID = $hexID;
		
		$duration = 0;
		if ($isRemember) {
			$duration = 3600*24*30;
			$this->rememberMe();
		}
		Yii::app()->user->login($identity, $duration);
		
		$this->_isGuest = false;
		// protect safe attributes
		$user->saltkey = null;
		$user->password = null;
		
		// Save state user to session
		Yii::app()->user->setState('model', $user);
		
		self::$currentUser = $user;
		self::$loadedUsers[$user->hexID] = self::$currentUser;
		
		return true;
	}
	
	/**
	 * This method is used to remember
	 */
	public function rememberMe()
	{
		
	}

	/**
	 * This method is used to change password.
	 * Support salt key.
	 *
	 * @param strPassword    String of password
	 */
	public function changePassword($strPassword)
	{

		$user = $this;
		$strSalt = self::createSalt();
		$user->saltkey = $strSalt;
		$user->password = self::encryptPassword($strPassword, $strSalt);
		return $user->save();
	}
	
	/**
	 * This method is used to initial default information for user
	 */
	public function beforeSave()
	{
		if ($this->getIsNewRecord())
		{
			$this->created = time();
			if (isset($this->superuser)) $this->superuser = 0;
		}
		return parent::beforeSave();;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CActiveRecord::afterSave()
	 */
	public function afterSave($event = null) {
		parent::afterSave();
		
		if ($this->isNewRecord) {
			if($this->hasEventHandler('onCreated'))
				$this->onCreated(new CEvent($this));
		} else {
			if($this->hasEventHandler('onUpdated'))
				$this->onUpdated(new CEvent($this));
		}
		
		// Remove from cache
		$strUserID = IDHelper::uuidFromBinary($this->id, true);
		$this->deleteCache($strUserID);
		
		$this->updateState();
	}
	
	
	public function afterDelete() {
		parent::afterDelete();
		
		// TODO: Clean from cache too
		// Remove from cache
		$this->deleteCache(IDHelper::uuidFromBinary($this->id));
		
		if($this->hasEventHandler('onCreated')) {
			$this->onCreated(new CEvent($this));
		}
	}
	
	/**
	 * This method is used to parse current user object to an array of values
	 * 
	 * @return array
	 */
	public function toArray($getProfile = false) {
		$arr = $this->attributes;
		$arr['hexID'] = IDHelper::uuidFromBinary($arr['id'], true);
		$arr['id'] = $arr['hexID'];
		
		$arr['password'] = '';
		$arr['saltkey'] = '';
		
		if ($getProfile) {
			$profile = $this->profile;
			
			// If user is a guest
			if (empty($profile)) {
				$profile = new GNUserProfile();
			}
			$arr['profile'] = $profile->toArray();
		}
		//$arr['stats'] = $this->stats;
		//$arr['location'] = $this->location;
		//$arr['gender'] = $this->gender;
		//$arr['status_text'] = $this->status_text;
		//$arr['avatar'] = $this->avatar;
		return $arr;
	}
	
	/**
	 * This method is used to update state of current user, save current user object to session state
	 * @param string $isCurrent
	 */
	public function updateState($isCurrent = false) {
		if ($isCurrent || $this->isCurrent) {
			$this->setExtraInfo();
			
			Yii::app()->user->setState('model', $this);
			self::$currentUser = $this;
		}
		
		self::$loadedUsers[$this->hexID] = $this;
	}
	
	/**
	 * This method is used to do some callbacks function after user info has been saved
	 * 
	 * @param string $event
	 */
	public function afterValidate($event = null) {
		parent::afterValidate($event);
	}
	
	/**
	 * This method is used to create salt key
	 *
	 * @param intLength    Length of salt
	 */
	public static function createSalt($intLength = 8)
	{
		if ($intLength < 1) throw new InvalidArgumentException(Yii::t("greennet", 'Minimum length is 1'));
		if ($intLength > 32) throw new InvalidArgumentException(Yii::t("greennet", 'Maximum length is 32'));
		return substr(md5(uniqid()), 0, $intLength);
	}
	
	/**
	 * This method is used to encrypt password from password and salt key
	 *
	 * @param strPassword    String of password
	 * @param strSalt    String of salt key
	 */
	public static function encryptPassword($strPassword, $strSalt)
	{
		$strMD5 = md5($strPassword);
		$hash = $strSalt . $strMD5 . Yii::app()->params['systemSalt'];
		$strEncrypt = sha1($hash);
	
		return $strEncrypt;
	}
	
	public function getScreenName() {
		return $this->displayname;
	}
	
	/**
	 * This method is used to return profile link of current user object
	 * @return url
	 */
	public function getProfileLink() {
		if ($this->isCurrent) {
			return GNRouter::createUrl('/profile');
		} else {
			return GNRouter::createUrl('/profile/' . $this->username);
		}
	}
	
	public function getSafeAttributes() {
		return array(
			'username'	=> $this->username,
			'displayname'	=> $this->displayname,
			'firstname'	=> $this->firstname,
			'lastname'	=> $this->lastname,
			'id'		=> IDHelper::uuidFromBinary($this->id, true)
		);
	}
	
	public function getCachable() {
		return (isset($this->cache) && isset($this->cache->instance));
	}
	
	/**
	 * This method is used to set photo to cache
	 * @param unknown_type $key
	 * @param unknown_type $value
	 */
	public function set($key, $value) {
		if ($this->cachable) {
			$this->cache->set($key, $value);
		}
	}
	
	public function get($strUserID) {
		$userInfo = array();
		
		if ($this->cachable) {
			$userInfo = $this->cache->get($strUserID);
			
		}
		
		// get photo information
		if (empty($userInfo)) {
			$binUserID = IDHelper::uuidToBinary($strUserID);
			$user = $this->getUserInfo($binUserID);
			
			if (empty($user)) {
				$user = GNUser::model()->getUserInfo(-1);
				$userInfo = $user->toArray(true);
			} else {
				$userInfo = $user->toArray(true);
				$this->set($strUserID, $userInfo);
			}
		} else {
// 			dump('from cache');
		}
		
		return $userInfo;
	}

	public function deleteCache($key) {
		if ($this->cachable) {
			$this->cache->delete($key);
		}
	}
	
	/**
	 * Return true if user is admin privilege
	 * @return boolean
	 */
	public function getIsAdmin() {
		$roles = $this->assignedRoles;
		
		if (isset($roles['SAdmin'])) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Return true if user is member privilege
	 * @return boolean
	 */
	public function getIsMember() {
		$roles = $this->assignedRoles;
		
		if (isset($roles['Member'])) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Return true if user is authenticated privilege
	 * @return boolean
	 */
	public function getIsAuthenticated() {
		$roles = $this->assignedRoles;
	
		if (isset($roles['Authenticated'])) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Return true if user is awaiting privilege
	 * @return boolean
	 */
	public function getIsAwaiting() {
		$roles = $this->assignedRoles;
	
		if (isset($roles['Awaiting'])) {
			return true;
		} else {
			return false;
		}
	}
	
	public function setAvatar($filename) {
		if (empty($filename)) {
			watch("Empty filename for {$this->displayname}");
			return;
		} else {
			$webroot = Yii::getPathOfAlias("jlwebroot");
			$avatar = "{$webroot}/upload/user-photos/{$this->hexID}/{$filename}";
			if (!is_file($avatar)) {
				$parts = explode(".", $filename);
				$model = ZoneUserAvatar::model()->find("id=0x{$parts[0]}");
				$model->delete();
				return;
			}
		}
		$profile = $this->profile;
		if (!empty($profile)) {
			//watch(array($profile, $filename));
			$profile->image = $filename;
			$profile->save();
			$this->deleteCache($this->hexID);
		} else {
			watch("{$this->displayname} has empty profile");
		}
	}
}