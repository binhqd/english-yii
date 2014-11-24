<?php
Yii::import('ext.YiiMongoDbSuite.EMongoGridFS');
class JLFileFS extends EMongoGridFS
{
	public $created;
	public $metadata;

	private $uploadPath = "unclassified";
	
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
		return 'jl_files';
	}
	
	public function getUploadPath() {
		$uploadPath = Yii::getPathOfAlias('jlwebroot') . "/upload/{$this->uploadPath}/";
		if (!is_dir($uploadPath)) {
			@mkdir($uploadPath, 0755, true);
			@chmod($uploadPath, 0755);
		}
		
		return realpath($uploadPath) . "/";
	}
	
	// save uploaded file
	public function saveFile($fileUpload) {
		$uploadPath = $this->getUploadPath();
		$mongoID = new MongoId();
		$id = $mongoID->{'$id'};
		$filename = $id . '.' . $fileUpload->getExtensionName();
		$filename = strtolower($filename);
		
		$fileUpload->saveAs($uploadPath.$filename);
		
		$info = pathinfo($uploadPath.$filename);
		
		// $size = getimagesize("{$info['dirname']}/{$info['basename']}");
		
		$this->_id = $mongoID;
		$this->filename = $uploadPath.$filename;

		$this->metadata = array('ext'=> $info['extension'], 'info'=> $info);
		
		$res = $this->save();
		
		if($res !== true)
			throw new EMongoException('error saving file');
		
		return CMap::mergeArray($info,array('id'=>$id));
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

	public function getFile($id) {
		$criteria = new EMongoCriteria();
		$criteria->_id = new MongoId($id);
		$avatar = $this->find($criteria);
		return $avatar;
	}
	
	protected function beforeSave() {
		$this->created = time();
		return parent::beforeSave();
		
		
	}
	
}
