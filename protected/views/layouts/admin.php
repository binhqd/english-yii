<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php 
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js'
	));
	?>
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css');?>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>JL - <?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php GNAssetHelper::setBase('justlook');?>
	<?php GNAssetHelper::setPriority(100);?>	
	<?php GNAssetHelper::cssFile('reset');?>
	<?php //GNAssetHelper::cssFile('common');?>
	<?php GNAssetHelper::cssFile('admin-manage/common');?>
	<?php GNAssetHelper::cssFile('admin-manage/fix-css-admin');?>
	<?php GNAssetHelper::cssFile('admin-manage/left-menu');?>
	<?php GNAssetHelper::cssFile('admin-manage/grid');?>
	<?php GNAssetHelper::cssFile('bt-big-2');?>
	<?php GNAssetHelper::cssFile('pagination-yii');?>

	<?php GNAssetHelper::cssFile('jquery.rating');?>
	<?php GNAssetHelper::scriptFile('jquery.rating')?>
	<?php GNAssetHelper::scriptFile('xii.thumbnailer')?>
	
	<?php GNAssetHelper::scriptFile('jquery.advancedAjax')?>
	
	<?php GNAssetHelper::scriptFile('jlbd', CClientScript::POS_END)?>
	<?php GNAssetHelper::scriptFile('jlbd.dialog', CClientScript::POS_END)?>
	<?php GNAssetHelper::scriptFile('jlbd.message', CClientScript::POS_END)?>
	<?php GNAssetHelper::cssFile('jlbd.message');?>


		
	<?php
	GNAssetHelper::setBase('application.modules.reviews.assets');
	GNAssetHelper::scriptFile('jlbd.rating', CClientScript::POS_END);
	?>
	
	<?php
	GNAssetHelper::setBase('application.modules.businesses.assets');
	GNAssetHelper::scriptFile('jlbd.biz', CClientScript::POS_END);
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
		<style type="text/css" media="all">@import "css/ie6.css";</style>
	<![endif]-->
	<!--[if IE 7]>
		<style type="text/css" media="all">@import "css/ie7.css";</style>
	<![endif]-->
	<!--[if IE 8]>
		<style type="text/css" media="all">@import "css/ie8.css";</style>
	<![endif]-->
	<!--[if IE 9]>
		<style type="text/css" media="all">@import "css/ie9.css";</style>
	<![endif]-->
</head>
<body>
<div id="Notification"></div>
<?php $this->widget('ext.jnotify.JNotify', array(
	'notificationId' => 'Notification',
	'notificationHSpace' => false,	
	'notificationVSpace' => '20px',
	'notificationWidth' => 'auto',
	'notificationShowAt' => 'topRight',
	'notificationCss' => array(
		'position'=>'fixed',
		'margin-top'=>false, // will be set by init()
		'right'=>false, // will be set by init()
		'width'=>'100%',
		'z-index'=>'9999',
	)
	//'notificationShowAt'=>'bottomLeft',
	//'notificationAppendType'=>'prepend',
)); ?>
	<div id="wd-head-container">
		<div id="wd-top-head-nav">
			<div class="wd-center">
				<ul class="wd-top-head-left">
					<li class="wd-none">You are in <a href="#" class="wd-drop-down wd-location">Sydney</a></li>
					<li class="wd-bro-buz"><a href="<?php echo JLRouter::createAbsoluteUrl('/admin_manage/registered/awaiting');?>">Manage registed business</a></li>
					<li class="wd-review"><a href="<?php echo JLRouter::createAbsoluteUrl('/admin_manage/claimed/emailedcompany/type');?>">Manage claimed business</a></li>
					<li class="wd-list"><a href="<?php echo JLRouter::createAbsoluteUrl('/admin_manage/attribute/');?>">Manage attributes</a></li>
				</ul>
				<div class="wd-top-head-right">
					<p>Welcome <a href="#"><?php echo Yii::app()->user->name;?></a>, <a href="<?php echo JLRouter::createAbsoluteUrl('/user/logout'); ?>" class="wd-drop-down">Logout</a></p>
				</div>
			</div>
		</div>	
	</div>
	<div id="wd-content-container" >
		<div class="wd-center">
			<?php echo $content;?>
		</div>
	</div>
	<div id="wd-extras">
		<div class="wd-center">
			<div class="wd-block">
				<h2>About</h2>
				<ul>
					<li><a href="#">About Justlook</a></li>
					<li><a href="#">Justlook Blog</a></li>
					<li><a href="#">Press</a></li>
					<li><a href="#">Terms of Service</a></li>
					<li><a href="#">Privacy Policy</a></li>
				</ul>
			</div>
			<div class="wd-block">
				<h2>Help</h2>
				<ul>
					<li><a href="#">FAQ</a></li>
					<li><a href="#">Content Guidelines</a></li>
					<li><a href="#">Contact Yelp</a></li>
					<li><a href="#">Business Support Center</a></li>
					<li><a href="#">Developers</a></li>
				</ul>
			</div>
			<div class="wd-block">
				<h2>More</h2>
				<ul>
					<li><a href="#">Careers</a></li>
					<li><a href="#">Justlook Mobile</a></li>
					<li><a href="#">The Weekly Justlook</a></li>
					<li><a href="#">RSS</a></li>
					<li><a href="#">Top Searches</a></li>
				</ul>
			</div>
			<div class="wd-block">
				<h2>My page</h2>
				<ul>
					<li><a href="#">About me</a></li>
					<li><a href="#">Settings</a></li>
					<li><a href="<?php echo JLRouter::createUrl('/user/logout')?>">Log out</a></li>
				</ul>
			</div>
		</div>
	</div>	
	<div id="wd-footer-container">
		<div class="wd-center">
			<p>Copyright © 2011 Justlook.com.au ™. All rights reserved. Justlook has offices in:</p>
			<ul id="wd-footer-menu" class="wd-font-11">
				<li><a href="#">amsterdam</a></li>
				<li>-</li>
				<li><a href="#">athens</a></li>
				<li>-</li>
				<li><a href="#">barcelona</a></li>
				<li>-</li>
				<li><a href="#">berlin</a></li>
				<li>-</li>
				<li><a href="#">buenos aires</a></li>
				<li>-</li>
				<li><a href="#">cambridge</a> </li>
				<li>-</li>
				<li><a href="#">cape town</a></li>
				<li>-</li>
				<li><a href="#">grand rapids</a></li>
				<li>-</li>
				<li><a href="#">istanbul</a></li>
				<li>-</li>
				<li><a href="#">london</a></li>
			</ul>
			<a class="wd-move-top" href="#">top</a>
		</div>
	</div>
	<!-- Start Script -->
		<!--[if lt IE 7]>
			<script src="js/IE7.js"></script>
		<![endif]-->
	<!-- End Script -->
</body>
</html>
