<?php
Yii::import('greennet.components.GNUploader.components.GNSingleUpload');
class GNSingleUploadImage extends GNSingleUpload
{
	private $_fileTypes	= array(
		'image/jpeg'	=> 'jpg',
		'image/jpg'		=> 'jpg',
		'image/png'		=> 'png',
		'image/gif'		=> 'gif',
	);
	private $_width		= 150;
	private $_height	= 150;
	private $_maxHeight	= 768;
	private $_maxWidth	= 1024;
	private $_maxSize	= null;
	
	/**
	 * 
	 * @return height
	 */
	public function getHeight() {
		return $this->_height;
	}
	
	/**
	 * 
	 * @return width
	 */
	public function getWidth() {
		return $this->_width;
	}
	
	/**
	 * 
	 * @param int $intHeight
	 */
	public function setHeight($intHeight) {
		$this->_height	= $intHeight;
	}

	/**
	 * 
	 * @param int $intWidth
	 */
	public function setWidth($intWidth) {
		$this->_width	= $intWidth;
	}
	
	/**
	 * 
	 * @param object $model
	 * @param string $field
	 * @return Ambigous <string, multitype:, multitype:string NULL >
	 */
	public function upload($model, $field) {
		$file	= CUploadedFile::getInstance($model, $field);
		if (!empty($file)) {
			
			
			if (array_key_exists($file->type, $this->_fileTypes)) {
				$fileExt	= $this->_fileTypes[$file->type];
				
				$upload	= parent::upload($model, $field);
				if (!empty($upload)) {
					$size	= getimagesize($upload['filePath']);
					if ($size[0]>1024 || $size[1]>768) {
						require_once(Yii::getPathOfAlias('webroot')."/external-tools/my_image.php");
						
						$my_image		= new my_image("{$this->uploadPath}{$upload['filename']}");
						$my_image->fit(1024, 768);
						$my_image->copyTo($upload['filePath']);
					}
					return $upload;
				} else {
					// TODO : notice error here
				}
			} else {
				Yii::log("Error on uploading image", CLogger::LEVEL_ERROR, "Upload Image");
			}
		}
	}
}