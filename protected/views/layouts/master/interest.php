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
<link href='http://fonts.googleapis.com/css?family=Istok+Web:400,700' rel='stylesheet' type='text/css'>

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
GNAssetHelper::cssFile('profile-user-view-layout');
GNAssetHelper::cssFile('nav');
GNAssetHelper::cssFile('md-header');
GNAssetHelper::cssFile('header-search');
GNAssetHelper::cssFile('setting-header');
GNAssetHelper::cssFile('jewelcontainer');
GNAssetHelper::cssFile('bottom-header');
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
GNAssetHelper::cssFile('media-queries');
GNAssetHelper::cssFile('custom.common');
GNAssetHelper::cssFile('css3-progress-bar');
GNAssetHelper::cssFile('pagelet-stream');
GNAssetHelper::cssFile('pagelet-stream-comment-box');
GNAssetHelper::cssFile('interests-select');
GNAssetHelper::cssFile('jquery.alerts');
GNAssetHelper::cssFile('emoticon');

GNAssetHelper::cssFile('home-page-layout');
GNAssetHelper::cssFile('banner-yl');
GNAssetHelper::cssFile('gallery-home');


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
	$().ready(function(e){
		
	});
	
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

	<div class="wd-wrapper ptloc wd-wapper-term" id='pageWrapper'>
		
		<script type="text/javascript" src="/myzone_v1/js/greennet-autocomplete.js"></script>
		<div id="wd-header">
			<div class="wd-top-header">
				<div class="wd-center">
					
					<h1 class="wd-header-logo">
						<a class="wd-logo-4" href="<?php echo GNRouter::createUrl('/landingpage');?>"><img src="<?php echo baseUrl();?>/img/front/youlook-logo.gif" alt="YouLook" /></a>
					</h1>
					
					<?php if(!currentUser()->isGuest){?>
					<div class="wd-chat-header">
						<a href="javascript:void(0)" class="wd-chaticon-bt">Chat</a>
					</div>
					<div class="wd-setting-header">
						<a href="javascript:void(0)" class="wd-settingicon-top wd_toggle_bt wd-tooltip-hover-north" title="Settings">Setting</a>
						<a class="wd-username-cont" href="<?php echo ZoneRouter::createUrl('/profile')?>">
							<span class="wd-username ume"><?php echo currentUser()->displayname?></span>
							<img  size="26-26" src='<?php echo (!currentUser()->isGuest) ? ZoneRouter::CDNUrl("/upload/user-photos/".currentUser()->hexID."/fill/40-40/" . currentUser()->profile->image ) : GNRouter::createUrl('/site/placehold',array('t'=>'26x26-282828-969696')) ;?>' class="wd-userimage me" alt="<?php echo currentUser()->displayname?>" width="26px" height="26px"/>
						</a>
						<div class="wd-setting-content wd_toggle">
							<ul class="bbor-solid-1">
								<li><a href="javascript:void(0);" class="create-new-topic-handler"><span
										class="wd-icon-16 wd-icon-create-toppic"></span><span
										class="wd-label">Create Topic</span> </a>
								</li>
								<li><a href="#" class=""><span
										class="wd-icon-16 wd-icon-addphotovideo"></span><span
										class="wd-label">Add Photos</span> </a>
								</li>
								<!--<li><a href="#" class=""><span
										class="wd-icon-16 wd-icon-manage-resources"></span><span
										class="wd-label">Manage resources</span> </a>
								</li>-->
							</ul>
							<!--<ul class="bbor-solid-1">
								<li><a href="<?php echo ZoneRouter::createUrl('/profile/edit')?>" class=""><span
										class="wd-icon-16 wd-icon-account-setting"></span><span
										class="wd-label">Account settings</span> </a>
								</li>
								<li><a href="#" class=""><span
										class="wd-icon-16 wd-icon-privacy-setting"></span><span
										class="wd-label">Privacy settings</span> </a>
								</li>
								<li><a href="#" class=""><span class="wd-icon-16 wd-icon-help"></span><span
										class="wd-label">Help</span> </a>
								</li>
							</ul>-->
							<ul>
								<li>
									<a href="<?php echo ZoneRouter::createUrl('/users/changePassword');?>" ><span
										class="wd-icon-16 wd-icon-change-pass"></span><span
										class="wd-label">Change Password</span> </a>
								</li>
								<li>
									<a href="<?php echo ZoneRouter::createUrl('/logout')?>" class=""><span
										class="wd-icon-16 wd-icon-logout"></span><span
										class="wd-label">Logout</span> </a>
								</li>
							</ul>
						</div>
						
					</div>
					

					<div class="wd-jewelcontainer">
						<?php $this->widget('widgets.notification.ZoneTopNotification');?>
					</div>
					<?php
					}else{
					?>
					<div class="wd-setting-header wd-setting-header-act-bt">
						<a href="<?php echo ZoneRouter::createUrl('/users/registration');?>" class="wd-join-yltn-bt">Join YouLook</a>
						<a href="<?php echo ZoneRouter::createUrl('/users/login');?>" class="wd-login-yltn-bt">Sign In</a>
					</div>
					<?php
					}
					?>
					<?php $this->renderPartial('application.views.common.header_search_box'); ?>
					<div class="clear"></div>
				</div>
				
			</div>
		</div>
		
		<?php echo $content;?>
		<!-- footer content -->
		<?php $this->renderPartial('//common/footer');?>
		
		<!-- footer content .end-->
		
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
	</div>
	<!-- Start Script -->
	
	
<?php
GNAssetHelper::setBase('myzone_v1');
GNAssetHelper::cssFile('popup-content');
GNAssetHelper::cssFile('main-form');
GNAssetHelper::cssFile('uniform.default');
GNAssetHelper::cssFile('uniform-default-custom');
GNAssetHelper::scriptFile('jquery.uniform.min', CClientScript::POS_HEAD);
?>
<?php $this->renderPartial('application.modules.zone.views.common.create_topic_popup'); ?>
<script language="javascript" src="<?php echo Yii::app()->params['socketIOScript']?>"></script>
<script type="text/javascript"><?php echo @Yii::app()->params['analyticsScript']; ?></script>
</body>
</html>
