<?php
Yii::import('greennet.components.GNUploader.components.engines.IStorageEngine');
Yii::import('greennet.extensions.s3.*');
class GNS3Engine extends CComponent implements IStorageEngine {
	public $metadata;
	public $created;
	private $uploadPath = "unclassified";
	public $caption;
	private $_con;
	private $_optimizeFileSize;
	public $uuid;
	private $_accessKey;
	private $_secretKey;
	private $_bucket;
	
	public function setServerInfo($info) {
		$this->_accessKey = $info['accessKey'];
		$this->_secretKey = $info['secretKey'];
		$this->_bucket = $info['bucket'];
// 		$config = array(
// 			'class'				=> 'EMongoDB',
// 			'connectionString'	=> "mongodb://{$info['server']}:{$info['port']}",
// 			'dbName'			=> $info['dbname'],
// 			'fsyncFlag'			=> true,
// 			'safeFlag'			=> true,
// 			'useCursor'			=> false
// 		);
		
// 		$this->_con = Yii::createComponent($config);
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
// 		$criteria = new EMongoCriteria;
// 		$criteria->_id = new MongoId($this->{'$id'});
// 		$this->deleteAll($criteria);
	}
	

// 	protected function beforeSave() {
// 		$this->created = time();
// 		return parent::beforeSave();
// 	}
	
	/**
	 * (non-PHPdoc)
	 * @see IStorageEngine::store()
	 */
	public function store($info) {
		$_defaultOptions = array(
			's3Path'	=> '/'
		);

		$options = CMap::mergeArray($_defaultOptions, $info);
		
		//$webroot = realpath(Yii::getPathOfAlias("jlwebroot"));
		$info['filePath'] = realpath($info['filePath']);
		
		$id = $info['fileid'];
		$filename = "{$info['filename']}";//$id . '.' . $validExtensions[$fileUpload->type];
		$filePath = "{$info['filePath']}";
		//$filesize = getimagesize($filePath);
		//$uploadPath = $webroot . "/" . substr($info['filePath'], 0, strrpos($info['filePath'], "/"));
		
		$fileinfo = pathinfo($filePath);
		$fileinfo['mime-type'] = $info['type'];
		
		$s3path = "{$info['s3path']}/" . strtolower($info['filename']);
		
		//AWS access info
		if (!defined('awsAccessKey')) define('awsAccessKey', $this->_accessKey);
		if (!defined('awsSecretKey')) define('awsSecretKey', $this->_secretKey);
		//instantiate the class
		$s3 = new S3(awsAccessKey, awsSecretKey);
		
		$ret = $s3->putObjectFile($filePath, $this->_bucket, trim($s3path, "/"), S3::ACL_PUBLIC_READ);
		
		return $fileinfo;
	}
	
	public function remove($info) {
		$s3path = trim("{$info['s3Path']}" . $info['filename'], "/");
		
		//AWS access info
		if (!defined('awsAccessKey')) define('awsAccessKey', $this->_accessKey);
		if (!defined('awsSecretKey')) define('awsSecretKey', $this->_secretKey);
		//instantiate the class
		$s3 = new S3(awsAccessKey, awsSecretKey);
		
		return $s3->deleteObject($this->_bucket, $s3path);
	}
}