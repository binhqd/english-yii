<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>Youlook - <?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php 
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js',
	));
	?>
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<?php 
	GNAssetHelper::setBase('justlook');
	GNAssetHelper::setPriority(100);
// 	GNAssetHelper::cssFile('reset-pattern');
	GNAssetHelper::cssFile('reset');
	GNAssetHelper::cssFile('fonts/UTMAlterGothicRegular');
	GNAssetHelper::cssFile('common');
	GNAssetHelper::cssFile('common-more');
	GNAssetHelper::cssFile('top-menu');
	GNAssetHelper::cssFile('top-menu-more');
	GNAssetHelper::cssFile('your-stat');
	GNAssetHelper::cssFile('jquery.fancybox-1.3.4');
	GNAssetHelper::cssFile('tooltip.ie.fix');
	GNAssetHelper::cssFile('bt-wrapper-tooltip');
	GNAssetHelper::cssFile('breadcrumb');
	GNAssetHelper::cssFile('validationEngine.jquery');
	GNAssetHelper::cssFile('bt-big-2');
	GNAssetHelper::cssFile('jquery.rating');
	GNAssetHelper::cssFile('jl-jui');
	GNAssetHelper::cssFile('popup-upload-photo');
	GNAssetHelper::cssFile('pagination-yii');
	
	GNAssetHelper::scriptFile('jquery.bt', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.rating', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.fancybox-1.3.4.pack', CClientScript::POS_END);
	
	GNAssetHelper::scriptFile('jquery.advancedAjax');
	GNAssetHelper::scriptFile('jquery.scrollIntoView');
	?>
	
	<?php 
	GNAssetHelper::cssFile('jlbd.message');
	GNAssetHelper::cssFile('jlbd.dialog');
	GNAssetHelper::cssFile('jlbd.notify');
	
	GNAssetHelper::scriptFile('jlbd', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jlbd.message', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jlbd.dialog', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jlbd.notify', CClientScript::POS_END);
	?>
	
	<?php GNAssetHelper::setPriority(99);?>
	<?php
	GNAssetHelper::setBase('application.modules.reviews.assets');
	GNAssetHelper::scriptFile('jlbd.rating', CClientScript::POS_END);
	?>
	
	<?php
	GNAssetHelper::setBase('application.modules.user.assets');
	GNAssetHelper::scriptFile('jlbd.user', CClientScript::POS_END);
	?>
	
	<?php
	GNAssetHelper::setBase('widgets.pagination.assets');
	GNAssetHelper::scriptFile('jlbd.linkpaper', CClientScript::POS_END);
	?>
	
	<!--[if lte IE 6]>
		<style type="text/css" media="all">@import "<?php echo Yii::app()->baseUrl;?>/justlook/css/ie6.css";</style>
	<![endif]-->
	<!--[if IE 7]>
		<style type="text/css" media="all">@import "<?php echo Yii::app()->baseUrl;?>/justlook/css/ie7.css";</style>
	<![endif]-->
	<!--[if IE 8]>
		<style type="text/css" media="all">@import "<?php echo Yii::app()->baseUrl;?>/justlook/css/ie8.css";</style>
	<![endif]-->
	<!--[if IE 9]>
		<style type="text/css" media="all">@import "<?php echo Yii::app()->baseUrl;?>/justlook/css/ie9.css";</style>
	<![endif]-->
</head>
<body>
	<div id="wd-head-container">
		
	</div>
	<div id="wd-content-container" >
		<div class="wd-center">
			<?php echo $content;?>
		</div>
	</div>
	<?php $this->renderPartial('//common/footer')?>
	
	<!-- Start Script -->
		<!--[if lt IE 7]>
			<script src="<?php echo JLRouter::createUrl('/')?>js/IE7.js"></script>
		<![endif]-->
	<!-- End Script -->
	<!--[if IE]><script src="<?php echo JLRouter::createUrl('/')?>js/excanvas.compiled.js" type="text/javascript" charset="utf-8"></script><![endif]-->
	<script type="text/javascript"><?php echo @Yii::app()->params['analyticsScript']; ?></script>
</body>
</html>
