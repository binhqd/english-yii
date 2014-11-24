<?php
Yii::import('greennet.modules.users.models.GNUserProfile');
class ZoneUserProfile extends GNUserProfile {
	// public static $userNode = 'User Node';
	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	// private static $_countPhotos = array();

	/**
	 * @return array validation rules for model attributes.
	 */
	// public function rules()
	// {
		// return CMap::mergeArray(parent::rules(), array(
			// array('username, displayname', 'required', 'on'=>'edituserinfo'),
			// array('displayname', 'length', 'max'=>20, 'min'=>2,'on'=>'edituserinfo'),
			// array('username', 'unique', 'on'=>'edituserinfo'),
			// array('username', 'match' ,'pattern'=>'/^[A-Za-z0-9_]+$/u', 'message'=>'Username must only contain letters, numbers and underscores.', 'on'=>'edituserinfo'),
			// array('displayname', 'match' ,'pattern'=>'/^[^\'\/~`\!@#\$%\^&\*\(\)\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\\]+$/', 'message'=>'Display name cannot contain special characters.', 'on'=>'edituserinfo'),
		// ));
	// }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	// public function behaviors() {
		// $cacheBehavior = array(
			// 'cache'	=> array(
				// 'class'		=> 'greennet.components.cache.memcache.GNMemcacheBehavior',
				// 'prefix'	=> 'user_'
			// ),
			// 'photos'		=> array(
				// 'class'		=> 'application.modules.resources.behaviors.UserPhotosBehavior'
			// )
		// );
		
		// return CMap::mergeArray(parent::behaviors(), $cacheBehavior);
	// }
	
	public function relations()
	{
		$parents = parent::relations();
		unset($parents['profile']);
		
		return @CMap::mergeArray(
			$parents,
			array(
				'language'=>array(self::BELONGS_TO, 'Language', 'prefer_language_id'),
			)
		);
	}
}