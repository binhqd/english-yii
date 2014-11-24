<!--
 *
 *@Usage	: This class used to edit profile user.
 *@author	: Chu Tieu
 *@Version	: 1.0
 *@Create	: 02-02-2013
 *,'value'=>!empty($userProfile->status_text) ? $userProfile->status_text : ''
 -->
<?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));
GNAssetHelper::setBase('greennet.modules.users.assets', 'user');
GNAssetHelper::scriptFile('jlbd.users.editprofile', CClientScript::POS_END);
?>
<div class="span4">
<?php /** @var BootActiveForm $form */
//GNAssetHelper::registerScript('editProfile', "jlbd.users_editprofile.Libs.initForm($('#editProfile'));", CClientScript::POS_READY);
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'editProfile',
	'enableClientValidation'=>true,
	'htmlOptions'=>array('class'=>'well', 'enctype' => 'multipart/form-data'),
)); ?>
<legend>Edit Profile</legend>
<?php echo $form->textFieldRow($user, 'firstname', array('class'=>'span3')); ?>
<?php echo $form->textFieldRow($user, 'lastname', array('class'=>'span3')); ?>
<?php 
$userProfile->gender = GNUserProfile::TYPE_GENDER_MALE;
echo $form->radioButtonListRow($userProfile, 'gender', array(
	GNUserProfile::TYPE_GENDER_MALE		=> 'Male',
	GNUserProfile::TYPE_GENDER_FEMALE	=> 'Female',
)); ?>
<?php echo $form->textFieldRow($userProfile, 'location', array('class'=>'span3')); ?>
<?php echo $form->textFieldRow($userProfile, 'phone', array('class'=>'span3')); ?>
<?php echo $form->textFieldRow($userProfile, 'status_text', array('class'=>'span3')); ?>
<div>
	<?php if (!empty($userProfile) && !empty($userProfile->image)):?>
	<img src='<?php echo GNRouter::createUrl("/upload/user-photos/{$user->hexID}/fill/64-64/{$userProfile->image}")?>'/>
	<?php endif;?>
	<?php echo $form->fileField($userProfile, 'image', array('class'=>'span3')); ?>
</div>

</br>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType'	=> 'submit',
	'label'			=> 'Update',
	'type'			=> 'primary',
)); ?>
<?php $this->endWidget(); ?>
</div>