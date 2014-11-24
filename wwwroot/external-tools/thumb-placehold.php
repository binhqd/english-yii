<?php
extract($_REQUEST);


if(!empty($size)){
	$size = str_replace("/","",$size);
	
	$arrSize = explode("-",$size);
	
	if(count($arrSize) == 2){
		putenv('GDFONTPATH=' . realpath('.'));
		
		$width = $arrSize[0];
		$height = $arrSize[1];
		
		
		if($height>1000) $height = 220;
		$bg_color = '282828';
		$txt_color = '969696';
		// createImgPlaceHold($arrSize[0],$arrSize[1],282828,969696);
		
		
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
}