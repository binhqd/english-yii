<?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js',
));
Yii::app()->jlbd->register(); // Register JLBD Library 

GNAssetHelper::setBase('myzone_v1');

GNAssetHelper::setPriority(100);
GNAssetHelper::cssFile('reset');
GNAssetHelper::cssFile('common');

//GNAssetHelper::scriptFile('jquery.magnific-popup.min', CClientScript::POS_HEAD);

?>
<?php echo $content;?>