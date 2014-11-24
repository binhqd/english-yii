<?php
$user->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
$countFriends = $user->countFriends();
$user->detachBehavior('UserFriend');
$urlFriends = GNRouter::createUrl('/friends/list');
if (currentUser()->isGuest || currentUser()->id != $user->id)
	$urlFriends = GNRouter::createUrl('/friends/list', array('username'=>$user->username));

$user->attachBehavior('UserFollowing', 'application.modules.followings.components.behaviors.GNUserFollowingBehavior'); // Attach behavior following for user
$countFollowings = $user->countFollowingsByObjectType('object');
$user->detachBehavior('UserFollowing');
$urlFollowings = GNRouter::createUrl('/followings/list/followings');
if (currentUser()->isGuest || currentUser()->id != $user->id)
	$urlFollowings = GNRouter::createUrl('/followings/list/followings', array('username'=>$user->username));

$totalPhotos = ZoneUser::model()->countPhotos($user->id);

?>
<div class="wd-headline custom-header-action-photo ">
	<?php
	if(!empty($pages)){
		switch($pages){
			case "album":
	?>
	
	<div class="wd-act-button-photo-update">
		<a href="javascript:void(0)" onclick="addPhotos()" class="wd-bt-add-photo"><span class="wd-icon-add-photo"></span>Add photos</a>
		<a href="javascript:void(0)" id="doneUpload" validate="1" album_id="<?php echo !empty($_GET['album_id']) ? $_GET['album_id'] : "";?>"  style="display:none" class="wd-bt-done-upload wd-save-button">Done upload</a>
		<a href="javascript:void(0)" onclick="cancelPhotos()" style="display:none" id="cancelUpload" class="wd-bt-cancel">Cancel</a>
	</div>
	<?php
			break;
		}
	}
	?>
	<ul class="wd-user-interaction-status">
		<li><a href="<?php echo $urlFriends; ?>"><span class="wd-icon-1 wd-icon-friend <?php if(!empty(Yii::app()->controller->module->id) && Yii::app()->controller->module->id == "friends") echo 'wd-icon-friend-acti'?>">&nbsp;</span><span
				class="wd-name js-friend-count-text" data-user_id="<?php echo $user->hexID; ?>">Friend<?php echo $countFriends == 1 ? '' : 's'; ?></span><span class="wd-value js-friend-count" data-user_id="<?php echo $user->hexID; ?>"><?php echo $countFriends; ?></span> </a>
		</li>
		<li><a href="<?php echo $urlFollowings; ?>"><span class="wd-icon-1 wd-icon-follow <?php if(!empty(Yii::app()->controller->module->id) && Yii::app()->controller->module->id == "followings") echo 'wd-icon-follow-acti'?>">&nbsp;</span><span
				class="wd-name">Following<?php echo $countFollowings == 1 ? '' : 's'; ?></span><span class="wd-value"><?php echo $countFollowings; ?></span> </a>
		</li>
		<li><a href="#"><span class="wd-icon-1 wd-icon-contribution">&nbsp;</span><span
				class="wd-name">Contributions </span><span class="wd-value">0</span>
		</a>
		</li>
		<li><a href="<?php echo ZoneRouter::createUrl('/userphotos?uid=' . IDHelper::uuidFromBinary($user->id,true));?>"><span class="wd-icon-1 wd-icon-photo <?php if(!empty(Yii::app()->controller->id) && (Yii::app()->controller->id == "userphotos" || Yii::app()->controller->id == "resource")) echo 'wd-icon-photo-acti'?>">&nbsp;</span><span
				class="wd-name">Photos</span><span class="wd-value totalPhotoUser"><?php echo $totalPhotos;?></span> </a>
		</li>
		
	</ul>
</div>
