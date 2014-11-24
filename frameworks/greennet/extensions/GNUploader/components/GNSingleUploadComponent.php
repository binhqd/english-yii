<?php
class GNSingleUploadComponent extends CComponent {
	private $fileTypes = array(
		'image/jpeg'	=> 'jpg',
		'image/jpg'		=> 'jpg',
		'image/png'		=> 'png',
		'image/gif'		=> 'gif',
		'image/pjpeg'	=> 'jpg', // Fix IE
		'image/x-png'	=> 'png', // Fix IE
	);
	private $_uploadPath	= 'uploads/';
	private $_folderFill	= 'fill/';
	private $_width			= 200;
	private $_height		= 200;
	// This method is used to check size of image
	private function checkSize($width, $height) {
		if ($width>1 || $height >1) {
			return true;
		} else return false;
	}
	
	// This method is used to copy into folder current and resize
	private function resize($filenameCurrent, $filenameNew, $width, $height) {
		require_once(Yii::getPathOfAlias('wwwroot')."/external-tools/my_image.php");
		$my_image	= new my_image("{$this->_uploadPath}{$filenameCurrent}");
		$my_image->fit($width, $height);
		$my_image->copyTo("{$this->_uploadPath}{$filenameNew}");
		return $this->deleteFileUploaded($filenameCurrent);
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
	// This method is used to set folder
	public function setFolderFill($folder) {
		$this->_folderFill	= $folder;
	}
	// This method is used to set Width of image
	public function setWidth($width) {
		$this->_width	= $width;
	}
	// This method is used to set Height of image
	public function setHeight($heigth) {
		$this->_height	= $heigth;
	}
	
	// This method is used to upload image 
	public function upload($model, $field) {
		$file	= CUploadedFile::getInstance($model, $field);
		if (!empty($file) && !$file->hasError) {
			$filename	= md5(uniqid()); 
			if (isset($this->fileTypes[$file->type]))
				$fileExt	= $this->fileTypes[$file->type];
			else
				throw new Exception(Yii::t('main', 'We do not support this type of file for upload photo.'));

			$filePath	= "{$this->_uploadPath}{$filename}.{$fileExt}";
			$file->saveAs($filePath);
			
			//$size		= $this->checkSize(getimagesize("{$this->_uploadPath}{$filename}.{$fileExt}")[0], getimagesize("{$this->_uploadPath}{$filename}.{$fileExt}")[1]);
// 			if ($size) {
// 				$filenameNew	= md5(uniqid());
// 				$this->resize("{$filename}.{$fileExt}", "{$filenameNew}.{$fileExt}", 1024, 768);
// 				$filename		= $filenameNew;
//  		}
			$ret = array(
				'fileid'	=> $filename,
				'filename'	=> "{$filename}.{$fileExt}",
				'type'		=> $file->type,
				'size'		=> $file->size
			);
			return $ret;
		} else {
			return array();
			Yii::log("Your upload image is invalid", CLogger::LEVEL_WARNING, "Upload Image");
		}
	}
	
	public function remove($filename) {
		return @unlink("{$this->_uploadPath}/{$filename}");
	}
	
	// This method is used to delete file in forder uploads
	public function deleteFileUploaded($filename) {
		return @unlink("{$this->_uploadPath}/{$filename}");
	}
	
	// This method is used to delete file in forder fil
	public function deleteFileFill($filename) {
		return unlink("{$this->_uploadPath}/{$this->_folderFill}/{$filename}");
	}
	
	// This method is used to Copy to folder fill
	public function copyFill($filename) {
		require_once(Yii::getPathOfAlias('wwwroot')."/external-tools/my_image.php");
		$my_image	= new my_image("{$this->_uploadPath}{$filename}");
		$my_image->fill($this->_width, $this->_height);
		$my_image->copyTo("{$this->_uploadPath}{$this->_folderFill}/{$filename}");
		
	}
}