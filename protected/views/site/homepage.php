<!DOCTYPE html>
<html>
<head>
	<?php 
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js',
	));
	?>
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<?php GNAssetHelper::setBase('new-homepage');?>
	<title>Youlook - Homepage</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	
	<!-- Start Script -->
	
	<!--[if IE 9]>
		<link rel="stylesheet" href="<?php echo ZoneRouter::createUrl('/new-homepage/');?>/css/home-ie9.css" type="text/css" media="screen"/>
	<![endif]-->
	<!--[if lt IE 9]>
		<script src="<?php echo ZoneRouter::createUrl('/new-homepage/');?>/js/css3-mediaqueries.js"></script>
	<![endif]-->
</head>
<body>
<div class="wd-wrapper-home">
	<div class="wd-home-container">
		<div class="wd-center">
			
		</div>
	</div>
</div>
<div id="wd-home-footer" style="margin-top: 20px !important;">
	<div class="wd-center"> 
		<ul class="wd-footer-menu">
			<li>
				<span class="wd-text">YouLook Net, Inc. &copy; 2013. </span>
				<a href="#" class="wd-active">English (US)</a>
				<!--
				<span> - </span>
				<a href="#">Spanish</a>
				<span> - </span>
				<a href="#">Vietnamese</a>
				-->
			</li>
			<li><a href="<?php echo ZoneRouter::createURL('/cms/about');?>">About</a></li>
			<li><a href="<?php echo ZoneRouter::createURL('/cms/career');?>">Careers</a></li>
			<li><a href="<?php echo ZoneRouter::createURL('/cms/terms');?>">Terms</a></li>
			<li><a href="<?php echo ZoneRouter::createURL('/cms/privacy');?>">Privacy</a></li>
		</ul>
	</div>
</div>
	
</body>
</html>