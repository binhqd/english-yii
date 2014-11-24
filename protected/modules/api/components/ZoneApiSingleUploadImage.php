<?php
Yii::import('greennet.components.GNSingleUploadImage.components.GNSingleUploadImage');
class ZoneApiSingleUploadImage extends GNSingleUploadImage
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
	 * @param object $model
	 * @param string $field
	 * @return Ambigous <string, multitype:, multitype:string NULL >
	 */
	public function upload($model, $field) {
		if (isset($_POST[$model->name][$field])) {
			$base64image = $_POST[$model->name][$field];
			$upload	= $this->_upload($base64image);
			$size	= $upload['imageSize'];
			if ($size[0]>1024 || $size[1]>768) {
				require_once(Yii::getPathOfAlias('webroot')."/external-tools/my_image.php");
				$my_image		= new my_image("{$this->uploadPath}{$upload['filename']}");
				$my_image->fit(1024, 768);
				$my_image->copyTo($upload['filePath']);
			}
			return $upload;
		} else {
			Yii::log("Error on uploading image", CLogger::LEVEL_ERROR, "Upload Image");
		}
	}

	private function _upload($base64image)
	{
		$imgdata = base64_decode($base64image);

		$f = finfo_open();
		$fileType = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
		$ext = $this->_fileTypes[$fileType];

		if (!array_key_exists($fileType, $this->_fileTypes)) {
			Yii::log("Error on uploading image", CLogger::LEVEL_ERROR, "Upload Image");
			return null;
		}

		$filename	= md5(uniqid());
		$newFile = strtolower("{$filename}.{$ext}");
		$filePath	= rtrim($this->uploadPath, "/")."/{$newFile}";

		$ifp = fopen( $filePath, "wb" ); 
		fwrite( $ifp, $imgdata ); 
		fclose( $ifp );

		$fileSize = filesize($filePath);
		$imageSize = getimagesize($filePath);

		$ret = array(
			'fileid'	=> $filename,
			'filename'	=> "{$newFile}",
			'type'		=> $fileType,
			'size'		=> $fileSize,
			'ext'		=> $ext,
			'filePath'	=> $filePath,
			'imageSize'	=> $imageSize,
		);

		return $ret;
	}
}