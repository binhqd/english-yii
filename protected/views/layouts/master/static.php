<!DOCTYPE html>
<html>
<head>
<title><?php echo $this->pageTitle?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link href="http://fonts.googleapis.com/css?family=Istok+Web:400,700" rel="stylesheet" type="text/css">
<?php 
GNAssetHelper::init(array(
'image'		=> 'img',
'css'		=> 'css',
'script'	=> 'js',
));
?>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php Yii::app()->jlbd->register(); // Register JLBD Library ?>
<?php GNAssetHelper::setBase('myzone_v1');?>
<?php
	if (empty($this->pageTitle)) 
		$this->pageTitle = "Youlook";
?>

<?php 
	GNAssetHelper::setPriority(100);
	GNAssetHelper::cssFile('reset');
	GNAssetHelper::cssFile('common');

	// ----------------- profile -------------
	GNAssetHelper::cssFile('profile-user-view-layout-v2');
	GNAssetHelper::cssFile('nav');
	GNAssetHelper::cssFile('md-header');
	GNAssetHelper::cssFile('header-search');
	GNAssetHelper::cssFile('setting-header');
	GNAssetHelper::cssFile('jewelcontainer');
	GNAssetHelper::cssFile('bottom-header-v2');
	GNAssetHelper::cssFile('user-interaction-status');
	GNAssetHelper::cssFile('list-1');
	GNAssetHelper::cssFile('for-mobile');
	GNAssetHelper::cssFile('mutual-friends');
	GNAssetHelper::cssFile('information-list');
	GNAssetHelper::cssFile('list-2');
	GNAssetHelper::cssFile('list-3');
	GNAssetHelper::cssFile('list-4');
	GNAssetHelper::cssFile('topleft-person');
	GNAssetHelper::cssFile('gallery-1');
	GNAssetHelper::cssFile('pagelet-stream');
	GNAssetHelper::cssFile('pagelet-stream-post');
	GNAssetHelper::cssFile('action-more-button');
	GNAssetHelper::cssFile('orange-action-more-bt');
	GNAssetHelper::cssFile('search-activities');
	GNAssetHelper::cssFile('topsearch-pagelet-form');
	GNAssetHelper::cssFile('userconnected');
	GNAssetHelper::cssFile('stream-gallery-51');
	GNAssetHelper::cssFile('footer');
	GNAssetHelper::cssFile('jquery.jscrollpane');
	GNAssetHelper::cssFile('jscrollpane-custom');
	GNAssetHelper::cssFile('tipsy');
	GNAssetHelper::cssFile('makerpage-objectnode');
	GNAssetHelper::cssFile('makerpage-objectnode-rightlc');
	GNAssetHelper::cssFile('pagelet-stream-head-storycontent');
	GNAssetHelper::cssFile('pagelet-stream-setting-streamstory');
	GNAssetHelper::cssFile('magnific-popup');
	// GNAssetHelper::cssFile('media-queries');
	GNAssetHelper::cssFile('custom.common');
	GNAssetHelper::cssFile('css3-progress-bar');
	GNAssetHelper::cssFile('pagelet-stream');
	GNAssetHelper::cssFile('pagelet-stream-comment-box');
	GNAssetHelper::cssFile('interests-select');
	GNAssetHelper::cssFile('jquery.alerts');
	GNAssetHelper::cssFile('emoticon');


	GNAssetHelper::cssFile('common-term');
	GNAssetHelper::cssFile('cms');

	// ----------- JS File ---------------------
	GNAssetHelper::scriptFile('jquery.easing.1.3.min', CClientScript::POS_END);
	GNAssetHelper::scriptFile('expanding', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.autosize-min', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.mousewheel.min', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.touchSwipe.min', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.carouFredSel-6.2.0-packed', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.jscrollpane.min', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.cookies.2.2.0.min', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.tipsy', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.alerts', CClientScript::POS_HEAD);
	GNAssetHelper::scriptFile('jquery.magnific-popup.min', CClientScript::POS_HEAD);
	GNAssetHelper::scriptFile('zone.popup', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.nicescroll', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.placeholder.min', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.emoticons', CClientScript::POS_END);
	GNAssetHelper::scriptFile('zone', CClientScript::POS_BEGIN);
	GNAssetHelper::scriptFile('jquery.tmpl.min', CClientScript::POS_BEGIN);
	GNAssetHelper::scriptFile('common', CClientScript::POS_END);

	GNAssetHelper::setBase('myzone');
	GNAssetHelper::scriptFile('myzone', CClientScript::POS_HEAD);
	GNAssetHelper::scriptFile('myzone.user', CClientScript::POS_HEAD);

	$this->widget('ext.timeago.JTimeAgo', array(
		'selector' => ' .timeago',
	));
?>

<!--[if lte IE 6]>
		<link href="/myzone_v1/css/ie6.css" media="screen" rel="stylesheet" type="text/css"/>
	<![endif]-->
<!--[if IE 7]>
		<link href="/myzone_v1/css/ie7.css" media="screen" rel="stylesheet" type="text/css"/>
	<![endif]-->
<!--[if IE 8]>
		<link href="/myzone_v1/css/ie7.css" media="screen" rel="stylesheet" type="text/css"/>
	<![endif]-->
<!--[if IE 9]>
		<link href="/myzone_v1/css/ie9.css" media="screen" rel="stylesheet" type="text/css"/>
	<![endif]-->
<!--[if lt IE 9]>
		<script src="/myzone_v1/js/css3-mediaqueries.js" type="text/javascript""></script>
	<![endif]-->
	
	<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="/myzone_v1/js/jquery.xdr-transport.js"></script>
<![endif]-->
<script>
	var CDNUrl = '<?php echo Yii::app()->params['AWS']['CDN']?>';
	var activities = [];
	var urlAmazone = "<?php echo ZoneRouter::CDNUrl('/');?>";
	
	function loadArticles(activities) {
		if (activities.length > 0) {
			$.post(homeURL + '/articles/api/realtime', {data: activities}, function(res) {
				$("#articleSelector").prepend(res);
				$(".fade-item-article").fadeIn(1000);
			});
			activities = [];
		}
	}
	
</script>
</head>
<body>
	<style>
		.well{padding: 0;margin-bottom: 0;}
		.wd-change-pass-form input{margin-bottom: 0px;}
		.btn-continue{background: #ce4413;padding: 4px 20px !important}
		.errorMessage{padding:0px !important}
	</style>

	<div class="wd-wrapper" id='pageWrapper'>
		<?php $this->renderPartial('//common/top-header-static');?>
		<?php echo $content;?>
		<div id="wd-push">&nbsp;</div>
	</div>
	<?php $this->renderPartial('//common/footer');?>
	<!-- footer content -->
	
	
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
		$this->widget('widgets.user.FormLogin');
		$this->widget('widgets.user.FormResgister');
	else :
		
		// $this->widget('widgets.user.FormChangePassword');
	endif;
	?>
	<!-- Start Script -->
	
	
<?php
GNAssetHelper::setBase('myzone_v1');
GNAssetHelper::cssFile('popup-content');
GNAssetHelper::cssFile('main-form');
GNAssetHelper::cssFile('uniform.default');
GNAssetHelper::cssFile('uniform-default-custom');
GNAssetHelper::scriptFile('jquery.uniform.min', CClientScript::POS_HEAD);
?>
<script language="javascript" src="<?php echo Yii::app()->params['socketIOScript']?>"></script>
<script type="text/javascript"><?php echo @Yii::app()->params['analyticsScript']; ?></script>
</body>
</html>
