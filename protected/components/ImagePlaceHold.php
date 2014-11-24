<?php
class ImagePlaceHold 
{
	public $width = "100";
	public $height = "100";
	public $bg_color = "d1d1d1";
	public $txt_color = "ebebeb";
	
	public  function init(){
		// dump(Yii::getPathOfAlias('myzonewebroot'));
		//Define the text to show
		$text = "$this->width x $this->height";

		//Create the image resource 
		$image = ImageCreate($this->width, $this->height);  

		//We are making two colors one for BackGround and one for ForGround
		$this->bg_color = ImageColorAllocate($image, base_convert(substr($this->bg_color, 0, 2), 16, 10), 
											   base_convert(substr($this->bg_color, 2, 2), 16, 10), 
											   base_convert(substr($this->bg_color, 4, 2), 16, 10));

		$this->txt_color = ImageColorAllocate($image,base_convert(substr($this->txt_color, 0, 2), 16, 10), 
											   base_convert(substr($this->txt_color, 2, 2), 16, 10), 
											   base_convert(substr($this->txt_color, 4, 2), 16, 10));

		//Fill the background color 
		ImageFill($image, 0, 0, $this->bg_color); 
		
		//Calculating (Actually astimationg :) ) font size
		$fontsize = ($this->width>$this->height)? ($this->height / 10) : ($this->width / 10) ;
		if($this->width<=32)  $fontsize = 6;
		//Write the text .. with some alignment astimations
		imagettftext($image,$fontsize, 0, ($this->width/2) - ($fontsize * 2.75) + 5, ($this->height/2) + ($fontsize* 0.2)+ 5, $this->txt_color, Yii::getPathOfAlias('myzonewebroot').'/myzone_v1/font/bebas.ttf', $text);
	 
		//Tell the browser what kind of file is come in 
		header("Content-Type: image/png"); 

		//Output the newly created image in png format 
		imagepng($image);
	   
		//Free up resources
		ImageDestroy($image);
	}
   

}
?>