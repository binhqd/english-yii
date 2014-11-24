<?php 
$user = currentUser();

Yii::import('application.modules.users.models.ZoneUserAvatar');

GNAssetHelper::init(array(
'image'		=> 'img',
'css'		=> 'css',
'script'	=> 'js',
));
GNAssetHelper::setBase('myzone_v1');

GNAssetHelper::cssFile('search-activities');
GNAssetHelper::cssFile('topsearch-pagelet-form');
GNAssetHelper::cssFile('viewall-photo');
GNAssetHelper::cssFile('pagelet-stream-post');
GNAssetHelper::cssFile('pagelet-composer-img-att-content');
GNAssetHelper::cssFile('pagelet-composer-photopost-content');
GNAssetHelper::cssFile('add-photo-update');
GNAssetHelper::cssFile('action-button-photo-update');





GNAssetHelper::scriptFile('imagesloaded.pkgd.min', CClientScript::POS_END);
// GNAssetHelper::scriptFile('jquery.wookmark.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('masonry.pkgd.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.infinitescroll.min', CClientScript::POS_END);

GNAssetHelper::scriptFile('zone.album.photo', CClientScript::POS_END);


?>

<?php
$this->renderPartial('//common/user-related', compact('user'));
$this->widget('ext.timeago.JTimeAgo', array(
    'selector' => ' .timeago',
 
));
?>


<div class="wd-container">
<div class="wd-center wd-center-content-layout3">
	<div class="wd-right-content">
		<!-- How You're Connected -->
		<?php $this->widget('application.modules.followings.components.widgets.ZoneFollowingHowConnected', array(
			'object_id' => $user->hexID,
		)); ?>
		<!-- How You're Connected .end -->
		<!-- People’s you may know -->
		<?php $this->widget('application.modules.friends.components.widgets.ZoneFriendsPeopleYouMayKnow'); ?>
		<!-- People’s you may know .end -->
		<!-- People also viewed -->
		<?php $this->renderPartial('application.modules.users.views.elements.people-also-view') ?>
		<!-- People also viewed .end -->
		<!-- YouLook for mobile -->
		<?php $this->renderPartial('application.modules.users.views.elements.youlook-for-mobile') ?>
		<!-- YouLook for mobile .end -->
		
	</div>
	<div class="wd-contain-content">
<!-- header line -->
		<?php
		$this->renderPartial('application.modules.users.views.elements.user-interaction-status', array(
				'user' => $user
			));
			
		
		?>

<!-- header line .end-->
<!-- main content -->
		<div class="wd-main-content" id="custom-view-all-photo">
			
			
			<div class="wd-main-content">
					
					
					<style>
					@media screen and (max-width: 1024px){
						.wd-pagelet-images-wiew{padding-left:25px!important;padding-right:25px!important;}
					}
					
					</style>
					<div class="wd-pagelet-images-wiew" style="opacity:1">
						
						

							
							
							<div class="wd-results-container">
								<div class="wd-empty-results-description">
									<p class="mt35">Hi! You do not have any album right now</p>
									
								</div>
							</div>


						<div class="clear"></div>
						
					</div>
					
				</div>
				
				
			
			
		</div>
<!-- main content .end-->
	</div>
	<div class="clear"></div>
</div>
</div>



<style>
ul.wd-view-all-photo li {width:210px;}
#infscr-loading{
	position:absolute!important;
	bottom:10px!important;
	left:44%!important;
}
.infinite_navigation{display:none}
</style>
