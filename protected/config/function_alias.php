<?php
function baseUrl(){
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js',
	));
	
	return GNAssetHelper::setBase('myzone_v1');
}
function urlProfile($user = null){
	if(empty($user)) return "#";
	else return GNRouter::createUrl('#');
}


?>