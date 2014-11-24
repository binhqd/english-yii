<?php
class ZoneResourceVideo extends GNActiveRecord {
	public $videoPoster = null;
	
	const TYPE_ALL = 'all';
	const TYPE_FULL = 'full';
	const TYPE_TRAILER = 'trailer';
	const TYPE_OTHER = 'other';
	
	const DATA_STATUS_NORMAL = 1;
	const DATA_STATUS_DELETED = 0;
	const CONVERTED = 1;
	const CONVERT = 0;
	
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
		return 'zone_videos';
	}
	
	public function behaviors() {
		$cacheBehavior = array(
// 			'cache'	=> array(
// 				'class'		=> 'greennet.components.cache.memcache.GNMemcacheBehavior',
// 				'prefix'	=> 'zonephoto_'
// 			)
		);
		
		return CMap::mergeArray(parent::behaviors(), $cacheBehavior);
	}
	
	public function rules()
	{
		return array(
			//array('video', 'file', 'types'=>'avi'),
			array('title, description', 'safe')
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return CMap::mergeArray(
			parent::relations(),
			array(
				'VideoPoster'	=> array(self::BELONGS_TO, 'ZoneUser', 'owner_id'),
			)
		);
	}
	
	/**
	 * get poster.
	 * @author: Chu Tieu
	 */
	public function getPoster($binOwnerID = null) {
		if (empty($binOwnerID)) {
			$binOwnerID = $this->owner_id;
		}
		
		$poster = ZoneUser::model()->get(IDHelper::uuidFromBinary($binOwnerID, true));
		return $poster;
	}
	
	/**
	 * This method is used to get all videl by data_status
	 * @param tinint $dataStatus
	 * @author: Chu Tieu
	 */
	public function getAllVideos($dataStatus=null, $limit=100){
		
		$criteria = new CDbCriteria();
		if(isset($dataStatus)){
			$criteria->condition = 'data_status=:dataStatus';
			$criteria->params = array(
				':dataStatus' => $dataStatus
			);
		}
		$criteria->order = 'created desc';
		
		$pages = new CPagination(count(self::model()->findAll($criteria)));
		
		$pages->pageSize=$limit;
		$pages->applyLimit($criteria);
		
		return array(
			'model'	=> self::model()->findAll($criteria),
			'pages'		=> $pages
		);
	}

	/**
	 * This method is used to get popular videos
	 * @author huytbt <huytbt@gmail.com>
	 */
	public function getPopularVideos($limit = -1, $offset = -1)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'data_status = :dataStatus';
		$criteria->params = array(
			':dataStatus' => self::DATA_STATUS_NORMAL,
		);
		$criteria->order = 'views desc';
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		return self::model()->findAll($criteria);
	}

	/**
	 * This method is used to get videos of an object by given objectID
	 * @param string $objectID
	 * @param string $type
	 * @param int $limit
	 * @param int $offset
	 * @param int $dataStatus
	 * @return array $videos
	 */
	public function getVideosByObjectID($objectID = null, $type = self::TYPE_ALL, $limit = 15, $offset = 0, $dataStatus=self::DATA_STATUS_NORMAL){
		if (isset($objectID)) {
			
			$criteria = new CDbCriteria();
			
			$criteria->condition = 'object_id=:objectId And data_status=:dataStatus';
			if (in_array($type, array(self::TYPE_FULL, self::TYPE_TRAILER, self::TYPE_OTHER))) {
				$criteria->addCondition("type='{$type}'");
			}
			
			$criteria->params = array(
				':objectId'		=> IDHelper::uuidToBinary($objectID),
				':dataStatus'	=> $dataStatus
			);
			
			$criteria->limit = $limit;
			$criteria->offset = $offset;
			$criteria->order = 'case when type="full" then 1 when type="trailer" then 2 else 3 end asc , created desc';
			
			$results = self::model()->findAll($criteria);
			
			$return = array();
			
			foreach ($results as $item) {
				$strID = IDHelper::uuidFromBinary($item->id, true);
				$return[] = self::model()->get($strID);
			}
			
			return $return;
		} else return array();
	}
	
	/**
	 * This method is used to get videos of an object by given objectID
	 * @param string $objectID
	 * @param string $type
	 * @param int $limit
	 * @param int $offset
	 * @param int $dataStatus
	 * @return array $videos
	 */
	public function getVideosByUserID($binUserID = null, $type = self::TYPE_ALL, $limit = 15, $offset = 0, $dataStatus=self::DATA_STATUS_NORMAL){
		if (isset($binUserID)) {
			
			$criteria = new CDbCriteria();
				
			$criteria->condition = 'owner_id=:owner_id And data_status=:dataStatus';
			if (in_array($type, array(self::TYPE_FULL, self::TYPE_TRAILER, self::TYPE_OTHER))) {
				$criteria->addCondition("type='{$type}'");
			}
			
			$criteria->params = array(
				':owner_id'		=> $binUserID,
				':dataStatus'	=> $dataStatus
			);
				
			$criteria->limit = $limit;
			$criteria->offset = $offset;
			$criteria->order = 'case when type="full" then 1 when type="trailer" then 2 else 3 end asc , created desc';
				
			$results = self::model()->findAll($criteria);
				
			$return = array();
			
			foreach ($results as $item) {
				$strID = IDHelper::uuidFromBinary($item->id, true);
				$return[] = self::model()->get($strID);
			}
				
			return $return;
		} else return array();
	}
	
	/**
	 * get video detail by video id
	 */
	public function getVideoDetail($objectID = null, $type = self::TYPE_ALL, $limit = 15, $offset = 0){

		if (isset($objectID)) {
			
			$id = IDHelper::uuidToBinary($objectID);
			
			$videoDetail = ZoneResourceVideo::model()->findByPk($id);
			
			$nextVideo = self::model()->getVideosByObjectID(IDHelper::uuidFromBinary($videoDetail->object_id), $type, $limit, $offset);
			
			$criteria = new CDbCriteria();
			
			$criteria->condition = 'object_id=:objectId';
			if (in_array($type, array(self::TYPE_FULL, self::TYPE_TRAILER, self::TYPE_OTHER))) {
				$criteria->addCondition("type='{$type}'");
			}
			
			$criteria->params = array(
				':objectId' => IDHelper::uuidToBinary($objectID)
			);
			
			$criteria->limit = $limit;
			$criteria->offset = $offset;
			$criteria->order = 'case when type="full" then 1 when type="trailer" then 2 else 3 end asc , created desc';
			
			$results = self::model()->findAll($criteria);
			$return = array();
			
			foreach ($results as $item) {
				$strID = IDHelper::uuidFromBinary($item->id, true);
				$return[] = self::model()->get($strID);
			}
			
			return $return;
		} else return array();
	}
	
	/**
	 *
	 * @param boolean $loadPoster
	 * @param boolean $loadLikes
	 * @param int $loadComments
	 * @param array|object $video
	 * @return array photos
	 */
	public function loadRelatedInfo($loadPoster = true, $loadLikes = true, $loadComments = 5, $video = null) {
		if (is_null($video) || !is_array($video)) {
			$binVideoID = $this->id;
			$strVideoID = IDHelper::uuidFromBinary($binVideoID, true);
		} else {
			$strVideoID = $video['id'];
			$binVideoID = IDHelper::uuidToBinary($strVideoID);
		}
		$related = array();
		
		if ($loadPoster) {
			$related['poster'] = $this->poster;
		}
	
		$like = array();
		if ($loadLikes) {
			// Get like information
			Yii::import('application.modules.like.models.LikeStatistic');
			Yii::import('application.modules.like.models.LikeObject');
	
			$statistic = LikeStatistic::model()->getLikeStatistic($binVideoID);
				
			if(!empty($statistic)){
				$like = LikeObject::model()->getLikeInfo($binVideoID, currentUser()->id);
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
						'text'			=> 'Like',
						'object_id'		=> $strVideoID,
						'type'			=> 'like'
				);
			}
	
			$like['actionUnlike']	= ZoneRouter::createUrl('/photo/unlike');
	
				
		}
		$related['like'] = $like;
	
		$comments = array();
		if ($loadComments) {
			// get comments
				
			$comments = ZoneComment::model()->getComments($strVideoID, 0, $loadComments, true);
				
		}
		$related['comments'] = $comments;
	
		return $related;
	}
	
	public function getTotal($objectID = null) {
		$cnt = 0;
		if (isset($objectID)) {
			$cnt = $this->count('object_id=:object_id And data_status=:dataStatus', array(':object_id' => $objectID, ':dataStatus' => self::DATA_STATUS_NORMAL));
		}
		
		return $cnt;
	}
	
	public function get($videoID, $options = array()) {
		$_defaultOptions = $options = array(
			'loadPoster' => true,
			'loadLikes' => true,
			'loadComments' => 5
		);
		
		$options = CMap::mergeArray($_defaultOptions, $options);
		
		$videoInfo = array();
		
		if ($this->cacheable) {
			$videoInfo = $this->cache->get($videoID);
		}
		
		// get photo information
		if (empty($videoInfo)) {
			$videoInfo = array();
		
			$binVideoID = IDHelper::uuidToBinary($videoID);
			$video = $this->findByPk($binVideoID);
// 			$video->created = date(DATE_ISO8601, strtotime($video->created));

			if (empty($video)) {
				return null;
			} else {
				$video->length = VideoConvertor::timeToString((int)$video->length, true);
				$video->description = nl2br($video->description);
				// parser binary data
				$videoAttributes = $video->toArray();
				
				$relatedInfo = $video->loadRelatedInfo($options['loadPoster'], $options['loadLikes'], $options['loadComments']);
				
				$videoInfo['video'] = $videoAttributes;
				$videoInfo['video']['poster'] = $relatedInfo['poster'];
				$videoInfo['like'] = $relatedInfo['like'];
				$videoInfo['comments'] = $relatedInfo['comments'];
		
				$videoInfo['pagination'] = array(
					//'total'	=>	121,
					//'index'	=> 15
				);
				
				if ($this->cachable) {
					$this->cache->set($videoID, $videoInfo);
				}
			}
		}
		
		return $videoInfo;
		// TODO: Need to implement this method like ZoneResourceImage
	}
	
	public function cleanUp($uploader = null) {
		// TODO: Need to implement this method like ZoneResourceImage
	}
	
	public function toArray($getPoster = true) {
		$attr = $this->attributes;
		$attr['id'] = IDHelper::uuidFromBinary($attr['id'], true);
		$attr['object_id'] = IDHelper::uuidFromBinary($attr['object_id'], true);
		
		$binOwnerID = $attr['owner_id'];
		$attr['owner_id'] = IDHelper::uuidFromBinary($attr['owner_id'], true);
		
		
		return $attr;
	}
	
	public function getCacheable() {
		return (isset($this->cache) && isset($this->cache->instance));
	}
	public static function getTotalByType($objectID=null, $type=self::TYPE_ALL, $limit=15, $offset=0, $dataStatus=self::DATA_STATUS_NORMAL){
		if(!empty($objectID)){
			return self::model()->getVideosByObjectID($objectID, $type, $limit, $offset, $dataStatus);
		}
	}
	
	/**
	 * This method is used to get total By User
	 * @author: Chu Tieu
	 */
	public static function getTotalByUser($userUID=null, $type=self::TYPE_ALL, $limit=15, $offset=0, $dataStatus=self::DATA_STATUS_NORMAL){
		if(!empty($userUID)){
			$userBinID = IDHelper::uuidToBinary($userUID);
			$criteria = new CDBCriteria();
			
			$criteria->condition = 'owner_id=:owner_id And data_status=:dataStatus';
			$criteria->params = array(
				':owner_id'		=> $userBinID,
				':dataStatus'	=> $dataStatus
			);
			
			$criteria->limit = $limit;
			$criteria->offset = $offset;
			$criteria->order = 'created desc';
			
			return self::model()->count($criteria);
		}
	}
	
	/**
	 * This method is used to hide node's ID
	 */
	public function hideByObjectID($binObjectID=null){
	
		if(!empty($binObjectID)){
			$modelMovies = ZoneResourceVideo::model()->findAllByAttributes(array(
				'object_id'=>IDHelper::uuidToBinary($binObjectID),
				'data_status' => ZoneResourceVideo::DATA_STATUS_NORMAL
			));
			
			if(!empty($modelMovies)){
			
				$transaction = Yii::app()->db->beginTransaction();
				try {
					foreach($modelMovies as $modelMovie){
						$modelMovie->data_status = self::DATA_STATUS_DELETED;
						$modelMovie->save();
					}
					$transaction->commit();
					return true;
				} catch (Exception $e) {
					$transaction->rollBack();
					return false;
				}
			} else false;
		}
		return false;
	}
	
	/**
	 * This method is used to hide video's ID
	 * @author: Chu Tieu
	 */
	public function hideById($binID=null) {
		if(!empty($binID)) {
			
			$modelMovie = ZoneResourceVideo::model()->findByAttributes(array(
				'id'=>IDHelper::uuidToBinary($binID),
				'owner_id'=>currentUser()->id
			));
			
			if(!empty($modelMovie)) {
				$modelMovie->data_status = self::DATA_STATUS_DELETED;
				if($modelMovie->save()){
					try {
						Yii::import('application.modules.activities.models.ZoneActivity');
						// delete activities
						ZoneActivity::model()->deleteAll('object_id=:object_id', array(
							':object_id'	=> $modelMovie->id,
						));
					} catch (Exception $ex) {}
					/**
					 * index video for search (landing page) - remove index
					 * @author huytbt
					 */
					Yii::import('application.modules.landingpage.models.*');
					try {
						$index = ZoneSearchVideo::model()->removeIndex($modelMovie->id);
					} catch (Exception $ex) {
						Yii::log($ex->getMessage(), 'error', 'Search: remove index failure video (id:'.IDHelper::uuidFromBinary($modelMovie->id,true).')');
					}
					/* end (index) */
					return true;
				} else return false;
			} else return false;
		}
		
		return false;
	}
	
	/**
	 * This method is used to restore video's ID
	 * @author: Chu Tieu
	 */
	public function restoreById($binID=null){
	
		if(!empty($binID)){
		
			$modelMovie = ZoneResourceVideo::model()->findByAttributes(array(
				'id'=>IDHelper::uuidToBinary($binID),
				'owner_id'=>currentUser()->id
			));
			
			if(!empty($modelMovie)){
				$modelMovie->data_status = self::DATA_STATUS_NORMAL;
				if($modelMovie->save()){
					return true;
				} else return false;
			} else return false;
		}
		return false;
	}
	
	/**
	 * This method is used to hide video by condition
	 */
	public function hideByCondition($ownerId=null, $formDate=null, $toDate=null){
		
		if(!empty($ownerId) && !empty($formDate)){
			
			$criteria = new CDbCriteria();
			$ownerId = IDHelper::uuidToBinary($ownerId);
			
			if(!empty($ownerId)){
				$criteria->addCondition("owner_id='{$ownerId}'");
			}
			if(!empty($formDate) && !empty($toDate)){
				$criteria->addBetweenCondition('created', $formDate, $toDate);
			}
			if(!empty($formDate) && empty($toDate)){
				$criteria->addCondition("created='{$formDate}'");
			}
			
			$modelMovies = ZoneResourceVideo::model()->findAll($criteria);
			
			if(!empty($modelMovies)){
			
				$transaction = Yii::app()->db->beginTransaction();
				try {
					foreach($modelMovies as $modelMovie){
						$modelMovie->data_status = ZoneResourceVideo::DATA_STATUS_NORMAL;
						$modelMovie->save();
					}
					$transaction->commit();
					return true;
				} catch (Exception $e) {
					$transaction->rollBack();
					return false;
				}
			} else return false;
		} else return false;
	}
	
	public static function getTotalVideosByUser($binUserID) {
		$count = Yii::app()->db->createCommand()
		->select('count(*)')
		->from(ZoneResourceVideo::model()->tableName() . ' as video')
		->where('video.owner_id=:poster_id And data_status=:dataStatus', array(':poster_id' => $binUserID, ':dataStatus'=>self::DATA_STATUS_NORMAL))
		->queryScalar();
		
		return abs((int)$count);
	}

	/**
	 * This method is used to search video
	 * @author huytbt <huytbt@gmail.com>
	 */
	public function searchVideos($keyword = '', $limit = -1, $offset = -1, $timeBegin = null)
	{
		$criteria = new CDbCriteria();
		$criteria->order = 'created desc';
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		$criteria->condition = "t.title LIKE :keyword AND data_status=".self::DATA_STATUS_NORMAL;
		$criteria->params = array(':keyword' => "%$keyword%");
		if (!empty($timeBegin)) {
			$criteria->condition .= " AND t.created<=:timeBegin";
			$criteria->params[':timeBegin'] = date('Y-m-d H:i:s', $timeBegin);
		}
		return self::model()->findAll($criteria);
	}

	/**
	 * This method is used to count video
	 * @author huytbt <huytbt@gmail.com>
	 */
	public function countVideos($keyword = '', $timeBegin = null)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "t.title LIKE :keyword AND data_status=".self::DATA_STATUS_NORMAL;
		$criteria->params = array(':keyword' => "%$keyword%");
		if (!empty($timeBegin)) {
			$criteria->condition .= " AND t.created<=:timeBegin";
			$criteria->params[':timeBegin'] = date('Y-m-d H:i:s', $timeBegin);
		}
		return self::model()->count($criteria);
	}
	
	public static function createUrl($video) {
		$url = ZoneRouter::createUrl("/video/detail?id={$video['id']}");
		
		return $url;
	}
}