<?php
class GNManageGalleryWidget extends GNWidget {
	public $uploadTemplatePath		= 'greennet.modules.gallery.widgets.views.upload';
	public $downloadTemplatePath	= 'greennet.modules.gallery.widgets.views.download';
	
	public $uploadTemplateId	= 'template-upload';
	public $downloadTemplateId	= 'template-download';
	
	public $uploadPath;
	public $_model;
	public $_callbacks;
	public $uploadUrl;
	public $fieldName;
	public $object_id = "";
	public $deleteUrl;
	public $assetPath				= 'greennet.modules.gallery.assets';
	public $assetUrl;
	public $fileUri;
	private $_addedCallback			= "";
	private $_deletedCallback		= "";
	private $_changeCallback		= "";
	public $maxNumberOfFiles		= 0;
	public $width					= 150;
	public $height					= 150;
	
	public $sequentialUploads		= false;
	public $inputs = array();
	public $overrideOptions = array();
	public $dropzone = array(
	);
	
	public $objectHtmlOptions = array(
		'dropzone'=>null, // Optional 
		'gallery'=>null, // Optional 
		'fileupload'=>null, // Optional 
		'filesContainer'=>null, // Optional 
	);
	public $autoUpload = false;
	// this method is used to callback
	public function setCallbacks($callbacks) {
		if (!is_array($callbacks)) {
			throw new Exception("Invalid callbacks. See API docs for more detail");
		} else {
			//$this->_callbacks = $callbacks;
			if (isset($callbacks['added']) && is_string($callbacks['added'])) {
				$this->_addedCallback = $callbacks['added'];
			}
			
			if (isset($callbacks['deleted']) && is_string($callbacks['deleted'])) {
				$this->_deletedCallback = $callbacks['deleted'];
			}
			
			if (isset($callbacks['change']) && is_string($callbacks['change'])) {
				$this->_changeCallback = $callbacks['change'];
			}
			
			if (isset($callbacks['done']) && is_string($callbacks['done'])) {
				$this->_changeCallback = $callbacks['done'];
			}
			//style='background:#282828;'
		}
	}
	public function getAddedCallback() {
		return $this->_addedCallback;
	}
	
	public function getDeletedCallback() {
		return $this->_deletedCallback;
	}
	
	public function getChangeCallback() {
		return $this->_changeCallback;
	}
	
	public function init() {
		GNAssetHelper::init(array(
			'image'		=> 'img',
			'css'		=> 'css',
			'script'	=> 'js',
		));
		$this->assetUrl = GNAssetHelper::setBase($this->assetPath);
		
		GNAssetHelper::setBase('greennet.components.GNUploader.assets.jQueryUploadFile');
		// CSS Files
		// GNAssetHelper::cssFile('style'); // huytbt removed because style conflict
		GNAssetHelper::cssFile('jquery.fileupload-ui');
		//GNAssetHelper::cssFile('jquery.fileupload-ui-noscript');
		
		// Script Files
		GNAssetHelper::scriptFile('tmpl.min', CClientScript::POS_END);
		GNAssetHelper::scriptFile('vendor/jquery.ui.widget', CClientScript::POS_END);
		GNAssetHelper::scriptFile('load-image.min', CClientScript::POS_END);
		GNAssetHelper::scriptFile('canvas-to-blob.min', CClientScript::POS_END);
		GNAssetHelper::scriptFile('jquery.blueimp-gallery.min', CClientScript::POS_END);
// 		GNAssetHelper::scriptFile('jquery.blueimp-gallery.min', CClientScript::POS_END);
		GNAssetHelper::scriptFile('jquery.iframe-transport', CClientScript::POS_END);
		GNAssetHelper::scriptFile('jquery.fileupload', CClientScript::POS_END);
		GNAssetHelper::scriptFile('jquery.fileupload-process', CClientScript::POS_END);
		GNAssetHelper::scriptFile('jquery.fileupload-image', CClientScript::POS_END);
		GNAssetHelper::scriptFile('jquery.fileupload-validate', CClientScript::POS_END);
		GNAssetHelper::scriptFile('jquery.fileupload-ui', CClientScript::POS_END);
		
		GNAssetHelper::setBase('greennet.modules.gallery.assets');
		GNAssetHelper::cssFile('gngallery');
		//GNAssetHelper::cssFile('bootstrap-image-gallery.min');
		
		// Script Files
		//GNAssetHelper::scriptFile('bootstrap-image-gallery.min', CClientScript::POS_END);
	}
	public function run() {
		$this->render('upload-form', array(
			'width'				=> $this->width,
			'height'			=> $this->height,
			'maxNumberOfFiles'	=> $this->maxNumberOfFiles,
		));
	}
// This method is used to set upload path
	public function setUploadPath($uploadPath) {
		$path		= Yii::getPathOfAlias('wwwroot');
		$uploadPath	= "{$path}/{$uploadPath}";

		if (!is_dir($uploadPath)) {
			mkdir($uploadPath, 0755, true);
		}
		$this->_uploadPath	= $uploadPath;
	}
}