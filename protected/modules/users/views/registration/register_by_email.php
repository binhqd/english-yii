<div class="span4">
<?php
	GNAssetHelper::init(array(
		'image' => 'img',
		'css' =>' css',
		'script' => 'js'
	));
	GNAssetHelper::setBase('application.modules.users.assets', 'user');
	GNAssetHelper::scriptFile('jlbd.users.registration', CClientScript::POS_END);
?>
<?php /** @var BootActiveForm $form */
GNAssetHelper::registerScript('Login', "jlbd.users_registration.Libs.initFormRegisterByFillEmail($('#userRegisterByEmail'));", CClientScript::POS_READY);
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'userRegisterByEmail',
	'htmlOptions'=>array('class'=>'well'),
	'enableClientValidation'=>true,
	// 'clientOptions'=> array('validateOnSubmit'=>true),
));?>
	<legend>Register by email</legend>
	<div class="">
	<?php echo $form->labelEx($model,'email');?>
	<?php echo $form->textField($model,'email', array('class'=>'span3'));?>
	<?php echo $form->error($model,'email');?>
	</div>
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Submit')); ?>
<?php $this->endWidget();?>
</div>