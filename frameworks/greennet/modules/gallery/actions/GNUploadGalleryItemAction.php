<?php
class GNUploadGalleryItemAction extends GNAction {

	private	$_uploadPath	= 'uploads/';
	public	$fieldName = "image";
	private	$_width			= 150;
	private	$_height		= 150;
	private $_uploader;
	public $return = false;
	
	public function init() {
		
	}
	
	public function setUploader($config) {
		$this->_uploader = Yii::createComponent($config);
	}
	/**
	 * 
	 * @param $width
	 */
	public function setWidth($width) {
		$this->_width	= $width;
	}
	
	/**
	 * 
	 * @return $width
	 */
	public function getWidth() {
		return $this->_width;
	}
	
	/**
	 * 
	 * @param $height
	 */
	public function setHeight($height) {
		$this->_height	= $height;
	}
	
	/**
	 * 
	 * @return $height
	 */
	public function getHeight() {
		return $this->_height;
	}
	
	/**
	 * 
	 * This method is used to set upload path
	 */
	public function setUploadPath($uploadPath) {
		$this->_uploadPath	= $uploadPath;
	}
	
	public function getUploadPath() {
		return $this->_uploadPath;
	}
	
	/**
	 * The main action that handles the file upload request.
	 */
	public function run() {
		$objectID = "";
		if (isset($_POST['object_id'])) {
			$objectID = $_POST['object_id'];
		}
		$controller = $this->controller;
		
		if (empty($this->_uploader)) {
			$config = array(
				'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
				'uploadPath'	=> $this->_uploadPath,	
			);
			
			$this->_uploader = Yii::createComponent($config);
		}
		
		$image	= $this->_uploader->upload($this->model, $this->fieldName);

		if (!empty($image)) {
			$filePath = "{$this->_uploader->uploadPath}/{$image['filename']}";
			// TODO: May be we need some validation here
			$this->model->image = $image['filename'];
			$this->model->id = IDHelper::uuidToBinary($image['fileid']);
			$this->model->object_id = $objectID;
			$this->model->created = date("Y-m-d H:i:s");
			
			// timestamp
			$time = explode(" ", microtime());
			$this->model->microtime = $time[0];
			$this->model->md5 = md5_file($filePath);
			$this->model->score = 100.0;
			
			$imageSize = @getimagesize($filePath);
			if (!empty($imageSize)) {
				$ratio = $imageSize[0] / $imageSize[1];
			
				$this->model->ratio = $ratio;
				$this->model->max_width = $imageSize[0];
				$this->model->max_height = $imageSize[1];
			} else {
				$this->model->invalid = 1;
			}
			
			$this->model->save();
			
			// Save associated data
			$belongsTo = $this->model->belongsTo;
			foreach ($belongsTo as $name => $instance) {
				$className = get_class($instance);
					
				if (isset($_POST[$className])) {
					$holderIDs = $_POST[$className];
					if (is_array($holderIDs)) {
						foreach ($holderIDs as $value) {
							$obj = new $className;
							$obj->image_id = $this->model->id;
							$obj->holder_id = IDHelper::uuidToBinary($value);
			
							$obj->save();
						}
					}
				}
			}
		}
		
		$out = array(
			"name"	=> $image['filename'],
			"type"	=> $image['type'],
			"size"	=> $image['size'],
			//"url"	=> $this->getFileUrl($model->{$this->fileNameAttribute}),
			"thumbnail_url"	=> GNRouter::createUrl("/{$this->_uploadPath}fill/{$this->width}-{$this->height}/{$image['filename']}"),
			"delete_url" => GNRouter::createUrl("/delete/{$image['fileid']}"),
			"delete_type" => "POST",
			"fileid"	=> $image['fileid']
		);
		if ($this->return) {
			return $out;
		}
		$return = array('files' => array($out));
		ajaxOut($return);
	}
}