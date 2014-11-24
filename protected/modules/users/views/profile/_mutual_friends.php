<?php
$currentUser = currentUser();
if (!$currentUser->isGuest && $currentUser->id != $user->id) : ?>
	<?php
	$currentUser->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
	$countMutualFriends = $currentUser->countMutualFriends($user->id);
	$mutualFriends = $currentUser->mutualFriends($user->id, '', '', 5);
	$isFriend = $currentUser->isFriend($user->id);
	$currentUser->detachBehavior('UserFriend');
	if ($countMutualFriends > 0 && !$isFriend) :
	$isMale = true;
	if (!empty($user->profile) && $user->profile->gender != GNUserProfile::TYPE_GENDER_MALE)
		$isMale = false;
	?>
	<div class="wd-left-block">
		<div class="wd-mutual-friends">
			<p class="wd-intro">To see what <?php echo $isMale ? 'he' : 'she'; ?> shares with friends, <a href="javascript:void(0)" onclick="$('.js-friend-request[data-action=\'request\'][data-user_id=\'<?php echo $user->hexID; ?>\']').click();">send <?php echo $isMale ? 'him' : 'her'; ?> a friend request</a>.</p>
			<ul class="wd-mutual-friend-list-1">
				<?php foreach ($mutualFriends as $mf) : ?>
				<?php $userInfo = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($mf['user_id'])); ?>
				<?php if (empty($userInfo)) continue; ?>
				<li class="wd-tooltip-hover" title="<?php echo $userInfo->displayname; ?>"><a href="<?php echo GNRouter::createUrl('/profile/' . $userInfo->username); ?>"><img src="<?php echo Yii::app()->baseUrl; ?>/upload/user-photos/<?php echo $userInfo->hexID; ?>/fill/34-34/<?php echo !empty($userInfo->profile) ? $userInfo->profile->image : ''; ?>" alt="<?php echo $userInfo->displayname; ?>" height="34" width="34"></a></li>
				<?php endforeach; ?>
			</ul>
			<p class="wd-count-mf">
				<?php /*$this->widget('application.modules.friends.components.widgets.ZoneMutualFriendsLink', array(
					'user'		=> $user,
				));*/ ?>
				<?php echo $countMutualFriends . ' Mutual Friend' . ($countMutualFriends==1?'':'s'); ?>
			</p>
		</div>
	</div>
	<?php endif; ?>
<?php endif; ?>