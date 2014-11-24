<?php 
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js'
	));
	
	GNAssetHelper::setBase('application.modules.users.assets', 'user');
	GNAssetHelper::scriptFile('jlbd.users.changepass', CClientScript::POS_END);
?>
<div class='span4'>
<?php /** @var BootActiveForm $form */
GNAssetHelper::registerScript('ChangePassword', "jlbd.users_changepass.Libs.initFormChange($('#userChangepassForm'));", CClientScript::POS_READY);
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'userChangepassForm',
	'enableClientValidation' => true,
	// 'clientOptions'=> array('validateOnSubmit'=>true),
	'htmlOptions'=>array(
		'class' => 'well'
		),
	)); 
?>
<legend>Forgot Password</legend>
<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span3')); ?>
<?php echo $form->passwordFieldRow($model, 'confirmPassword', array('class'=>'span3')); 
?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit', 
			'label'=>'Change',
			'type'	=> 'primary',
		)
	); 
?>
 <?php $this->endWidget(); ?>
 </div>
