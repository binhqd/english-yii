<?php
Yii::import('greennet.components.GNUploader.components.engines.IStorageEngine');
Yii::import('greennet.extensions.YiiMongoDbSuite.*');
class GNGridFSEngine extends EMongoGridFS implements IStorageEngine {
	public $metadata;
	public $created;
	private $uploadPath = "unclassified";
	public $gridFSFile;
	public $caption;
	private $_con;
	private $_optimizeFileSize;
	public $uuid;
	
	public function setServerInfo($info) {
		$config = array(
			'class'				=> 'EMongoDB',
			'connectionString'	=> "mongodb://{$info['server']}:{$info['port']}",
			'dbName'			=> $info['dbname'],
			'fsyncFlag'			=> true,
			'safeFlag'			=> true,
			'useCursor'			=> false
		);
		
		$this->_con = Yii::createComponent($config);
	}
	
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
		return 'zone_files';
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
	
	public function delete() {
		$criteria = new EMongoCriteria;
		$criteria->_id = new MongoId($this->{'$id'});
		$this->deleteAll($criteria);
	}
	

	protected function beforeSave() {
		$this->created = time();
		return parent::beforeSave();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IStorageEngine::store()
	 */
	public function store($info) {
		$criteria = array(
			'conditions'	=> array(
				'uuid'	=> array("==" => $info['fileid'])
			)
		);
		
		//debug($criteria);
		$criteria = new EMongoCriteria($criteria);
		$file = $this->find($criteria);
		
		if ($file != null) {
			return;
		}
		
		// ---------------------------------------
		$id = $info['fileid'];
		
		$mongoID = new MongoId();
		
		$webroot = realpath(Yii::getPathOfAlias("jlwebroot"));
		$info['filePath'] = str_replace($webroot, "", realpath($info['filePath']));
		
		$filename = "{$id}.{$info['ext']}";//$id . '.' . $validExtensions[$fileUpload->type];
		$filePath = "{$webroot}/{$info['filePath']}";
		//$filesize = getimagesize($filePath);
		$uploadPath = $webroot . "/" . substr($info['filePath'], 0, strrpos($info['filePath'], "/"));
		
// 		if ($filesize[0] > 1440 && $filesize[1] > 900) {
// 			Yii::import('greennet.extensions.phpthumb.EasyPhpThumb');
// 			$thumbs = new EasyPhpThumb();
// 			$thumbs->init();
// 			$thumbs->setThumbsDirectory($uploadPath);
// 			$thumbs->load($filePath)
// 			->resize(1440, 900)
// 			->save($filename);
// 		}
		
		$fileinfo = pathinfo($filePath);
		$fileinfo['mime-type'] = $info['type'];
		
		$this->_id = $mongoID;
		$this->uuid = $id;
		$this->filename = $filePath;
		$this->metadata = array('ext'=> $fileinfo['extension'], 'info'=> $fileinfo);
		
		$res = $this->save();
		if($res !== true)
			throw new EMongoException(Yii::t("greennet", 'error saving file'));
		
		return $fileinfo;
	}
	
	public function remove($uuid) {
		$criteria = array(
			'conditions'	=> array(
				'uuid'	=> array("==" => $uuid)
			)
		);
		
		//debug($criteria);
		$criteria = new EMongoCriteria($criteria);
		$this->deleteAll($criteria);
	}
}