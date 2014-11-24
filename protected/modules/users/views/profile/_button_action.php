<?php
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js'
	));

	GNAssetHelper::setBase('application.modules.friends.assets');
	GNAssetHelper::scriptFile('script.friends', CClientScript::POS_BEGIN);
	GNAssetHelper::registerScript('friends', '$.Friends.initLinks($(".js-friend-request"));', CClientScript::POS_READY);

	GNAssetHelper::setBase('application.modules.followings.assets');
	GNAssetHelper::scriptFile('script.followings', CClientScript::POS_BEGIN);
	GNAssetHelper::registerScript('followings', '$.Followings.initLinks($(".js-following-request"));', CClientScript::POS_READY);

	if (!isset($user)) {
		if (isset($user_id))
			$user = ZoneUser::model()->getUserInfo($user_id);
	}
	$currentUser = currentUser();
	$currentUser->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
	$user->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
	$countFriends = $user->countFriends();

	if ($currentUser->id != $user->id) {
		$isFriend = $user->isFriend($currentUser->id);
		$isPending = false;
		if (!$isFriend)
			$isPending = $currentUser->isPendingBy($user->id);
		$isPendingMe = false;
		if (!$isFriend && !$isPending)
			$isPendingMe = $user->isPendingBy($currentUser->id);
	}

	$user->detachBehavior('UserFriend');
	$currentUser->detachBehavior('UserFriend');
?>
<?php if ($currentUser->id != $user->id) : ?>
	<?php if ($isFriend) : ?>
		<span class="wd-getact-bt wd-relationfriend-bt js-friend-request" data-action="unfriend" data-user_id="<?php echo $user->hexID; ?>">Friend</span>
	<?php else : ?>
		<?php if ($isPending) : ?>
			<span class="wd-getact-bt wd-addfriend-bt js-friend-request" data-action="pending" data-user_id="<?php echo $user->hexID; ?>">Request Sent</span>
		<?php else : ?>
			<?php if ($isPendingMe) : ?>
				<span class="wd-getact-bt wd-addfriend-bt js-friend-request" data-action="accept" data-user_id="<?php echo $user->hexID; ?>">Accept Friend</span>
			<?php else : ?>
				<span class="wd-getact-bt wd-addfriend-bt js-friend-request" data-action="request" data-user_id="<?php echo $user->hexID; ?>">Add Friend</span>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	
	<span class="wd-dropdow-slmbt wd_toggle_bt wd-tooltip-hover" title="More function..."><span class="wd-addmore"></span></span>
	<div class="wd-actmore-user-toggle wd_toggle">
		<div class="wd-scroll-1">
			<div class="content">
				<span class="wd-uparrow-1"></span>
				<ul class="bbor-solid-2">
					<li><a href="#" class="wd_auto_close_toggle">Send message</a></li>
					<li><a href="#" class="wd_auto_close_toggle">Recommend</a></li>
				</ul>
				<?php if ($currentUser->id == $user->id) : ?>
				<ul class="bbor-solid-2">
					<li><a href="<?php echo GNRouter::createUrl('/users/family'); ?>" class="wd_auto_close_toggle">Family</a></li>
					
				</ul>
				<?php endif; ?>
				<ul>
					<li><a href="#" class="wd_auto_close_toggle">Block</a></li>
					<li><a href="#" class="wd_auto_close_toggle">Report...</a></li>
				</ul>
			</div>
		</div>
	</div>

	
<?php else : ?>
	<!--<span class="wd-getact-bt wd-addfriend-bt" onClick="window.location='<?php echo GNRouter::createUrl('/profile/edit'); ?>';">Edit Profile</span>-->
<?php endif; ?>
