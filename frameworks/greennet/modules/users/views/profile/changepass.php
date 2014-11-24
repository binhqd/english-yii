<?php 
/**
 * @author Ngocnm
 * @version 1.0
 * @created 01-Feb-2013 
 * @modified 
 */ 
 ?>
 <?php 
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));

GNAssetHelper::setBase('greennet.modules.users.assets', 'user');
GNAssetHelper::scriptFile('jlbd.users.changepass', CClientScript::POS_END);
?>
<div class='span4'>
<?php /** @var BootActiveForm $form */
if ($hasCreatedPassword)
	GNAssetHelper::registerScript('ChangePassword', "jlbd.users_changepass.Libs.initFormChangePasswordFull($('#userProfileChangepassForm'));", CClientScript::POS_READY);
else
	GNAssetHelper::registerScript('ChangePassword', "jlbd.users_changepass.Libs.initFormChange($('#userProfileChangepassForm'));", CClientScript::POS_READY);
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'userProfileChangepassForm',
	'enableClientValidation' => false,
	'htmlOptions'=>array('class'=>'well'),
)); ?>
<?php if ($hasCreatedPassword) : ?>
	<legend>Change Password</legend>
<?php else : ?>
	<legend>Create Password</legend>
<?php endif; ?>
<?php if ($hasCreatedPassword) echo $form->passwordFieldRow($model, 'currentPassword', array('class'=>'span3')); ?>
<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span3')); ?>
<?php echo $form->passwordFieldRow($model, 'confirmPassword', array('class'=>'span3')); 
?>
<br/>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType'=>'submit', 
	'label'=> $hasCreatedPassword ? 'Change' : 'Create',
	'type'	=> 'primary',
)); ?> 
<?php $this->endWidget(); ?>
</div>
