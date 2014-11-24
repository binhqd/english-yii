<?php
class GNMultiUploadWidget extends CWidget {
// 	public $uploadUrl;
	public $uploadTemplatePath = 'greennet.extensions.GNUploader.widgets.views.upload';
	public $downloadTemplatePath = 'greennet.extensions.GNUploader.widgets.views.download';
	
	public $uploadPath;
	public $_model;
	public $callbacks;
	public $uploadUrl;
	public $fieldName;
	public $object_id = "";
	public $deleteUrl;
	public $dropzone = array(
		
	);
	
	public function run() {
		$this->render('upload-form');
	}
	
	// This method is used to set upload path
	public function setUploadPath($uploadPath) {
		$path		= Yii::getPathOfAlias('webroot');
		$uploadPath	= "{$path}/{$uploadPath}";

		if (!is_dir($uploadPath)) {
			mkdir($uploadPath, 0755, true);
		}
		$this->_uploadPath	= $uploadPath;
	}
	
	// method contractor
	public function setModel($model){
		// import model
		Yii::import($model);
		
		if (is_string($model)) {
			$config = array(
				'class'	=> $model
			);
			$this->_model = Yii::createComponent($config);
		} else {
			$this->_model = $model;
		}
	}
	
	public function getModel() {
		return $this->_model;
	}
	
// 	public function setUploadPath() {
// 		Yii::import('xupload.actions.XUploadAction');
// 		$xUploadAction = new XUploadAction();
// 		$xUploadAction->publicPath	= Yii::app() -> getBaseUrl() . "/images";
// 		//$xUploadAction->path		= Yii::app() -> getBasePath() . "/../images";
// 	}
	public function setUpload($uploadTemplatePath) {
		$this->_uploadTemplatePath	= $uploadTemplatePath;
		
	}
	private function setUploadTemplate() {
		Yii::import($this->_uploadTemplatePath);
		$xUpload	= new XUpload();
		$xUpload->uploadView	= $this->_uploadTemplatePath;
	}
	public function init() {
		// TODO :
	}
	// method run when call widget
	
	
	public function getUploadPath($uploadpath) {
		return true;
	}
	public function checkNumberOfUploadFile($number) {
		return true;
	}
	public function uploadImage() {
		$modelUploadForm	= new UploadForm();
		$classModel			= new $this->_model;
		if (!empty($_POST["{$this->_classModel}"]) && !empty($_POST['images'])) {
			$imagesName 			= $_POST['images'];
			$classModel->attributes	= $_POST["{$this->_classModel}"];
			if ($classModel->save()) {
				$numberOfImages	= count($imagesName);
				for ($i=0;$i<$numberOfImages;$i++) {
					$gallery	= new Gallery();
					$gallery->name				= date( "mdY" ).'/'.$imagesName[$i];
					$gallery->destination_id	= $classModel->id;
					$gallery->save();
				}
			}
		} else {
			$this -> render('ViewMultiUpload', array(
				'modelXUploadForm' 		=> $modelXUploadForm,
				'classModel'	=> $classModel,
			));		
		}
	}
}