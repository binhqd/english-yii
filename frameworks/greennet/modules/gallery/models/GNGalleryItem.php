<?php
class GNGalleryItem extends GNActiveRecord {
	public $prefix = "";
	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getName() {
		return __CLASS__;
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gallery';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			
		);
	}
	
	public function behaviors()
	{
		
		return array(
// 			'cache'	=> array(
// 				'class'	=> 'greennet.components.cache.memcache.GNMemCacheBehavior'
// 			)
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
			//'profile' => array(self::HAS_ONE, 'GNUserProfile', 'user_id'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
// 			'id' => Yii::t("greennet", 'ID'),
// 			'username' => Yii::t("greennet", 'Username'),
// 			'password' => Yii::t("greennet", 'Password'),
// 			'email' => Yii::t("greennet", 'Email'),
// 			'created' => Yii::t("greennet", 'Date Join'),
// 			'lastvisited' => Yii::t("greennet", 'Last Visited'),
// 			'superuser' => Yii::t("greennet", 'Superuser'),
// 			'status' => Yii::t("greennet", 'Status'),
// 			'firstname' => Yii::t("greennet", 'First Name'),
// 			'lastname' => Yii::t("greennet", 'Last Name'),
		);
	}
	
	public function cleanUp($uploader = null) {
		$attributes = $this->attributes;
		$this->delete();
		
		// Remove physical image
		if (isset($uploader)) {
			$uploader->remove($attributes);
		}
	}
	
	/**
	 * This method is used for get photo from cache
	 * @param unknown_type $key
	 * @return NULL
	 */
	public function get($photoID, $albumID = "", $options = array()) {
		$_defaultOptions = $options = array(
			'loadPoster' => true, 
			'loadLikes' => true, 
			'loadComments' => 5
		);
		
		$options = CMap::mergeArray($_defaultOptions, $options);
		
		$photoInfo = array();
		
		if ($this->cachable) {
			$photoInfo = $this->cache->get($photoID);
		}
	
		// get photo information
		if (empty($photoInfo)) {
			$photoInfo = array();
				
			$binPhotoID = IDHelper::uuidToBinary($photoID);
			$photo = $this->findByPk($binPhotoID);
				
			if (empty($photo)) {
				return null;
			} else {
				// parser binary data
				$photoAttributes = $photo->attributes;
				$photoAttributes['id'] = IDHelper::uuidFromBinary($photoAttributes['id'], true);
				
				/*$totalComments = ZoneComment::model()->countComments($photoID);
				$photoAttributes['totalComments'] = $totalComments;
				$photoAttributes['commentOffset'] = 0;*/
				
				if (empty($albumID)) {
					$photoAttributes['album_id'] = $photo->albumID;
				} else {
					$photoAttributes['album_id'] = $albumID;
				}
				
				$relatedInfo = $photo->loadRelatedInfo($options['loadPoster'], $options['loadLikes'], $options['loadComments']);
				
				$photoInfo['photo'] = $photoAttributes;
				$photoInfo['photo']['poster'] = $relatedInfo['poster'];
				$photoInfo['like'] = $relatedInfo['like'];
				$photoInfo['comments'] = $relatedInfo['comments'];
	
				$photoInfo['pagination'] = array(
					//'total'	=>	121,
					//'index'	=> 15
				);
	
				if ($this->cachable) {
					$this->cache->set($photoID, $photoInfo);
				}
			}
		}
	
		return $photoInfo;
	}
	
	/**
	 * 
	 * @param boolean $loadPoster
	 * @param boolean $loadLikes
	 * @param int $loadComments
	 * @param array|object $photo
	 * @return array photos
	 */
	public function loadRelatedInfo($loadPoster = true, $loadLikes = true, $loadComments = 5, $photo = null) {
		if (is_null($photo) || !is_array($photo)) {
			$binPhotoID = $this->id;
			$strPhotoID = IDHelper::uuidFromBinary($binPhotoID, true);
		} else {
			$strPhotoID = $photo['id'];
			$binPhotoID = IDHelper::uuidToBinary($strPhotoID);
		}
		$related = array();
		
		$userAttributes = array();
		if ($loadPoster) {
			if (is_null($photo) || !is_array($photo)) {
	
				$user = $this->poster;
				
				if (!empty($user)) {
					$userID = IDHelper::uuidFromBinary($user->id, true);
				} else {
					// Get guest instance of user
					$userID = -1;
				}
			} else {
				$userID = IDHelper::uuidFromBinary($photo['poster'], true);
			}
				
			$userAttributes = ZoneUser::model()->get($userID);
		}
		$related['poster'] = $userAttributes;
		
		$like = array();
		if ($loadLikes) {
			// Get like information
			Yii::import('application.modules.like.models.LikeStatistic');
			Yii::import('application.modules.like.models.LikeObject');
				
			$statistic = LikeStatistic::model()->getLikeStatistic($binPhotoID);
			
			if(!empty($statistic)){
				$like = LikeObject::model()->getLikeInfo($binPhotoID, currentUser()->id);
				if (isset($like['you_liked']) && $like['you_liked']) {
					$like['classRating']			= 'wd-liked-bt';
					$like['action']					= ZoneRouter::createUrl('/photo/like');
				} else {
					$like['classRating']			= '';
					$like['action']					= ZoneRouter::createUrl('/photo/like');
				}
			} else {
				$like	= array(
					'you_liked'		=> false,
					'classRating'	=> '',
					'action'		=> ZoneRouter::createUrl('/photo/like'),
					'value'			=> LikeObject::VALUE_RATING_LIKE,
					'count'			=> 0,
					'text'			=> Yii::t("greennet", 'Like'),
					'object_id'		=> $strPhotoID,
					'type'			=> 'like'
				);
			}
				
			$like['actionUnlike']	= ZoneRouter::createUrl('/photo/unlike');
				
			
		}
		$related['like'] = $like;
		
		$comments = array();
		if ($loadComments) {
			// get comments
			
			$comments = ZoneComment::model()->getComments($strPhotoID, 0, $loadComments, true);
			
		}
		$related['comments'] = $comments;
	
		return $related;
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
	
	public function deleteCache($key) {
		if ($this->cachable) {
			$this->cache->delete($key);
		}
	}
	
	/**
	 * This method is used to get album_id based on object_id and album_id
	 * @return Ambigous <multitype:, string>
	 */
	public function getAlbumID() {
		if (empty($this->album_id)) return $this->object_id;
		else return IDHelper::uuidFromBinary($this->album_id, true);
	}
	
	public function countPhotos($strObjectID) {
		$cnt = 0;
		if (isset($strObjectID)) {
			$cnt = $this->count('object_id=:object_id and invalid=0', array(':object_id' => "{$this->prefix}{$strObjectID}"));
		}
		
		return $cnt;
	}
	
	public function getPhotos($strObjectID, $limit = 5, $offset = 0) {
		$command = Yii::app()->db->createCommand()
		->select('hex(photo.id) as id')
		->from($this->tableName() . " as photo")
		->where('photo.object_id = :object_id and photo.invalid = 0 and photo.data_status=:dataStatus')
		->order('photo.created desc, photo.microtime desc')
		->offset($offset)
		->limit($limit);
		
		$command->bindValues(array(
			':object_id'		=> "{$this->prefix}{$strObjectID}",
			':dataStatus'		=> ZoneResourceImage::DATA_STATUS_NORMAL
		));
		
		$results = $command->queryAll();
		
		$photos = array();
		
		foreach ($results as $photo) {
			$photo = $this->get($photo['id']);
				
			$strToken = md5(uniqid(32));
			
			$photo['photo']['created'] = date(DATE_ISO8601, strtotime($photo['photo']['created']));
			$photo['photo']['url'] = ZoneRouter::CDNUrl("/upload/gallery/fill/165-10000/{$photo['photo']['image']}?album_id={$photo['photo']['album_id']}");
			
			//$photo['like']['actionUnlike']	= ZoneRouter::createUrl('/photo/unlike');
			$photo['like']['token']			= $strToken;
			$photo['token'] = $strToken;
	
			$photos[] = $photo;
		}
	
		return $photos;
	}
}