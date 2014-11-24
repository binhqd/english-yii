<?php
class GNUploadAction extends CAction {
	/**
	 * Initialize the propeties of pthis action, if they are not set.
	 *
	 * @since 0.1
	 */
	private $_uploadPath	= 'uploads/';
	private $_model;
	public $fieldName;
	
	public function init( ) {
		
	}
	
	/**
	 * This method is used to set model to current action
	 * 
	 * @param unknown $model
	 */
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
	
	/**
	 * Method to get current model
	 */
	public function getModel() {
		return $this->_model;
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
	 * 
	 * @since 0.1
	 * @author Asgaroth
	 */
	public function run() {
		$objectID = "";
		if (isset($_POST['object_id'])) {
			$objectID = $_POST['object_id'];
		}
		
		$controller = $this->controller;
		
		//$this->sendHeaders();
		$config = array(
			'class'			=> 'greennet.extensions.GNUploader.components.GNSingleUploadComponent',
			'uploadPath'	=> $this->_uploadPath,	// default folder uploads
			// 'folderFill'	=> 'fill/',		// default folder image copy is fill
			// 'width'		=> 200,		// default width image copy is 200
			// 'height'		=> 200,			// default height image copy is 200
		);
		$uploader = Yii::createComponent($config);
		$image	= $uploader->upload($this->_model, $this->fieldName);
		
		if (!empty($image)) {
			
			// TODO: May be we need some validation here
			$this->_model->filename = $image['filename'];
			$this->_model->id = IDHelper::uuidToBinary($image['fileid']);
			$this->_model->object_id = $objectID;
			$this->_model->created = date("Y-m-d H:i:s");
			$this->_model->save();
		}
		
		$return = array('files' => array(array(
			"name"	=> $image['filename'],
			"type"	=> $image['type'],
			"size"	=> $image['size'],
			//"url"	=> $this->getFileUrl($model->{$this->fileNameAttribute}),
			"thumbnail_url"	=> GNRouter::createUrl("/{$this->_uploadPath}/fill/64-64/{$image['filename']}"),
			"delete_url" => GNRouter::createUrl("/delete/{$image['fileid']}"),
			"delete_type" => "POST",
			"fileid"	=> $image['fileid']
		)));
		
		ajaxOut($return);
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
