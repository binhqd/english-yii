<!DOCTYPE html >
<html>
<head>
<?php 
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));
?>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php GNAssetHelper::setBase('justlook');?>
<?php GNAssetHelper::cssFile('reset');?>
<?php GNAssetHelper::cssFile('common');?>
<?php GNAssetHelper::cssFile('biz-register');?>
<?php GNAssetHelper::cssFile('biz-register-form');?>
<?php GNAssetHelper::cssFile('jl-jui');?>
<?php GNAssetHelper::cssFile('jlbd-style-content');?>
<?php GNAssetHelper::scriptFile('jquery.fancybox-1.3.4.pack', CClientScript::POS_HEAD);?>


<?php 
//GNAssetHelper::scriptFile('jlbd', CClientScript::POS_END)
?>

<?php
//GNAssetHelper::setBase('application.modules.reviews.assets');
//GNAssetHelper::scriptFile('jlbd.rating', CClientScript::POS_END);
?>

<?php
//GNAssetHelper::setBase('application.modules.businesses.assets');
//GNAssetHelper::scriptFile('jlbd.biz', CClientScript::POS_END);
?>

<?php
//GNAssetHelper::setBase('application.modules.user.assets');
//GNAssetHelper::scriptFile('jlbd.user', CClientScript::POS_END);
?>
	
</head>
<body>
<?php echo $content;?>
	<script type="text/javascript"><?php echo @Yii::app()->params['analyticsScript']; ?></script>
</body>
</html>
