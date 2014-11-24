<?php
extract($_REQUEST);
require(dirname(__FILE__) . '/../../protected/config/constants.php');

$filename = basename("{$name}.{$ext}");

// Get path of original file
$filepath = realpath(dirname(__FILE__) . "/../../" . str_replace("/fill/{$w}-{$h}", "", $uri));
// Get dir path of current file
$dirPath = dirname(__FILE__) . "/.." . dirname($uri);

$imagesize = @getimagesize("{$filepath}");
if(!$imagesize){
	require_once("placehold.php");
	if($h>1000) $h = 220;
	createImgPlaceHold($w,$h,282828,969696);
}

// If orgininal file existed
if ($filepath !== false) {
	// thumbnail file
	require_once("my_image.php");
	
	$my_image = new my_image($filepath);
	
	
	$my_image->fill($w, $h);
	$my_image->copyTo("{$dirPath}/{$filename}");
	$my_image->show();
} else {
	/**
	 * If file not exist, check if it exist in cloud store, mongodb or S3
	 */
	// check in mongo
	$info = require(dirname(__FILE__) . '/../../../jlprotected/config/server-info.php');
	$YiiComponentConfigs = require(dirname(__FILE__) . '/../../../jlprotected/config/' .APPLICATION_ENV. '/_common_components.php');
	$mongoDBConfig = $YiiComponentConfigs['mongodb'];
	
	$pattern = "/upload\/(.*?)\//";
	preg_match($pattern, $uri, $matches);
	
	$m = new Mongo($mongoDBConfig['connectionString']); // connect
	$db = $m->selectDB($mongoDBConfig['dbName']);
	/*
	edit code: thinhpq 	
	date : 28/08/2012
	**/
	
	$fs = null;
	switch($matches[1]){
		case "user-photos":
			$fs = new MongoGridFS($db, "zone_files");
		break;
		case "gallery":
			$fs = new MongoGridFS($db, "zone_files");
			break;
	}
	
	if($fs == null){
		create_image($w,$h,282828,969696);
	}

	$file = $fs->findOne(array(
		'uuid'	=> $name
	));
	
	if ($file != null) {
		$fileToWrite = dirname(__FILE__) . "/.." . str_replace("/fill/{$w}-{$h}", "", $uri);
		
		$pos = strrpos($fileToWrite, "/");
		
		$dirToWrite = substr($fileToWrite, 0, $pos);
		
		if (!is_dir($dirToWrite)) {
			mkdir($dirToWrite, 0777, true);
			chmod($dirToWrite, 0777);
		}
		
		$content = $file->getBytes();
		file_put_contents($fileToWrite, $content);
		//$file->write($fileToWrite);
		
		// thumbnail file
		require_once("my_image.php");
		
		$my_image = new my_image($fileToWrite);
		
		$my_image->fill($w, $h);
		
		$dirPath = dirname($fileToWrite) . "/fill/{$w}-{$h}";
		$my_image->copyTo("{$dirPath}/{$filename}");
		$my_image->show();
		
	} else {
		$fileURI = str_replace("/fill/{$w}-{$h}", "", $uri);
		$content = file_get_contents("http://{$_SERVER['HTTP_HOST']}" . $fileURI);
		
		$dirname = dirname($fileURI);
		
		if (!is_dir(dirname(__FILE__) . "/../{$dirname}")) {
			mkdir(dirname(__FILE__) . "/../{$dirname}", 0755, true);
		}
		
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

function create_image($width, $height, $bg_color, $txt_color )
{
    //Define the text to show
    $text = "$width x $height";

    //Create the image resource 
    $image = ImageCreate($width, $height);  

    //We are making two colors one for BackGround and one for ForGround
	$bg_color = ImageColorAllocate($image, base_convert(substr($bg_color, 0, 2), 16, 10), 
										   base_convert(substr($bg_color, 2, 2), 16, 10), 
										   base_convert(substr($bg_color, 4, 2), 16, 10));

	$txt_color = ImageColorAllocate($image,base_convert(substr($txt_color, 0, 2), 16, 10), 
										   base_convert(substr($txt_color, 2, 2), 16, 10), 
										   base_convert(substr($txt_color, 4, 2), 16, 10));

    //Fill the background color 
    ImageFill($image, 0, 0, $bg_color); 
    
	//Calculating (Actually astimationg :) ) font size
	$fontsize = ($width>$height)? ($height / 8) : ($width / 8) ;
    
	//Write the text .. with some alignment astimations
	imagettftext($image,$fontsize, 0, ($width/2) - ($fontsize * 2.75) + 8, ($height/2) + ($fontsize* 0.25) + 8, $txt_color, 'bebas.ttf', $text);
 
    //Tell the browser what kind of file is come in 
    header("Content-Type: image/png"); 

    //Output the newly created image in png format 
    imagepng($image);
   
    //Free up resources
    ImageDestroy($image);
}
