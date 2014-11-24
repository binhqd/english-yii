<?php
	GNAssetHelper::setBase('myzone_v1');
	GNAssetHelper::cssFile('feedback');
	GNAssetHelper::cssFile('ysharer');
	GNAssetHelper::scriptFile('ysharer', CClientScript::POS_END);
	
	
	GNAssetHelper::scriptFile('jquery.magnific-popup.min', CClientScript::POS_HEAD);
	GNAssetHelper::scriptFile('common-feedback', CClientScript::POS_HEAD);
	GNAssetHelper::scriptFile('youlook.common', CClientScript::POS_END);

	$this->widget('application.components.widgets.feedback.Feedback');
	$this->widget('application.components.widgets.reports.ReportConcern');
?>

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
				<a class="wd-username-cont" href="<?php echo ZoneRouter::createUrl('/profile')?>" id="oImage-username-cont">
					<span class="wd-username ume"><?php echo currentUser()->displayname?></span>
					<img  
						size="26-26"
						src='<?php echo (!currentUser()->isGuest) ? ZoneRouter::CDNUrl("/upload/user-photos/".currentUser()->hexID."/fill/40-40/" . currentUser()->profile->image."?album_id=".currentUser()->hexID ) : GNRouter::createUrl('/site/placehold',array('t'=>'26x26-282828-969696')) ;?>' 
						class="wd-userimage me userAvatar" 
						alt="<?php echo currentUser()->displayname?>" 
						width="26" height="26"
						data-bind="{attributes: {src : CDNUrl + '/upload/user-photos/' + user.id + '/fill/26-26/' + user.profile.image + '?album_id=' + user.id}}"
					/>
				</a>
				<div class="wd-setting-content wd_toggle">
					<ul class="bbor-solid-1">
						<li><a href="javascript:void(0);" class="create-new-topic-handler"><span
							class="wd-icon-16 wd-icon-create-toppic"></span><span
							class="wd-label">Create Topic</span> </a>
						</li>
						<li><a href="<?php echo ZoneRouter::createUrl("/user/".currentUser()->username."?tab=photos&action=albums")?>" class=""><span
							class="wd-icon-16 wd-icon-addphotovideo"></span><span
							class="wd-label">Add Photos</span> </a>
						</li>
						<!--<li><a href="#" class=""><span
								class="wd-icon-16 wd-icon-manage-resources"></span><span
								class="wd-label">Manage resources</span> </a>
						</li>-->
					</ul>
					<!--<ul class="bbor-solid-1">
						<li><a href="<?php //echo ZoneRouter::createUrl('/profile/edit')?>" class=""><span
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
					<?php $modelLinkedAccount = ZoneLinkedAccount::isCreatePassword(currentUser()->id); ?>
					<ul>
						<li>
							<a href="#wd-change-password-popup" id="change-user-password" ><span
								class="wd-icon-16 wd-icon-change-pass"></span>
								<span class="wd-label">
									<?php if(!empty($modelLinkedAccount) && $modelLinkedAccount->has_created_password=='0') : ?>
										Create
									<?php else: ?>
										Change
									<?php endif; ?> 
										Password
								</span> 
							</a>
						</li>
						<li>
							<a href="<?php echo ZoneRouter::createUrl('/logout')?>" class=""><span
								class="wd-icon-16 wd-icon-logout"></span><span
								class="wd-label">Logout</span> </a>
						</li>
					</ul>
				</div>
				
			</div>

			<!--div class="wd-md-menu-link-header-home">
				<a class="wd-home-menu-link" href="#"><span class="wd-icon-21"></span>home</a>
			</div-->
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
	
	<div class="wd-md-header">
		<div class="wd-center menu-category">
			<?php 
			if(!empty($layout)){
				
				$this->widget('widgets.common.MenuCategory',array(
					'layout'=>$layout
				));
			}else
				$this->widget('widgets.common.MenuCategory');
			?>
			
		</div>
	</div>
</div>

<div id="wd-change-password-popup" class="wd-container-popup wd-change-password" style="display: none;">
	<div class="wd-popup-content">
		<h2 class="wd-tt-pp-5">
			<span class="wd-tt-st">
				<?php if(!empty($modelLinkedAccount) && $modelLinkedAccount->has_created_password=='0') : ?>
					Create
				<?php else: ?>
					Change
				<?php endif; ?> 
					password
			</span>
		</h2>
		<div class="wd-form-content">
			<?php
				$this->widget('widgets.user.FormChangePassword',array(
					'isPopup'=>false
				));
			?>
		</div>
	</div>
</div>
<?php
	GNAssetHelper::setBase('myzone_v1');
	GNAssetHelper::cssFile('magnific-popup');
	$this->renderPartial('application.modules.zone.views.common.create_topic_popup');
	/* new jquery ui*/
	GNAssetHelper::setBase('myzone');
	GNAssetHelper::scriptFile('jquery-ui', CClientScript::POS_HEAD);
?>
<script>
	var urlYoutubeDetail = '<?php echo GNRouter::createUrl("/video/detail?yid=");?>';
</script>
