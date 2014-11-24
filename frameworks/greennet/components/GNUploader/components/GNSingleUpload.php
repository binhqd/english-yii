<?php
class GNSingleUpload extends CComponent {
	private $_uploadPath;
	private $_storageEngines = array();
	public $extraInfo = array();
	
	/**
	 * This method is used to set storage engine for current upload component
	 * @param array $config
	 */
	public function setStorageEngine($key, $config) {
		$this->_storageEngines[$key] = Yii::createComponent($config);
	}
	
	public function getStorageEngine() {
		return $this->_storageEngines;
	}
	
	/**
	 * This method is used to set storage engines for current upload component
	 * @param array $configs
	 */
	public function setStorageEngines($configs) {
		foreach ($configs as $key => $config) {
			$this->setStorageEngine($key, $config);
		}
	}
	
	// This method is used to set upload path
	public function setUploadPath($uploadPath) {
		if (isset($this->_uploadPath))
			return $this->_uploadPath;
		
		$path		= Yii::getPathOfAlias('wwwroot');
		$uploadPath	= "{$path}/{$uploadPath}";
		
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath, 0755, true);
		}
		
		$this->_uploadPath	= $uploadPath;
		
		return $this->_uploadPath;
	}
	
	public function getUploadPath() {
		return $this->_uploadPath;
	}
	
	public function store($filePath, $config = array()) {
		if (!is_file($filePath)) return false;
		preg_match("/([a-fA-F0-9]{20,})\.(gif|png|jpg|jpeg|GIF|PNG|JPG|JPEG)$/", $filePath, $matches);
		
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$format = finfo_file($finfo, $filePath);
		
		$ret = array(
			'fileid'	=> $matches[1],
			'filename'	=> $matches[0],
			'type'		=> $format,
			'size'		=> filesize($filePath),
			'ext'		=> $matches[2],
			'filePath'	=> $filePath
		);
		
		$ret = CMap::mergeArray($config, $ret);
		// store file in storage engines
		foreach ($this->_storageEngines as $engine) {
			$engine->store($ret);
		}
			
		return $ret;
	}
	
	// This method is used to upload image 
	public function upload($model, $field) {
		$file	= CUploadedFile::getInstance($model, $field);
		
		if (!empty($file) && !$file->hasError) {
			$ext = $file->extensionName;
			
			$filename	= md5(uniqid());
			$newFile = strtolower("{$filename}.{$ext}");
			$filePath	= "{$this->uploadPath}/{$newFile}";
			$file->saveAs($filePath);
			
			$ret = array(
				'fileid'	=> $filename,
				'filename'	=> "{$newFile}",
				'type'		=> $file->type,
				'size'		=> $file->size,
				'ext'		=> $ext,
				'filePath'	=> $filePath
			);
			
			// store file in storage engines
			foreach ($this->_storageEngines as $engine) {
				$engine->store($ret, $this->extraInfo);
			}
			
			return $ret;
		} else {
			return array();
			Yii::log("Your upload image is invalid", CLogger::LEVEL_WARNING, "Upload Image");
		}
	}
	
	public function remove($info) {
		foreach ($this->_storageEngines as $engine) {
			$engine->remove($info);
		}
		
		return @unlink("{$this->_uploadPath}/{$info['filename']}");
	}
}