<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<title><?php echo @CHtml::encode($this->pageTitle); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<meta property="og:image" content="<?php echo ZoneRouter::createAbsoluteUrl('/'); ?>/myzone_v1/img/front/youlook-logo.png"/>
	<meta property="og:title" content="Youlook - The social network of human-knowledge"/>
	<meta property="og:url" content="http://youlook.net"/>
	<meta property="og:site_name" content="Youlook"/>

	<link href='http://fonts.googleapis.com/css?family=Istok+Web:400,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Annie+Use+Your+Telescope' rel='stylesheet' type='text/css' />
	
	
	<?php
		GNAssetHelper::init(array(
			'image'		=> 'img',
			'css'		=> 'css',
			'script'	=> 'js',
		));
		Yii::app()->clientScript->registerCoreScript('jquery');
		GNAssetHelper::setPriority(100);
		GNAssetHelper::setBase('myzone_v1');
		GNAssetHelper::cssFile('reset');
		GNAssetHelper::cssFile('common');
		GNAssetHelper::cssFile('home-page-layout');
		GNAssetHelper::cssFile('footer');
		GNAssetHelper::cssFile('banner-yl');
		GNAssetHelper::cssFile('gallery-home');
		GNAssetHelper::cssFile('custom.common');
		GNAssetHelper::cssFile('tipsy');
		
		GNAssetHelper::scriptFile('jquery.magnific-popup.min', CClientScript::POS_HEAD);
		GNAssetHelper::scriptFile('jquery.uniform.min', CClientScript::POS_HEAD);
		GNAssetHelper::scriptFile('jquery.placeholder.min', CClientScript::POS_HEAD);
		GNAssetHelper::scriptFile('common-home', CClientScript::POS_HEAD);
		GNAssetHelper::scriptFile('jquery.nicescroll', CClientScript::POS_END);
		GNAssetHelper::scriptFile('jquery.tipsy', CClientScript::POS_HEAD);
		GNAssetHelper::scriptFile('jqautocomplete', CClientScript::POS_END);


	?>
	
	

	<!--[if lte IE 6]>
		<link rel="stylesheet" href="<?php echo baseUrl();?>/css/ie6.css" type="text/css" media="screen"/>
	<![endif]-->
	<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo baseUrl();?>/css/ie7.css" type="text/css" media="screen"/>
	<![endif]-->
	<!--[if IE 8]>
		<link rel="stylesheet" href="<?php echo baseUrl();?>/css/ie8.css" type="text/css" media="screen"/>
	<![endif]-->
	<!--[if IE 9]>
		<link rel="stylesheet" href="<?php echo baseUrl();?>/css/ie9.css" type="text/css" media="screen"/>
	<![endif]-->
	<!--[if lt IE 9]>
		<script src="<?php echo baseUrl();?>/js/css3-mediaqueries.js"></script>
	<![endif]-->
</head>
<body>
<div class="wd-wrapper">
	<div id="wd-header">
		<div class="wd-top-header wd-home-top-header bbor-solid">
			<div class="wd-center">
				<h1 class="wd-ttlogo">
					<a class="wd-logo-4" href="<?php echo GNRouter::createUrl('/landingpage');?>"><img src="<?php echo baseUrl();?>/img/front/youlook-logo.gif" alt="YouLook" /></a>
				</h1>
				
				<div class="wd-header-right">
					<?php
					if(currentUser()->isGuest):
					?>
					
					<?php
						echo CHtml::link('Join YouLook',ZoneRouter::createUrl('/users/registration'),array(
							'class'=>'wd-join-yltn-bt '
						));
						echo CHtml::link('Sign In',ZoneRouter::createUrl('/users/login'),array(
							'class'=>'wd-login-yltn-bt'
						));
					else:
					?>
						<a class="wd-username-cont" href="<?php echo ZoneRouter::createUrl('/profile')?>">
							<span class="wd-username ume"><?php echo currentUser()->displayname?></span>
							<img  size="26-26" src='<?php echo (!currentUser()->isGuest) ? ZoneRouter::CDNUrl("/upload/user-photos/".currentUser()->hexID."/fill/40-40/" . currentUser()->profile->image ) : GNRouter::createUrl('/site/placehold',array('t'=>'26x26-282828-969696')) ;?>' class="wd-userimage me" alt="<?php echo currentUser()->displayname?>" width="26px" height="26px"/>
						</a>

					<?php
					endif;
					?>
				
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<div class="wd-container">
		<?php echo $content;?>
		
	</div>
	<div id="wd-push">&nbsp;</div>
</div>
<!-- footer content -->
	<?php $this->renderPartial('//common/footer');?>
	<?php 
	if(currentUser()->isGuest) :
	GNAssetHelper::scriptFile('common-home', CClientScript::POS_HEAD);
	?>
		<script>
		$().ready(function(e){
			$('.wd-open-popup').magnificPopup({
				tClose: 'Close',closeBtnInside:true
			});
		});
		</script>
		
	<?php
		//$this->widget('widgets.user.ForgotPassword');
	endif;
	?>
<!-- footer content .end-->

<script type="text/javascript"><?php echo @Yii::app()->params['analyticsScript']; ?></script>
</body>
</html>