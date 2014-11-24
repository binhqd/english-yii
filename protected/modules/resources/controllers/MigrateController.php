<?php
/**
 * MigrateController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Jun 21, 2013 10:49:08 AM
 */
//Yii::import('import something here');
class MigrateController extends GNController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}

	public function actions(){
		return array(
			
		);
	}
	
	
	public function actionIndex() {
		
		
		$webroot = Yii::getPathOfAlias('jlwebroot');
		$paths = array(
			//"{$webroot}/upload/articles",
			realpath("{$webroot}/upload/gallery")
		);
		
		$handle = opendir("{$webroot}/upload/user-photos");
		while ($dir = readdir($handle)) {
			if ($dir != "." && $dir != ".." && is_dir("{$webroot}/upload/user-photos/{$dir}")) {
				$paths[] = realpath("{$webroot}/upload/user-photos/{$dir}");
			}
		}
		
		$files = array();
		foreach ($paths as $path) {
			$_files = $this->_getImages($path);
			$files = array_merge($files, $_files);

		}
		
		
		// create queue
		$filepath = Yii::app()->runtimePath . "/mongofiles.txt";
		file_put_contents($filepath, "");
		foreach ($files as $file) {
			file_put_contents($filepath, implode("\n", $files));
		}
		
		$fileCount = count($files);
		//$this->redirect('/resources/migrate/migrate?total=' . count($files));
		$this->layout = 'ajax';
		$this->render('index', compact('fileCount'));
	}
	
	public function actionMigrate() {
		$engineConfig = array(
			'class'			=> 'greennet.components.GNUploader.components.engines.mongo.GNGridFSEngine',
			'serverInfo'	=> array(
				'server'	=> '54.215.136.218',
				'port'		=> 7474,
				'dbname'	=> 'myzonedev'
			)
		);
		$engine = Yii::createComponent($engineConfig);
		
		$filepath = Yii::app()->runtimePath . "/mongofiles.txt";
		
		$files = file($filepath);
		$files = array_map("trim", $files);
		$files = array_map("rtrim", $files);
		$files = array_map("ltrim", $files);
		
		//echo count($files);
		$n = 0;
		for ($i = 0; $i < 3; $i++) {
			if (!isset($files[$i])) break;
			if (empty($files[$i])) continue;
			
			$info = $this->_parseInfo($files[$i]);
			$engine->store($info);
			
			$n++;
			unset($files[$i]);
			
		}
		
		if (empty($files)) {
			ajaxOut(array(
				'error'		=> true,
				'message'	=> 'All files has been migrated'
			), false);
			file_put_contents($filepath, "");
		} else {
			ajaxOut(array(
				'error'		=> false,
				'message'	=> "{$n} files has been migrated",
				'n'			=> $n,
				'remain'	=> count($files)
			), false);
			file_put_contents($filepath, implode("\n", $files));
		}
	}
	
	private function _getImages($path) {
		$handle = opendir($path);
		$files = array();
		while ($file = readdir($handle)) {
			if ($file != "." && $file != ".." && preg_match("/[a-zA-Z0-9]{20,}/", $file)) {
				$files[] = "{$path}/{$file}";
			}
		}
		
		return $files;
	}
	
	private function _parseInfo($file) {
		$file =realpath($file);
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$format = finfo_file($finfo, $file);
		
		$filename = basename($file);
		preg_match("/([a-zA-Z0-9]{20,})\.(.*)/", $filename, $matches);
		
		$filePath = substr($file, strrpos($file, "upload" . DS));
		$ret = array(
			'fileid'	=> $matches[1],
			'filename'	=> $filename,
			'type'		=> $format,
			'size'		=> filesize($file),
			'ext'		=> $matches[2],
			'filePath'	=> $filePath
		);
		
		return $ret;
	}
	
	public function actionMigrateImageRatio() {
		$criteria = new CDbCriteria();
		$criteria->limit = 10;
		$criteria->condition = 'ratio=:ratio and invalid=0';
		$criteria->params = array(
			':ratio'	=> 0
		);
		$images = ZoneResourceImage::model()->findAll($criteria);
		
		if (empty($images)) {
			echo '';exit;
		}
		
		$n = 0;
		foreach ($images as $image) {
			$albumID = empty($image->album_id) ? $image->object_id : IDHelper::uuidFromBinary($image->album_id);
			$s3FilePath = Yii::app()->params['AWS']['S3URL'] . "/upload/gallery/{$albumID}/{$image->image}";
			
			$webroot = Yii::getPathOfAlias('jlwebroot');
			require_once("{$webroot}/external-tools/my_image.php");
			
			$headers = @get_headers($s3FilePath);
			if ($headers[0] == "HTTP/1.1 403 Forbidden" || !preg_match("/image\/(jpg|jpeg|png)/", $headers[6])) {
				$image->invalid = 1;
				$image->save();
				continue;
			}
			
			$my_image = new my_image($s3FilePath);
			
			$my_image->fit(800, 800);
			
			$dirPath = "/mnt/cache/original/{$albumID}";
			@mkdir($dirPath, 0755);
			
			$filePath = "{$dirPath}/{$image->image}";
		 	$my_image->copyTo($filePath);
			
			$config	= array(
				'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
				'uploadPath'	=> 'upload/gallery/',
				'storageEngines'	=> array(
					's3'	=> array(
						'class'			=> 'greennet.components.GNUploader.components.engines.s3.GNS3Engine',
						'serverInfo'	=> array(
							'accessKey'	=> Yii::app()->params['AWS']['S3']['upload']['accessKey'],
							'secretKey'	=> Yii::app()->params['AWS']['S3']['upload']['secretKey'],
							'bucket'	=> 'static.youlook.net'
						)
					)
				)
			);
			$s3Uploader = Yii::createComponent($config);
			
			$s3Uploader->store($filePath, array('s3path' => "upload/gallery/{$albumID}"));
			
			$size = getimagesize($filePath);
			$image->ratio = $size[0]/$size[1];
			
			$image->save();
			
			$n++;
		}
		
		echo "{$n} files has been migrated.";exit;
	}
	
	// TODO: Need to remove soon after mining
	public function actionCleanNodePhoto() {
		$id = Yii::app()->request->getParam('id');
		
		// get all images
		$images = ZoneResourceImage::model()->findAll('object_id=:object_id', array(
			':object_id'	=> $id
		));
		
		foreach ($images as $image) {
			$binImageID = $image->id;
			$fileid = IDHelper::uuidFromBinary($binImageID, true);
			try {
				$config = array(
					'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
					'uploadPath'	=> 'upload/gallery/',
					'storageEngines'	=> array(
						's3'	=> array(
							'class'			=> 'greennet.components.GNUploader.components.engines.s3.GNS3Engine',
							'serverInfo'	=> array(
								'accessKey'	=> Yii::app()->params['AWS']['S3']['upload']['accessKey'],
								'secretKey'	=> Yii::app()->params['AWS']['S3']['upload']['secretKey'],
								'bucket'	=> 'static.youlook.net'
							)
						)
					)
				);
			
				$uploader = Yii::createComponent($config);
			
				$image->cleanUp($uploader);
					
				ajaxOut(array(
					'error'		=> false,
					'fileid'	=> $fileid,
					'filename'	=> $image->image,
					'message'	=> "Gallery item has been deleted successful"
				));
			
			} catch (Exception $e) {
				ajaxOut(array(
					'error'		=> true,
					'message'	=> $e->getMessage()
				));
			}
			
			break;
		}
	}
}