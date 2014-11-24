<!DOCTYPE html>
<html lang="en">
<?php 
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));
?>
<head>
<title>Administrator Login</title>
<meta charset="UTF-8" />
<?php 
GNAssetHelper::cssCollection("css-login-head", "/loginassets/css/", array(
	'bootstrap.min',
	'bootstrap-responsive.min',
	'unicorn.login',
	'style'
), CClientScript::POS_HEAD, "testcss");

GNAssetHelper::jsCollection("js-head", "/unicorn/js/", array(
	'jquery.min'
), CClientScript::POS_HEAD, "js-head");

?>

<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<?php 
// GNAssetHelper::setBase('unicorn');
// GNAssetHelper::setPriority(100);
// GNAssetHelper::scriptFile('jquery', CClientScript::POS_HEAD);

// GNAssetHelper::setBase('loginassets');
// GNAssetHelper::cssFile('bootstrap.min');
// GNAssetHelper::cssFile('bootstrap-responsive.min');
// GNAssetHelper::cssFile('unicorn.login');
// GNAssetHelper::cssFile('style');

// GNAssetHelper::scriptFile('jquery.yiiactiveform');
// Yii::app()->jlbd->register();
//Yii::app()->jlbd->register();
?>
</head>
<body>
	<div id="logo">
		<img src="/loginassets/img/fvws_logo.png" alt="" />
	</div>
	<?php echo $content;?>
</body>
</html>
