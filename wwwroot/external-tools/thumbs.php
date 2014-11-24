<?php
extract($_REQUEST);
require(dirname(__FILE__) . '/../../../jlprotected/config/constants.php');
$filename = "{$name}.{$ext}";
$filepath = realpath(dirname(__FILE__) . "/.." . str_replace("/thumbs/{$w}-{$h}", "", $uri));
$dirPath = dirname($filepath) . "/thumbs/{$w}-{$h}";

$imagesize = @getimagesize("{$filepath}");
if(!$imagesize){
	require_once("placehold.php");
	if($h>1000) $h = 220;
	createImgPlaceHold($w,$h,282828,969696);
}

if ($filepath !== false) {
	// thumbnail file
	require_once("my_image.php");
	$my_image = new my_image($filepath);
	
	$my_image->fit($w, $h);
 	$my_image->copyTo("{$dirPath}/{$filename}");
	$my_image->show();
} else {
	// check in mongo
	$info = require(dirname(__FILE__) . '/../../../jlprotected/config/server-info.php');
	$YiiComponentConfigs = require(dirname(__FILE__) . '/../../../jlprotected/config/' .APPLICATION_ENV. '/_common_components.php');
	$mongoDBConfig = $YiiComponentConfigs['mongodb'];
	
	$pattern = "/upload\/(.*?)\//";
	preg_match($pattern, $uri, $matches);
	
	$m = new Mongo($mongoDBConfig['connectionString'], true); // connect
	$db = $m->selectDB($mongoDBConfig['dbName']);
	
	
	$fs = null;
	switch($matches[1]){
		case "user-photos":
			$fs = new MongoGridFS($db, "zone_files");
		break;
		case "gallery":
			$fs = new MongoGridFS($db, "zone_files");
			break;
	}
	
	$file = $fs->get(new MongoId($name));
	if ($file != null) {
		$fileToWrite = dirname(__FILE__) . "/.." . str_replace("/thumbs/{$w}-{$h}", "", $uri);
		$pos = strrpos($fileToWrite, "/");
		
		$dirToWrite = substr($fileToWrite, 0, $pos);
		
		if (!is_dir($dirToWrite)) {
			mkdir($dirToWrite, 0777, true);
			chmod($dirToWrite, 0777);
		}
		$file->write($fileToWrite);
		
		// thumbnail file
		require_once("my_image.php");
		
		$my_image = new my_image($fileToWrite);
		
		$my_image->fit($w, $h);
		
		$dirPath = dirname($fileToWrite) . "/thumbs/{$w}-{$h}";
		$my_image->copyTo("{$dirPath}/{$filename}");
		$my_image->show();
		
	} else {
		$fileURI = str_replace("/thumbs/{$w}-{$h}", "", $uri);
		$content = file_get_contents("http://{$_SERVER['HTTP_HOST']}" . $fileURI);
		
		$filePath = dirname(__FILE__) . '/../' . $fileURI;
		file_put_contents($filePath, $content);
		
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$format = finfo_file($finfo, $filePath);
		switch ($format) {
			case 'image/jpeg':
			case 'image/pjpeg':
			case 'image/jpg':
				header('Content-type: image/jpeg');
				break;
			case 'image/png':
				header('Content-type: image/png');
				break;
			case 'image/gif':
				header('Content-type: image/gif');
				break;
		}
		
		echo $content;
	}
}