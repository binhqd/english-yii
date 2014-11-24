
<?php
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js',
	));
	Yii::app()->clientScript->registerCoreScript('jquery');
	
	GNAssetHelper::setBase('myzone_v1');
	GNAssetHelper::cssFile('popup-content');
	GNAssetHelper::cssFile('uniform.default');
	GNAssetHelper::cssFile('uniform-default-custom');
	
	GNAssetHelper::cssFile('main-form');
	GNAssetHelper::scriptFile('zone', CClientScript::POS_HEAD);
	GNAssetHelper::scriptFile('jquery.nicescroll', CClientScript::POS_END);
	
	
	Yii::app()->jlbd->register(); // Register JLBD Library 

?>

<?php
$this->widget('widgets.user.FormChangePassword',array(
	'isPopup'=>false
));
?>
<script>
$().ready(function(e){
	$("html").niceScroll({styler:"fb",cursorcolor:"#000"});
	jlbd.users_changepass.isPopup = false;
});
</script>