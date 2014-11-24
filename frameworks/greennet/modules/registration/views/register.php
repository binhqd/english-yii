<div class="span4">
<?php
GNAssetHelper::init(array(
	'image' => 'img',
	'css' =>' css',
	'script' => 'js'
));
GNAssetHelper::setBase('greennet.modules.registration.assets', 'registration');
GNAssetHelper::scriptFile('jlbd.users.registration', CClientScript::POS_END);
?>
<?php
GNAssetHelper::registerScript('Login', "jlbd.users_registration.Libs.initFormRegisterByFillInfo($('#userRegistrationForm'));", CClientScript::POS_READY);
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'userRegistrationForm',
	'htmlOptions'=>array('class'=>'well'),
	'enableClientValidation'=>true,
	// 'clientOptions'=> array('validateOnSubmit'=>true),
)); ?>
	<legend>Register by fill infomation</legend>
	<div class="">
	<?php echo $form->labelEx($model,'email'); ?>
	<?php echo $form->textField($model,'email', array('class'=>'span3')); ?>
	<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="">
	<?php echo $form->labelEx($model,'confirmEmail'); ?>
	<?php echo $form->textField($model,'confirmEmail', array('class'=>'span3')); ?>
	<?php echo $form->error($model,'confirmEmail'); ?>
	</div>

	<div class="">
	<?php echo $form->labelEx($model,'password'); ?>
	<?php echo $form->passwordField($model,'password', array('class'=>'span3')); ?>
	<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="">
	<?php echo $form->labelEx($model,'confirmPassword'); ?>
	<?php echo $form->passwordField($model,'confirmPassword', array('class'=>'span3')); ?>
	<?php echo $form->error($model,'confirmPassword'); ?>
	</div>

	<div class="">
	<?php echo $form->labelEx($model,'firstname'); ?>
	<?php echo $form->textField($model,'firstname', array('class'=>'span3')); ?>
	<?php echo $form->error($model,'firstname'); ?>
	</div>

	<div class="">
	<?php echo $form->labelEx($model,'lastname'); ?>
	<?php echo $form->textField($model,'lastname', array('class'=>'span3')); ?>
	<?php echo $form->error($model,'lastname'); ?>
	</div></br>
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Submit')); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Reset')); ?>
<?php $this->endWidget(); ?>
</div>