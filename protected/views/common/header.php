<?php 
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js',
));

GNAssetHelper::setBase('justlook');
GNAssetHelper::cssFile('header');

if(!currentUser()->isGuest)
{
	$location = Yii::app()->cache->get(currentUser()->hexID . 'location');
	if(!$location)
		$location = Yii::app()->session->get('currentLocation','Sydney');
}
else
{
   $location =  Yii::app()->session->get('currentLocation','Sydney');
}
?>


	<div class="wd-center">
		<div class="wd-header-content">
			<div class="icons">
				<i class="pointer" style="opacity: 0; "></i>
				<?php $this->widget('widgets.toolbar.JLBDHomeLocation') ?>
				<span class="ico ico2"><a href="<?php echo JLRouter::createUrl('/browse/business')?>" style="top: 0px; ">Businesses</a></span>
				<span class="ico ico3"><a href="<?php echo JLRouter::createUrl('/publicPages/publicReviews')?>" style="top: 0px; ">Reviews</a></span>
				<span class="ico ico4"><a href="<?php echo JLRouter::createUrl('/publicPages/publicLists')?>" style="top: 0px; ">Lists</a></span>
				<span class="ico ico5 hoverFix"><a href="<?php echo JLRouter::createUrl('/dashboard/friends/find')?>" style="top: 15px; " id='menuFindFriend'>Find Friends</a></span>
				
			</div>
			<?php 
				/**
				 * Append page popup login for User
				 */
				Yii::app()->controller->renderPartial('application.modules.user.views.login.login-popup');
			?>
		</div>
	</div>
<?php $this->beginWidget('widgets.JLScriptPacker', array(
	'id'		=> 'FindFriendMenu',
	'type'		=> 'js',
	'position'	=> CClientScript::POS_READY
))?>
$('#menuFindFriend').click(function() {
	var href = $(this).attr('href');
	if (jlbd.user.collection.current.user.id == -1) {
		jlbd.popupLogin.autoShow({
			'text': 'You must login in order to add business to list!',
			'complete': function(){window.location=window.location;},
			redirectURL : href
		});
		return false;
	}
});

<?php $this->endWidget();?>