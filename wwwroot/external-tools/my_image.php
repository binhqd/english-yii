<?php
/* *******************************************
 * This file is create by Quan Duc Binh
 * Email: Hastilydoll219@yahoo.com
 * Mobile: +84 905 089661
 * Date Created: {1}
 * Date Modified: 2007-01-16 7:56:41
 * ******************************************/
?>
<?php
/*
Functions:
color getHexColor(source img,string hexColor) 
color getRBGColor(source img,int red,int green,int blue) 
color getRBGColor(source img,hex red,hex green,hex blue) 
void mergeHorizontal(string[] paths) //Merge array of image into an image with horizontal order
void mergeVertical(string[] paths) //Merge array of image into an image with vertical order
void rotate(float angle) //rotate image ||note: bounded GD only
void crop(int x,int y,int width,int height) //crop an image
void copyTo(string newPath) //Copy image into new path
void thumbnail(int maxwidth,int maxheight,int percent) //thumbnail an image with limit width<=maxwidth,height<=maxheight and percent
void show() //show an image into browser
void scale(int newWidth,int newHeight)

Example:
$info['file'] = "buom.jpg";
		$img = $func->getObject("MyImage",$info);
		
		//$img->thumbnail(180,0,0);
		switch($vars->input['action']) {
			default:
				$img->show();
			break;
			case "a":
				$img->crop(0,0,200,200);
				$img->show();
			break;
		}
		$img->destroy();
*/
class my_image {
	private $_property = array(
			"ErrorMessage"		  =>	"",
			"Error"				 =>	false,
			"Format"				=>	"",
			"file"				  =>	"",
			"MaxWidth"			  =>	"0",
			"MaxHeight"			 =>	"0",
			"Percent"			   =>	"0",
			"MyImage"			   =>	"",
			"NewImage"			  =>	""
	);
	
	private $original;
	public function __get($key)
	{
		if (isset($this->_property[$key]))
		{
			return $this->_property[$key];
		} 
		else
		{
			throw new Exception("Property ".$key." doesn't exists");
		}
	}
	
	//Overload function _set to create properties
	public function __set($key, $value)
	{
		if (!isset($value))
		{
			throw new Exception("null can't be passed to property of MyImage object");
		}
		if (isset($this->_property[$key])) 
		{
			$this->_property[$key] = $value;
		} 
		else 
		{
			throw new Exception("Property ".$key." doesn't exists");
		}
	}
	
	public function __construct($imagePath) {
		if (!preg_match("/^http\:/", $imagePath)) {
			if (!file_exists($imagePath)) {
				throw new Exception("File doesn't exists");
			} else if (!is_readable($imagePath)) {
				throw new Exception("File is not readable");
			}
			//if ($this->error = true) return;
				
			
			$imagePath = realpath($imagePath);
		}
		
		$info = pathinfo($imagePath);
		$sizes = getimagesize($imagePath);
		
		$format = $sizes['mime'];
		//$format = mime_content_type ($imagePath);
		switch ($format) {
			case "image/gif":
				$this->_property["Format"] = "GIF";
				break;
			case "image/jpeg":
			case "image/pjpeg":
			case "image/jpg":
				$this->_property["Format"] = "JPG";
				break;
			case "inode/x-empty":
			case "image/png":
				$this->_property["Format"] = "PNG";
				break;
			default:
				$this->_property["ErrorMessage"] = "Unknown file format";
				$this->_property["Error"]  = true;
				break;
		}
			
		
		$resource = $this->createImage($imagePath, $this->_property["Format"]);
		
		
		$this->original = array(
			'width'		=> $sizes[0],
			'height'	=> $sizes[1],
			'ratio'		=> $sizes[0] / $sizes[1],
			'filename'	=> basename($imagePath),
			'info'		=> $info,
			'resource'	=> $resource
		);
		
		$this->file = $imagePath;
		$this->_property["MyImage"] = $resource;
	}
	
	function thumbnail($max_width=0,$max_height=0,$percent=0) {
		if ($max_width == 0 && $max_height == 0 && $percent == 0) {
			$percent = 100;
		}
		
		$this->_property["MaxWidth"]	= $max_width;
		$this->_property["MaxHeight"]	= $max_height;
		$this->_property["Percent"]	= $percent;
	
		//$size	  = GetImageSize($this->file);
		$new_size  = $this->calc_image_size($size[0], $size[1]);
		#
		# Good idea from Mariano Cano Prez
		# Requires GD 2.0.1 (PHP >= 4.0.6)
		#
		if (function_exists("ImageCreateTrueColor")) {
			$new_image = ImageCreateTrueColor($new_size[0], $new_size[1]);
		}
		else {
			$new_image = ImageCreate($new_size[0], $new_size[1]);
		}
	
		imagecopyresampled($new_image, $this->_property["MyImage"], 0, 0, 0, 0, $new_size[0], $new_size[1], $size[0], $size[1]);
		$this->_property["MyImage"] = $new_image;
	}
	
	function fit($w, $h) {
		$ratio = $w / $h;
		
		if ($this->original['width'] <= $w && $this->original['height'] <= $h) {
			return;
		}
		if ($ratio < $this->original['ratio']) {
			$width = (int) $w;
			$height = (int) ($width / $this->original['ratio']);
		} else {
			$height = (int) $h;
			$width = (int) ($height * $this->original['ratio']);
		}
		
		if (function_exists("ImageCreateTrueColor")) {
			$newImage = ImageCreateTrueColor($width, $height);
		} else {
			$newImage = ImageCreate($width, $height);
		}
		
		$offset = array(
			'x' => (int)((($width - $w) / 2) * ($this->original['width'] / $width)),
			'y' => (int)((($height - $h) / 2)  * ($this->original['height'] / $height)),
		);
		
		imagecopyresampled($newImage, $this->original["resource"], 0, 0, 0, 0, $width, $height, $this->original['width'], $this->original['height']);
		$this->_property["MyImage"] = $newImage;
		
	}
	
	public function fill($w, $h) {
		$ratio = $w / $h;
		
		if ($ratio < $this->original['ratio']) {
			$height = (int) $h;
			$width = (int) ($height * $this->original['ratio']);
		} else {
			$width = (int) $w;
			$height = (int) ($width / $this->original['ratio']);
		}
		
		if (function_exists("ImageCreateTrueColor")) {
			$newImage = ImageCreateTrueColor($w, $h);
		} else {
			$newImage = ImageCreate($w, $h);
		}
		
		$offset = array(
			'x' => (int)((($width - $w) / 2) * ($this->original['width'] / $width)),
			'y' => (int)((($height - $h) / 2)  * ($this->original['height'] / $height)),
		);
		
		imagecopyresampled($newImage, $this->original["resource"], 0, 0, $offset['x'], $offset['y'], $width, $height, $this->original['width'], $this->original['height']);
		$this->_property["MyImage"] = $newImage;
		
	}
	private function createImage($path, $format="") {
		$image = null;
		switch ($format) {
			case "GIF":
				$image = ImageCreateFromGif($path);
				/*$trans = imagecolorallocate($image, 255, 255, 255);
				imagecolortransparent($image,$trans);*/
				break;
			case "JPG":
				$image = ImageCreateFromJpeg($path);
				break;
			case "PNG":
				$image = ImageCreateFromPng($path);
				break;
		}
		return $image;
	}
	function calc_width($width, $height) {
		$new_width  = $this->_property["MaxWidth"];
		$new_wp	 = (100 * $new_width) / $width;
		$new_height = ($height * $new_wp) / 100;
		return array($new_width, $new_height);
	}

	function calc_height($width, $height) {
		$new_height = $this->_property["MaxHeight"];
		$new_hp	 = (100 * $new_height) / $height;
		$new_width  = ($width * $new_hp) / 100;
		return array($new_width, $new_height);
	}

	function calc_percent($width, $height) {
		$new_width  = ($width * $this->_property["Percent"]) / 100;
		$new_height = ($height * $this->_property["Percent"]) / 100;
		return array($new_width, $new_height);
	}

	function return_value($array) {
		$array[0] = intval($array[0]);
		$array[1] = intval($array[1]);
		return $array;
	}

	function calc_image_size($width, $height) {
		$new_size = array($width, $height);

		if ($this->_property["MaxWidth"] > 0) {
			$new_size = $this->calc_width($width, $height);
			if ($this->_property["MaxHeight"] > 0) {
				if ($new_size[1] > $this->_property["MaxHeight"]) {
					$new_size = $this->calc_height($new_size[0], $new_size[1]);
				}
			}
			return $this->return_value($new_size);
		}
		if ($this->_property["MaxHeight"] > 0) {
			$new_size = $this->calc_height($width, $height);
			return $this->return_value($new_size);
		}
		if ($this->_property["Percent"] > 0) {
			$new_size = $this->calc_percent($width, $height);
			return $this->return_value($new_size);
		}
	}

	function show_error_image() {
		header("Content-type: image/png");
		$err_img   = ImageCreate(220, 25);
		$bg_color  = ImageColorAllocate($err_img, 0, 0, 0);
		$fg_color1 = ImageColorAllocate($err_img, 255, 255, 255);
		$fg_color2 = ImageColorAllocate($err_img, 255, 0, 0);
		ImageString($err_img, 3, 6, 6, "ERROR:", $fg_color2);
		ImageString($err_img, 3, 55, 6, $this->_property["ErrorMessage"], $fg_color1);
		ImagePng($err_img);
		ImageDestroy($err_img);
	}
	
	
	//Bussiness method
	//Rotate
	function getRBGColor($src,$red,$green,$blue) {
		return imagecolorallocate($src, $red,$green,$blue);
	}
	function getHexColor($src,$hex) {
		$red = substr($hex,1,2);
		$green = substr($hex,3,2);
		$blue = substr($hex,5,2);
		return imagecolorallocate($src, hexdec("0x".$red), hexdec("0x".$green), hexdec("0x".$blue));
		//echo "$red - $green - $blue";
		//return imagecolorallocate($src, 0x00, 0x00, 0xFF);
	}
	function test() {
		$mycolor = $this->getHexColor($this->MyImage,"#FF0000");
		imagefilledrectangle ($this->_property["MyImage"],10,10,200,200,$mycolor);
	}
	
	function mergeHorizontal($paths) {
		$cnt=0;
		$old_width = 0;
		while (list($k,$v) = each($paths)) {
			$size = GetImageSize($v);
			$image[$k] = $this->createImage($v);
			
			if ($cnt > 0) {
				$this->crop(0,0,$old_width+$size[0],$old_height);
				imagecopymerge($this->_property["MyImage"],$image[$k],$old_width,0,0,0,$size[0],$size[1],100);
			}
			else
				$this->_property["MyImage"] = $image[$k];

			$old_width = $old_width+$size[0];
			$old_height = $size[1];
			$cnt++;
		}
	}
	function mergeVertical($paths) {
		$cnt=0;
		$old_height = 0;
		while (list($k,$v) = each($paths)) {
			$size = GetImageSize($v);
			$image[$k] = $this->createImage($v);
			
			if ($cnt > 0) {
				$this->crop(0,0,$old_width,$old_height+$size[1]);
				imagecopymerge($this->_property["MyImage"],$image[$k],0,$old_height,0,0,$size[0],$size[1],100);
			}
			else
				$this->_property["MyImage"] = $image[$k];

			$old_width = $size[0];
			$old_height = $old_height+$size[1];
			$cnt++;
		}
	}
	function rotate($angle) {
		$mycolor = $this->getHexColor($this->_property["MyImage"],"#FF0000");
		$im = ImageCreateTrueColor(500, 500);
		imagerotate($this->_property["MyImage"],$angle,$mycolor);
	}
	
	function scale($newWidth,$newHeight) {
		
		if ($this->_property["Error"]) {
			$this->show_error_image();
			return;
		}
		
		
		$size	  = GetImageSize($this->file);
		#
		# Good idea from Mariano Cano Prez
		# Requires GD 2.0.1 (PHP >= 4.0.6)
		#
		if (function_exists("ImageCreateTrueColor")) {
			$new_image = ImageCreateTrueColor($newWidth,$newHeight);
		} else {
			$new_image = ImageCreate($newWidth,$newHeight);
		}

		
		imagecopyresampled($new_image, $this->_property["MyImage"], 0, 0, 0, 0, $newWidth,$newHeight, $size[0], $size[1]);
		$this->_property["MyImage"] = $new_image;
	}
	function crop($x,$y,$width,$height) {
		if ($this->_property["Error"]) {
			$this->show_error_image();
			return;
		}
		if (function_exists("ImageCreateTrueColor")) {
			$new_image = ImageCreateTrueColor($width, $height);
		} else {
			$new_image = ImageCreate($width, $height);
		}
		ImageCopy($new_image, $this->_property["MyImage"], 0, 0, $x, $y, $width, $height);
		$this->_property["MyImage"] = $new_image;
	}
	function show($showHeader = true) {
		switch ($this->_property["Format"]) {
			case "GIF":
			
			if ($showHeader) header("Content-type: image/jpeg");
			ImageJpeg($this->_property["MyImage"], null, 100);
			
			break;
			case "JPG":
			if ($showHeader) header("Content-type: image/jpeg");
			ImageJpeg($this->_property["MyImage"], null, 100);
			
			break;
			case "PNG":
			if ($showHeader) header("Content-type: image/png");
			ImagePng($this->_property["MyImage"], null);
			
			break;
		}
		return;
	}
	function destroy() {
		ImageDestroy($this->_property["MyImage"]);
	}
	function copyTo($path) {

		$dir = dirname($path);
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}
		
		$format = strtoupper($this->_property["Format"]);
		
		switch ($format) {
			case "GIF":
				if (function_exists("ImageGif")) {
					ImageGif($this->_property["MyImage"], $path);
				} elseif (function_exists("ImageJpeg")) {
					ImageJpeg($this->_property["MyImage"],$path, 100);
				} elseif (function_exists("ImagePng")) {
					imagepng($this->_property["MyImage"],$path);
				} elseif (function_exists("imagewbmp")) {
					imagewbmp($this->_property["MyImage"],$path);
				} else {
				   die("No image support in this PHP server");
				}
			
			case "JPG":
				ImageJpeg($this->_property["MyImage"], $path, 100);
				break;
			case "PNG":			
				ImagePng($this->_property["MyImage"], $path);
				break;
		}
		return;
	}
}
?>