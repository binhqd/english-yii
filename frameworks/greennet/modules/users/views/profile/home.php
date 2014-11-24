 <?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));
//GNAssetHelper::setBase('application.modules.users.assets', 'user');
?>
<?php
/**
 *@author: Thanhngt
 *@Create: 04-02-2013
 */
if (!empty($user)) {
	$timestamp = $user->created;
	$date = date("F d, Y", $timestamp);
	$profile = $user->profile;
	if (empty($profile))
		$profile = GNUserProfile::model();
?>
<div id="div" style='margin:0 auto;'>
	<div>
		<?php if (!empty($profile) && !empty($profile->image)):?>
		<img src='<?php echo GNRouter::createUrl("/upload/user-photos/{$user->hexID}/fill/64-64/{$profile->image}")?>'/>
		<?php endif;?>
	</div>
	<span style ='display:block;'><b style='float:left;margin-right:10px'>First name:</b><p><?php echo $user->firstname;?></p></span>
	<span style ='display:block;'><b style='float:left;margin-right:10px'>Last name:</b><p><?php echo $user->lastname; ?></p></span>
	<span style ='display:block;'><b style='float:left;margin-right:10px'>Email:</b><p><?php echo $user->email;?></p></span>
	<span style ='display:block;'><b style='float:left;margin-right:10px'>Created:</b><p><?php echo $date;?></p></span>
	<span style ='display:block;'><b style='float:left;margin-right:10px'>Gender:</b><p><?php echo (!empty($profile->gender)&& $profile->gender == GNUserProfile::TYPE_GENDER_MALE) ? 'Male' : 'Female';?></p></span>
	<span style ='display:block;'><b style='float:left;margin-right:10px'>Location:</b><p><?php echo !empty($profile->location) ? $profile->location :'[Not set]';?></p></span>
	<span style ='display:block;'><b style='float:left;margin-right:10px'>Phone:</b><p><?php echo !empty($profile->phone) ? $profile->phone :'[Not set]';?></p></span>
	<span style ='display:block;'><b style='float:left;margin-right:10px'>Status text:</b><p><?php echo !empty($profile->status_text) ? $profile->status_text :'[Not set]';?></p></span>
	<a class = "muted" href="<?php echo GNRouter::createUrl('/profile/edit'); ?>">Edit your profile</a><br>
	<a class = "muted" href="<?php echo GNRouter::createUrl('/profile/change_email'); ?>">Change your email</a><br>
	<a class = "muted" href="<?php echo GNRouter::createUrl('/profile/change_password'); ?>">Change your password</a><br>
</div>
<?php }?>