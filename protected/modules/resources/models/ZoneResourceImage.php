<?php
Yii::import('greennet.modules.gallery.models.GNGalleryItem');
class ZoneResourceImage extends GNGalleryItem {
	
	const	DATA_STATUS_NORMAL = 1;
	const	DATA_STATUS_DELETED = 0;
	
	const TITLE_DEFAULT = "Untitled";
	
	public function userDeletePhoto() {
		return array('baonhu214dnyaho','tronghieu8120gmail','july87208yaho','nhutoanlegmail','thaihuongquyengmail','huytbtgmail');
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
	
	public function getName() {
		return __CLASS__;
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zone_images';
	}
	
	public function behaviors() {
		$cacheBehavior = array(
			'cache'	=> array(
				'class'		=> 'greennet.components.cache.memcache.GNMemcacheBehavior',
				'prefix'	=> 'zonephoto_'
			)
		);
		
		return CMap::mergeArray(parent::behaviors(), $cacheBehavior);
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
				'articles'	=> array(self::BELONGS_TO, 'ZoneArticle', 'object_id'),
				'ImagePoster'	=> array(self::HAS_ONE, 'ZoneImagePoster', 'image_id'),
			)
		);
	}
	
	/**
	 * This method is used to get all photos by data_status
	 * @param tinint $dataStatus
	 * @author: Chu Tieu
	 */
	public function getAllPhotos($dataStatus=null, $limit=30){
		$criteria = new CDbCriteria();
		if(isset($dataStatus)){
			$criteria->condition = 'data_status=:dataStatus';
			$criteria->params = array(
				':dataStatus' => $dataStatus
			);
		}
		
		$pages = new CPagination(count(self::model()->findAll($criteria)));
		
		$pages->pageSize=$limit;
		$pages->applyLimit($criteria);
		
		return array(
			'model'	=> self::model()->findAll($criteria),
			'pages'		=> $pages
		);
	}
	
	public function getTotal($objectID = null) {
		$cnt = 0;
		if (isset($objectID)) {
			$cnt = $this->count('object_id=:object_id and invalid=0', array(':object_id' => $objectID));
		}
		
		return $cnt;
	}
	
	public function getPoster() {
		$imagePoster = $this->ImagePoster;
		
		return !($imagePoster) ? $imagePoster : ZoneUser::model()->getUserInfo($imagePoster->holder_id);
	}

	public function getImagesFromObject($strObjectId=null,$limit = null,$order="score desc, created desc, microtime desc"){
		throw new Exception("Vui long su dung phuong thuc getPhotos thay cho phuong thuc nay");
		$criteria = new CDbCriteria();
		$criteria->condition = "object_id=:object_id and invalid=0";
		$criteria->params = array(':object_id' => $strObjectId);
		$criteria->order = $order;
		if($limit != null) $criteria->limit = $limit;
		return ZoneResourceImage::model()->findAll($criteria);
	}
	
	public static function getNamespaceImage($namespaceID) {
		Yii::import('application.modules.resources.models.ZoneResourceImage');
		
		$criteria = new CDbCriteria();
		$criteria->condition = "object_id=:object_id and invalid=0 and data_status = ".self::DATA_STATUS_NORMAL;
		$criteria->limit = 1;
		
		// $criteria->select = "id, name";
		$criteria->params = array(':object_id' => $namespaceID);
		$criteria->order = "score desc, created desc, microtime desc";
		
		$item = ZoneResourceImage::model()->find($criteria);
		
		if (!empty($item)) {
			$item = self::model()->get(IDHelper::uuidFromBinary($item->id, true));
		}
		
		return $item;
	}
	
	
	public function getImagesFromAlbum($binAlbumId=null, $limit = null, $offset = 0) {
		$command = Yii::app()->db->createCommand()
		->select('id')
		->from($this->tableName())
		->where("album_id=:album_id and invalid=0 and data_status=".self::DATA_STATUS_NORMAL)
		->order('score desc, created desc, microtime desc')
		->limit($limit)
		->offset($offset);
		
		$command->bindParam(':album_id', $binAlbumId);
		$results = $command->queryAll();
		
		$photos = array();
		foreach ($results as $item) {
			$photos[] = ZoneResourceImage::model()->get(IDHelper::uuidFromBinary($item['id'], true));
		}
		
		return $photos;
	}
	
	public function setData($data) {
		if (empty($data['photo']['id'])) {
			$this->isNewRecord = false;
			$data['photo']['id'] = null;
		}
		else {
			$data['photo']['id'] = IDHelper::uuidToBinary($data['photo']['id']);
		}
		
		// if album_id is equal with object_id so we won't need album_id
		if ($data['photo']['album_id'] == $data['photo']['object_id']) {
			unset($data['photo']['album_id']);
		}
		
		$this->setAttributes($data['photo'], false);
	}
	
	/**
	 * This method used tmp for demo :(
	 * Author: thinhpq
	 **/
	
	public function getPhotosForObject($strNodeId = null,$limit = 10){
		$criteria=new CDbCriteria();
		// $criteria->with = array("author","namespace");
		$criteria->together = true;
		$criteria->order = "score desc, created desc, microtime desc";
		
// 		$arrayArticles = array();
// 		$article = ZoneArticle::model()->getArticlesByObject(null,$binNodeId,null,null);
// 		if(!empty($article['data'])){
// 			foreach($article['data'] as $key=>$value){
// 				$arrayArticles[] = $value->id;
// 			}
// 		}
// 		// dump($arrayArticles,false);
		
		
		
// 		$criteria->addInCondition('t.album_id',$arrayArticles); 
		$criteria->addCondition("t.object_id =:object_id");
		$criteria->addCondition("t.data_status=:dataStatus");
		$criteria->params = array(
			':object_id'	=> $strNodeId,
			':dataStatus'	=> self::DATA_STATUS_NORMAL
		);
		
		if($limit!=null){
			$pages = new CPagination(count(ZoneResourceImage::model()->findAll($criteria)));
			$pages->pageSize = $limit;
			$pages->applyLimit($criteria);
		}
		
		
		
		return array(
			'pagination'=>!empty($pages) ? $pages : null,
			'data'=>ZoneResourceImage::model()->findAll($criteria)
		);
	}
	
	public static function countObjectPhotos($strObjectID) {
		$binAlbumID = IDHelper::uuidToBinary($strObjectID);
		$count = ZoneResourceImage::model()->count('(object_id=:object_id or album_id=:album_id) and invalid=0 and data_status=:dataStatus', array(
			':object_id'	=> $strObjectID,
			':album_id'		=> $binAlbumID,
			':dataStatus'	=> self::DATA_STATUS_NORMAL
		));
		
		return $count;
	}
	/**
	 * This method used get total articles for user
	 * Author: thinhpq
	 */
	public static function countImages($binNodeId = null){
		$count = ZoneResourceImage::model()->count('object_id=:object_id and invalid=0 and data_status=:dataStatus', array(
			':object_id'	=> IDHelper::uuidFromBinary($binNodeId, true),
			':dataStatus'	=> self::DATA_STATUS_NORMAL
		));
		
		return $count;
	}
	public static function countImagesForPoster($binPoster = null){
		$criteria=new CDbCriteria();
		$criteria->with = array("ImagePoster");
		$criteria->together = true;
		$criteria->addCondition("ImagePoster.holder_id =:holder_id");
		$criteria->addCondition("ImagePoster.data_status=:dataStatus");
		$criteria->params = array(
			':holder_id'	=> $binPoster,
			':dataStatus'	=> self::DATA_STATUS_NORMAL
		);
		
		return ZoneResourceImage::model()->count($criteria);
		
	}
	
// 	public function loadRelatedInfo($loadPoster = true, $loadLikes = true, $loadComments = 5) {
		
// 	}
	
	
	/**
	 * This method is used for get photo from cache
	 * @param unknown_type $key
	 * @return NULL
	 */
	public function get($photoID, $albumID = "", $options = array()) {
		$photo = parent::get($photoID, $albumID, $options);
		
		if (empty($photo)) {
			return null;
		}
			
		$totalComments = ZoneComment::model()->countComments($photoID);
		$photo['photo']['totalComments']	= $totalComments;
		$photo['photo']['commentOffset']	= 0;
		$photo['photo']['type']				= "gallery";
		
		return $photo;
	}
	
	public function cleanUp($uploader = null) {
		$attributes = $this->attributes;
		$albumID = $this->albumID;
		
		$this->delete();
		
		// also remove all likes
		ZoneLike::model()->removeAllByObjectID($attributes['id']);
		
		// also remove all comment
		ZoneComment::model()->removeAllByObjectID($attributes['id']);
		
		// Also remove related activities 
		
		// Remove physical image
		
		// Remove image from cloud
		$info['s3Path'] = "/upload/gallery/{$albumID}/";
		$info['filename'] = $attributes['image'];
		
		$uploader->remove($info);
	}
	
	/**
	 * This method is used to hide image
	 * @author: Chu Tieu
	 * VuNDH change code
	 */
	public function hideImage() {
		$this->data_status = self::DATA_STATUS_DELETED;
		if($this->save()){
			return true;
		}
		return false;
// 		also hide all likes
// 		ZoneLike::model()->hideAllByObjectID($attributes['id']);

// 		also hide all comment
// 		ZoneComment::model()->hideAllByObjectID($attributes['id']);

// 		Also remove related activities

// 		Remove physical image

// 		Remove image from cloud
// 		$info['s3Path'] = "/upload/gallery/{$albumID}/";
// 		$info['filename'] = $attributes['image'];

// 		$uploader->remove($info);
	}
	
	/**
	 * This method is used to restore image 
	 * @param $binID
	 * @return boolean
	 */
	public function restoreImage($binID=null){
		if(!empty($binID)){
			$model = ZoneResourceImage::model()->findByPk($binID);
			if(!empty($model)){
				$model->data_status = self::DATA_STATUS_NORMAL;
				if($model->save()){
					return true;
				} else return false;
			} else return false;
		}
		return false;
	}
	
	/**
	 * This is function use to get max score photo off node
	 */
	public function getMaxScore($strObjectID) {
		$criteria=new CDbCriteria;
		$criteria->compare('data_status', self::DATA_STATUS_NORMAL);
		$criteria->compare('object_id', $strObjectID);
		$criteria->order='score DESC, created DESC, microtime DESC';
		$photo = $this->model()->find($criteria);
		return $photo;
	}
	
	public static function createUrl($photo) {
		$url = ZoneRouter::createUrl("/photos/viewPhoto?photo_id={$photo['id']}&album_id={$photo['album_id']}");
	
		return $url;
	}
	
	
	//
}