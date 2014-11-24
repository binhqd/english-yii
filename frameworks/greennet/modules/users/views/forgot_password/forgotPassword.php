<?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));

GNAssetHelper::setBase('application.modules.users.assets', 'user');
GNAssetHelper::scriptFile('jlbd.users.forgot', CClientScript::POS_END);
?>

<div class="span4">
<?php /** @var BootActiveForm $form */
//GNAssetHelper::registerScript('ForgotPassword', "jlbd.users_forgot.Libs.init($('#userForgotForm'));", CClientScript::POS_READY);
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'userForgotForm',
	'enableClientValidation' => true,
	// 'clientOptions'=> array('validateOnSubmit'=>true),
	'htmlOptions'=>array(
		'class' => 'well'
	),
)); ?>
<legend>Forgot Password</legend>
<?php echo $form->textFieldRow($model, 'email', array('class'=>'span3')); ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit',
	'label' => 'Restore',
	'type' => 'primary',
)); ?>
<?php $this->endWidget(); ?>
</div>