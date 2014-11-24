<?php
Yii::import('ext.YiiMongoDbSuite.EMongoGridFS');
class JLImageFS extends EMongoGridFS
{
	public $metadata;
	public $created;
	private $uploadPath = "unclassified";
	public $gridFSFile;
	public $width = 0;
	public $height = 0;
	public $caption;
	public $facebook_url = "";
	
	public function populateRecord($document, $callAfterFind=true)
	{
		if($document instanceof MongoGridFSFile)
		{
			$model = parent::populateRecord($document->file, $callAfterFind);
			$model->gridFSFile = $document;
			return $model;
		}
		else
			return parent::populateRecord($document, $callAfterFind);
	}
	
	/**
	 * this is similar to the get tableName() method. this returns tha name of the
	 * document for this class. this should be in all lowercase.
	 */
	public function getCollectionName()
	{
		return 'jl_images';
	}
	
	public function getUploadPath() {
		$uploadPath = Yii::getPathOfAlias('jlwebroot') . "/upload/{$this->uploadPath}/";
		if (!is_dir($uploadPath)) {
			@mkdir($uploadPath, 0755, true);
			@chmod($uploadPath, 0755);
		}
		
		return realpath($uploadPath) . "/";
	}
	
	public function downloadFiles($arrUrl,$uploadPath = null,$owner_id = null)
	{
		if($uploadPath == null)
			$uploadPath = $this->getUploadPath();
		
		foreach ($arrUrl as $url) {
			$mongoID = new MongoId();
			$id = $mongoID->{'$id'};
			$filename = strtolower($id . substr($url,strrpos($url,'.'))) ;
			$criteria = new EMongoCriteria;
			$url = str_replace('https://','http://',$url);
			$criteria->facebook_url  =  md5($owner_id.$url);
			
			$photo = $this->find($criteria);
			
			if($photo == null)
			{
				@file_put_contents($uploadPath.$filename,@file_get_contents($url));
			
				// resize image if it larger than 800 & 600
				$filesize = getimagesize($uploadPath.$filename);
				
				if ($filesize[0] > 1440 && $filesize[1] > 900) {
					Yii::import('ext.phpthumb.EasyPhpThumb');
					$thumbs = new EasyPhpThumb();
					$thumbs->init();
					$thumbs->setThumbsDirectory($uploadPath);
					$thumbs->load($uploadPath.$filename)
					->resize(1440, 900)
					->save($filename);
				}
				
				$info = pathinfo($uploadPath.$filename);
				
				$this->_id = $mongoID;
				$this->filename = $uploadPath.$filename;
				$this->metadata = array('ext'=> $info['extension'], 'info'=> $info);
				$this->facebook_url  =  md5($owner_id.$url);
				$res = $this->save();
			}
		}
		
	}
	
	public function downloadFile($url, $uploadPath = null, $owner_id = null, $allowDuplicate = false)
	{
		if($uploadPath == null)
			$uploadPath = $this->getUploadPath();
		
		$mongoID = new MongoId();
		$id = $mongoID->{'$id'};
		$filename = strtolower($id . substr($url, strrpos($url, '.')));
		
		if (!$allowDuplicate) {
			$criteria = new EMongoCriteria;
			$url = str_replace('https://', 'http://', $url);
			$criteria->facebook_url  =  md5($owner_id.$url);
			$photo = $this->find($criteria);
			if(!empty($photo))
			{
				return false;
			}
		}
		
		@file_put_contents($uploadPath.$filename, @file_get_contents($url));
	
		// resize image if it larger than 800 & 600
		$filesize = getimagesize($uploadPath.$filename);
		
		if ($filesize[0] > 1440 && $filesize[1] > 900) {
			Yii::import('ext.phpthumb.EasyPhpThumb');
			$thumbs = new EasyPhpThumb();
			$thumbs->init();
			$thumbs->setThumbsDirectory($uploadPath);
			$thumbs->load($uploadPath.$filename)
			->resize(1440, 900)
			->save($filename);
		}
		
		$info = pathinfo($uploadPath.$filename);
		
		$this->_id = $mongoID;
		$this->filename = $uploadPath.$filename;
		$this->metadata = array('ext'=> $info['extension'], 'info'=> $info);
		$this->facebook_url  =  md5($owner_id.$url);
		$res = $this->save();
		
		if($res !== true)
			throw new EMongoException('error while saving file');
		
		return $info;
	}
	
	
	// save uploaded file
	public function saveFile($fileUpload) {
		$validExtensions = array(
			'image/jpg'	=> 'jpg',
			'image/jpeg'	=> 'jpg',
			'image/pjpeg'	=> 'jpg',
			'image/png'	=> 'png',
			'image/gif'	=> 'gif'
		);
		
		$uploadPath = $this->getUploadPath();
		$mongoID = new MongoId();
		$id = $mongoID->{'$id'};
		$filename = $id . '.' . $validExtensions[$fileUpload->type];
		$filename = strtolower($filename);
		
		$fileUpload->saveAs($uploadPath.$filename);
		
		// resize image if it larger than 800 & 600
		$filesize = getimagesize($uploadPath.$filename);
		
		if ($filesize[0] > 1440 && $filesize[1] > 900) {
			Yii::import('ext.phpthumb.EasyPhpThumb');
			$thumbs = new EasyPhpThumb();
			$thumbs->init();
			$thumbs->setThumbsDirectory($uploadPath);
			$thumbs->load($uploadPath.$filename)
			->resize(1440, 900)
			->save($filename);
		}
		
		$info = pathinfo($uploadPath.$filename);
		$info['mime-type'] = $fileUpload->type;
		
		$size = getimagesize("{$info['dirname']}/{$info['basename']}");
		
		$this->_id = $mongoID;
		$this->filename = $uploadPath.$filename;
		$this->metadata = array('ext'=> $info['extension'], 'info'=> $info);
		$this->width = $size[0];
		$this->height = $size[1];
		
		$res = $this->save();
		
		if($res !== true)
			throw new EMongoException('error saving file');
		
		return $info;
	}
	
	public static function getUserImages($userid, $category = "") {
		
	}
	/**
	 * Returns the static model of the specified AR class.
	 *
	 * @param string $className class name
	 *
	 * @return CompaniesDb the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function rules()
	{
		return array(
			array('filename, metadata','safe'),
			array('filename','required'),
		);
	}
	
	public function delete($id) {
		$criteria = new EMongoCriteria;
		$criteria->_id = new MongoId($id);
		$this->deleteAll($criteria);
	}
	
	public function getImage($id) {
	
		$criteria = new EMongoCriteria();
		$criteria->_id = new MongoId($id);
		$avatar = $this->find($criteria);
	
		return $avatar;
	}
	
	protected function beforeSave() {
		$this->created = time();
		return parent::beforeSave();
		
		
	}
	
	public function getImageSize() {
		if ($this->width == 0 || $this->height == 0) {
			$info = $this->cacheInfo();
			$size = getimagesize("{$info['dirname']}/{$info['basename']}");
			$this->width = $size[0];
			$this->height = $size[1];
			$this->filename = null;
			$this->update(array('width', 'height'), true);
		}
		
		return array($this->width, $this->height);
	}
	
	public function base64ToImage($strBase64) {
		$uploadPath = $this->getUploadPath();
		$mongoID = new MongoId();
		$id = $mongoID->{'$id'};
		
		$validExtensions = array(
			'image/jpg'	=> 'jpg',
			'image/jpeg'	=> 'jpg',
			'image/pjpeg'	=> 'jpg',
			'image/png'	=> 'png',
			'image/gif'	=> 'gif',
		); 
		$match = preg_match("/data:(image\/(png|jpg|jpeg|gif));base64,/", $strBase64, $matches);
		$content = str_replace($matches[0], '', $strBase64);
		
		$mime = strtolower($matches[1]);
		
		if (!$match || count($matches) < 3 || !isset($validExtensions[$mime])) {
			throw new Exception("Base64 image string is not valid");
		}
		
		$filename = $id . '.' . $validExtensions[$mime];
		$filename = strtolower($filename);
		
		file_put_contents($uploadPath.$filename, base64_decode($content));
		
		// resize image if it larger than 800 & 600
		$filesize = getimagesize($uploadPath.$filename);
		
		if ($filesize[0] > 1440 && $filesize[1] > 900) {
			Yii::import('ext.phpthumb.EasyPhpThumb');
			$thumbs = new EasyPhpThumb();
			$thumbs->init();
			$thumbs->setThumbsDirectory($uploadPath);
			$thumbs->load($uploadPath.$filename)
			->resize(1440, 900)
			->save($filename);
		}
		
		$info = pathinfo($uploadPath.$filename);
		$info['mime-type'] = $mime;
		
		$size = getimagesize("{$info['dirname']}/{$info['basename']}");
		
		$this->_id = $mongoID;
		$this->filename = $uploadPath.$filename;
		$this->metadata = array('ext'=> $info['extension'], 'info'=> $info);
		$this->width = $size[0];
		$this->height = $size[1];
		
		$res = $this->save();
		
		if($res !== true)
			throw new EMongoException('Error while saving file');
		
		return $info;
	}
}
