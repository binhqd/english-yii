<?php
class GNMongoUserDocument extends EMongoDocument {
	public $id;
	public $user_id;
	public $username;
	public $displayname;
	public $firstname;
	public $lastname;
	public $lastvisited;
	public $email;
	public $created;
	public $is_activated;
	public $status_text;
	public $location;
	public $phone;
	public $gender;
	public $stats = array(
		"reviews"	=> 0,
		"first_reviews"	=> 0,
		"lists"	=> 0,
		"friends"	=> 0,
		"groups"	=> 0,
		"points"		=> 0,
	);
	public $avatar = array(
		'primary'	=> null,
		'others'	=> array()
	);
	public $cache_modified;
	public $deleted;
	public $isCurrent = false;
	// This has to be defined in every model, this is same as with standard Yii ActiveRecord
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	// This method is required!
	public function getCollectionName()
	{
		return 'users';
	}
	
	public function init() {
		parent::init();
	}
	
	
	public function reset($hexID) {
		$user = GNUser::model()->findByPk(IDHelper::uuidToBinary($hexID));
		
		if (!isset($user)) return null;
		
		$profile = $user->profile;
		if (empty($profile)) {
			$profile = new GNUserProfile();
		}
		
		$mongoUser = new GNMongoUserDocument();
		
		$criteria = new EMongoCriteria;
		$criteria->user_id = $hexID;
		$obj = $mongoUser->find($criteria);
		
		if (isset($obj)) {
			$obj->delete();
		}
		
		$mongoUser->user_id = $hexID;
		$mongoUser->username = $user->username;
		$mongoUser->displayname = $user->displayname;
		$mongoUser->firstname = $user->firstname;
		$mongoUser->lastname = $user->lastname;
		$mongoUser->lastvisited = $user->lastvisited;
		$mongoUser->is_activated = $user->status == GNUser::STATUS_ACTIVATED ? true : false;
		$mongoUser->email = $user->email;
		$mongoUser->status_text = $profile->status_text;
		$mongoUser->location = $profile->location;
		$mongoUser->phone = $profile->phone;
		$mongoUser->gender = $profile->gender == GNUserProfile::TYPE_GENDER_MALE ? Yii::t("greennet", "Male") : Yii::t("greennet", "Female");
		$mongoUser->cache_modified = time();
		$mongoUser->created = $user->created;
		
		$mongoUser->save();
		
		return $mongoUser;
	}
	
}