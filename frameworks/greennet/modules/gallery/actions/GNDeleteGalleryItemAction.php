<?php
class GNDeleteGalleryItemAction extends GNAction {
	/**
	 * Initialize the propeties of pthis action, if they are not set.
	 */
	private $_uploadPath	= 'uploads/';
	public $fieldName;
	private $_uploader;
	
	public function setUploader($config) {
		$this->_uploader = Yii::createComponent($config);
	}
	
	public function init( ) {
	}
	
	/**
	 * This method is used to set upload path
	 * 
	 * @param unknown $uploadPath
	 */
	public function setUploadPath($uploadPath) {
		$this->_uploadPath	= $uploadPath;
	}
	/**
	 * The main action that handles the file upload request.
	 */
	public function run() {
		$controller = $this->controller;
		if (!isset($_GET['fileid'])) {
			ajaxOut(array(
				'error'		=> true,
				'message'	=> Yii::t("greennet", "Invalid file ID"),
				//'url'		=> $backUrl
			));
		}
		try {
			// Perform delete gallery item
			$binImageID = IDHelper::uuidToBinary($_GET['fileid']);
			$obj = $this->model->find('id=:id', array(
				':id'	=> $binImageID
			));
			if (empty($obj)) {
				$out = array(
					'erorr'		=> true,
					'message'	=> Yii::t("greennet", "File doesn't exist")
				);
				ajaxOut($out);
			}
			
			// if file existed, continue
			if (empty($this->_uploader)) {
				$config = array(
					'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
					'uploadPath'	=> $this->_uploadPath,	
				);
				
				$this->_uploader = Yii::createComponent($config);
			}
			
			ajaxOut(array(
				'error'		=> false,
				'fileid'	=> $_GET['fileid'],
				'filename'	=> $obj->image,
				'message'	=> Yii::t("greennet", "Gallery item has been deleted successful")
			), false);
			
			$obj->cleanUp($this->_uploader);
			
			
		} catch (Exception $e) {
			ajaxOut(array(
				'error'		=> true,
				'message'	=> $e->getMessage()
			));
		}
	}
	protected function sendHeaders()
	{
		header('Vary: Accept');
		if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
			header('Content-type: application/json');
		} else {
			header('Content-type: text/plain');
		}
	}
	
}
