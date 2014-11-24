<?php
// TODO: Need to migrate and set object_id for album
class ZoneResourceAlbum extends GNActiveRecord {
	const TITLE_DEFAULT = "Untitled";
	
	const	DATA_STATUS_NORMAL = 1;
	const	DATA_STATUS_DELETED = 0;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	
	private static $_albums = array();
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
		return 'zone_albums';
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
				'owner'	=> array(self::BELONGS_TO, 'ZoneUser', 'owner_id'),
				'AlbumNamespace'	=> array(self::HAS_ONE, 'ZoneAlbumNamespace', 'album_id'),
			)
		);
	}
	
	/**
	 * This method is used to get all album by data_status
	 * @param tinint $dataStatus
	 * @author: Chu Tieu
	 */
	public function getAllAlbum($dataStatus=null, $limit=100){
	
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
	
	public function getAlbumsByObjectID($binObjectID, $limit = 10, $offset = 0) {
		$results = Yii::app()->db->createCommand()
		->select('album_id')
		->from(ZoneAlbumNamespace::model()->tableName() . ' as bridge')
		->join(self::model()->tableName()." as album", "album.id=bridge.album_id")
		->where('bridge.holder_id=:id', array(':id'=>$binObjectID))
		->order('album.created DESC')
		->limit($limit)
		->offset($offset)
		->queryAll();
		
		$albums = array();
		foreach ($results as $item) {
			$album = $this->getAlbum($item['album_id']);
			$albums[] = $album->toArray();
		}
		
		return $albums;
	}
	
	public function getImages($binAlbumId=null, $limit = 6, $offset = 0){
		if (empty($binAlbumId))
			$binAlbumId = $this->id;
		return ZoneResourceImage::model()->getImagesFromAlbum($binAlbumId, $limit, $offset);
	}
	
	public static function getAlbumPhoto($binAlbumID) {
		$command = Yii::app()->db->createCommand()
		->select('image.id')
		->from(ZoneResourceImage::model()->tableName() . ' as image')
		->where('image.album_id=:album_id and image.data_status=:dataStatus', array(':album_id'=>$binAlbumID, ':dataStatus'=> self::DATA_STATUS_NORMAL))
		->order('image.created DESC, image.microtime DESC')
		->limit(1);
		
		$id = $command->queryScalar();
		
		if (!empty($id)) {
			return ZoneResourceImage::model()->get(IDHelper::uuidFromBinary($id, true));
		} else {
			return array();
		}
	}
	
	public function getOtherAlbum($binAlbumId = null,$binOwnerId = null){
		$criteria = new CDbCriteria();
		$criteria->addNotInCondition('id',array($binAlbumId));
		$criteria->order = "created desc";
		$criteria->compare('owner_id',$binOwnerId);
		$criteria->compare('data_status', self::DATA_STATUS_NORMAL);
		
		$pages = new CPagination(count( $this->findAll($criteria) ));
		$pages->pageSize = 15;
		$pages->applyLimit($criteria);
		
		return array(
			'data'=>$this->findAll($criteria),
			'pages'=>$pages
		);
	}
	
	/**
	 * This method is used to get albums of a user
	 * @param binary $binUserID
	 * @param int $limit
	 * @param int $offset
	 */
	public static function getAlbumsByUser($binUserID, $limit = 10, $offset = 0) {
		$criteria = new CDbCriteria();
		$criteria->condition = "owner_id=:owner_id and data_status=:dataStatus";
		$criteria->params = array(
			':owner_id'		=> $binUserID,
			':dataStatus'	=> self::DATA_STATUS_NORMAL
		);
		
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		$criteria->order = "created DESC";
		
		$records = self::model()->findAll($criteria);
		
		return $records;
	}
	
	/**
	 * This method is used to count total albums posted by user
	 * @param binary $binUserID
	 * @return int $total
	 */
	public static function countAlbumsByUser($binUserID) {
		$criteria = new CDbCriteria();
		$criteria->condition = "owner_id=:owner_id and data_status=:dataStatus";
		$criteria->params = array(
			':owner_id'		=> $binUserID,
			':dataStatus'	=> self::DATA_STATUS_NORMAL
		);
		
		$count = self::model()->count($criteria);
		
		return $count;
	}
	/**
	 * This is function used to get album
	 * @param binary $binAlbumID
	 * @author: VuNDH
	 */
	public function getAlbum($binAlbumID) {
		$strAlbumID = IDHelper::uuidFromBinary($binAlbumID, true);
		if (isset(self::$_albums[$strAlbumID])) {
			$album = self::$_albums[$strAlbumID];
		} else {
			$album = $this->findByPk($binAlbumID);
			self::$_albums[$strAlbumID] = $album;
		}
		return $album;
	}
	
	/**
	 * This method is used to return album information as an array, including poster
	 * @param string $strAlbumID
	 * @throws Exception Invalid album ID
	 * @return array $arrAlbum
	 */
	public static function get($strAlbumID) {
		$binAlbumID = IDHelper::uuidToBinary($strAlbumID);
		
		$album = self::model()->getAlbum($binAlbumID);
		if (empty($album)) {
			throw new Exception("Invalid album ID: {$strAlbumID}");
		}
		
		// parse into array
		$albumInfo = $album->toArray();
		
		return $albumInfo;
	}
	/**
	 * 
	 * This method is used to convert album object to an array
	 */
	public function toArray() {
		$attr = $this->attributes;
		$attr['id'] = IDHelper::uuidFromBinary($attr['id'], true);
		
		if (empty($attr['title'])) $attr['title'] = self::TITLE_DEFAULT;
		
		$owner = null;
		if (!empty($attr['owner_id'])) {
			$strPosterID = IDHelper::uuidFromBinary($attr['owner_id'], true);
			unset($attr['owner_id']);
			$owner = ZoneUser::model()->get($strPosterID);
			$attr['poster'] = $owner;
		}
		
		$attr['countAlbumPhotos'] = $this->countAlbumPhotos($this->id);
		return $attr;
	}
	
	/**
	 * This method is used to delete album
	 * @param $binAlbumID
	 */
	public static function cleanUp($binAlbumID) {
		Yii::import('application.modules.resources.models.*');
		$config = array(
			'class' => 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
			'uploadPath' => 'upload/gallery/',
			'storageEngines' => array(
				's3' => array(
					'class' => 'greennet.components.GNUploader.components.engines.s3.GNS3Engine',
					'serverInfo' => array(
						'accessKey' => Yii::app()->params['AWS']['S3']['upload']['accessKey'],
						'secretKey' => Yii::app()->params['AWS']['S3']['upload']['secretKey'],
						'bucket' => 'static.youlook.net'
					)
				)
			)
		);
		$uploader = Yii::createComponent($config);
		
		$images = ZoneResourceImage::model()->getImagesFromAlbum($binAlbumID);
		foreach ($images as $item) {
			$image = new ZoneResourceImage();
			$image->data = $item;
// 			$image = ZoneResourceImage::
			$image->cleanUp($uploader);
		}
		ZoneAlbumNamespace::model()->deleteAll('album_id=:album_id', array(':album_id'=>$binAlbumID));
		ZoneResourceAlbum::model()->deleteAll('id=:album_id', array(':album_id'=>$binAlbumID));
	}
	
	/**
	 * This method is used to hide album
	 * @param $binAlbumID
	 * @author : Chu Tieu
	 */
	public static function hideAlbum($binAlbumID) {
		// $binID = IDHelper::uuidToBinary('5243fb9f2e80484199880ff8c0a8014e');
		Yii::import('application.modules.resources.models.*');
		$config = array(
			'class' => 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
			'uploadPath' => 'upload/gallery/',
			'storageEngines' => array(
				's3' => array(
					'class' => 'greennet.components.GNUploader.components.engines.s3.GNS3Engine',
					'serverInfo' => array(
						'accessKey' => Yii::app()->params['AWS']['S3']['upload']['accessKey'],
						'secretKey' => Yii::app()->params['AWS']['S3']['upload']['secretKey'],
						'bucket' => 'static.youlook.net'
					)
				)
			)
		);
		$uploader = Yii::createComponent($config);
	
		$images = ZoneResourceImage::model()->getImagesFromAlbum($binAlbumID);
		foreach ($images as $item) {
			$image = new ZoneResourceImage();
			$image->data = $item;
			$image->hideImages($uploader);
		}
		ZoneAlbumNamespace::model()->hideAlbumNamespace($binAlbumID);
		ZoneResourceAlbum::model()->hideAlbumById($binAlbumID);
	}
	
	/**
	 * This method is used to Hide album
	 * @author: Chu Tieu
	 */
	public function hideAlbumById($binID){
		// check id
		if(!empty($binID)){
			// find album
// 			$binID = IDHelper::uuidToBinary('5243fb9f2e80484199880ff8c0a8014e');
			$model = self::model()->findByPk($binID);
			
			if(!empty($model)){
				$model->data_status = self::DATA_STATUS_DELETED;
				if($model->save()){
					/**
					 * index photo album for search (landing page) - remove index
					 * @author huytbt
					 */
					Yii::import('application.modules.landingpage.models.*');
					try {
						$index = ZoneSearchAlbum::model()->removeIndex($model->id);
					} catch (Exception $ex) {
						Yii::log($ex->getMessage(), 'error', 'Search: remove index failure photo album (id:'.IDHelper::uuidFromBinary($model->id,true).')');
					}
					/* end (index) */

					$modelImage = new ZoneResourceImage();
					$images = ZoneResourceImage::model()->findAllByAttributes(array('album_id'=>$binID,'data_status'=>self::DATA_STATUS_NORMAL));
					$countPhotoDelete = count($images);
					if(!empty($images)){
						foreach($images as $image){
							$image->hideImage();
						}
					}
					return $countPhotoDelete;
				};
				
			} else return -1;
		} else return -1;
	}
	
	/**
	 * This method is use to restore album
	 * @author : Chu Tieu
	 */
	public function restoreAlbumById($binID){
		// check id
		if(!empty($binID)){
			// find album
			$model = self::model()->findByPk($binID);
				
			if(!empty($model)){
				$model->data_status = self::DATA_STATUS_NORMAL;
				if($model->save()){
						
					$modelImage = new ZoneResourceImage();
					$images = ZoneResourceImage::model()->findAllByAttributes(array('album_id'=>$binID));
					if(!empty($images)){
						foreach($images as $image){
							$image->restoreImage();
						}
					}
					return true;
				};
	
			} else return false;
		} else return false;
	}
	
	public static function countAlbumPhotos($binAlbumID) {
		// get total
		$command = Yii::app()->db->createCommand()
		->select('count(*)')
		->from(ZoneResourceImage::model()->tableName() . " as image")
		->where('(image.album_id=:album_id) and image.invalid = 0 and data_status='.self::DATA_STATUS_NORMAL);
		
		$command->bindValues(array(
			':album_id'	=> $binAlbumID,
		));
			
		$count = $command->queryScalar();
		return $count;
	}
	
	public function setInvalid() {
		$this->invalid = 1;
		$this->save();
	}

	/**
	 * This method is used to search albums
	 * @author huytbt <huytbt@gmail.com>
	 */
	public function searchAlbums($keyword = '', $limit = -1, $offset = -1, $timeBegin = null)
	{
		$criteria = new CDbCriteria();
		$criteria->order = 't.created desc';
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		$criteria->condition = "t.title LIKE :keyword AND data_status=".self::DATA_STATUS_NORMAL." AND (SELECT count(id) FROM ".ZoneResourceImage::model()->tableName()." zi WHERE zi.album_id=t.id AND data_status=1)>0";
		$criteria->params = array(':keyword' => "%$keyword%");
		if (!empty($timeBegin)) {
			$criteria->condition .= " AND t.created<=:timeBegin";
			$criteria->params[':timeBegin'] = date('Y-m-d H:i:s', $timeBegin);
		}
		return self::model()->findAll($criteria);
	}

	/**
	 * This method is used to count albums
	 * @author huytbt <huytbt@gmail.com>
	 */
	public function countAlbums($keyword = '', $timeBegin = null)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "t.title LIKE :keyword AND data_status=".self::DATA_STATUS_NORMAL." AND (SELECT count(id) FROM ".ZoneResourceImage::model()->tableName()." zi WHERE zi.album_id=t.id AND data_status=1)>0";
		$criteria->params = array(':keyword' => "%$keyword%");
		if (!empty($timeBegin)) {
			$criteria->condition .= " AND t.created<=:timeBegin";
			$criteria->params[':timeBegin'] = date('Y-m-d H:i:s', $timeBegin);
		}
		return self::model()->count($criteria);
	}
}