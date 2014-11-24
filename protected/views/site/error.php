<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
$user = currentUser();

$this->renderPartial('//common/user-related', compact('user'));

GNAssetHelper::init(array(
'image'		=> 'img',
'css'		=> 'css',
'script'	=> 'js',
));
GNAssetHelper::setBase('myzone_v1');

GNAssetHelper::cssFile('viewall-photo');
?>
<div class="wd-container">
		<div class="wd-center wd-center-content-layout3">
			<div class="wd-right-content">
<!-- How You're Connected -->
				<!-- How You're Connected -->
				<?php 
				$this->widget('application.modules.followings.components.widgets.ZoneFollowingHowConnected', array(
					'object_id' => $user->hexID,
				));
				?>
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
<!-- YouLook for mobile .end -->
			</div>
			<div class="wd-contain-content wd-contain-content-mr">
<!-- header line -->
				<?php $this->renderPartial('application.modules.users.views.elements.user-interaction-status', array(
				'user' => $user,
			)); ?>
<!-- header line .end-->
<!-- main content -->
				<div class="wd-main-content">
					
					<div class="wd-results-container">
						<div class="wd-empty-results-description">
							<p class="mt35">Error: <?php echo $code; ?>! <?php echo CHtml::encode($message); ?></p>
							<a href="javascript:void(0)" onclick="history.back()" class="wd-previous-page"><span class="wd-arrow"></span><span class="wd-text">Previous page</span></a>
						</div>
					</div>
				</div>
<!-- main content .end-->
			</div>
			<div class="clear"></div>
		</div>
		</div>
