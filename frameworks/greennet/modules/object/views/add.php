<?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));

// GNAssetHelper::setBase('greennet.modules.object.assets', 'user');
// GNAssetHelper::scriptFile('jlbd.users.login', CClientScript::POS_END);
?>

<div class="span4">
<?php /** @var BootActiveForm $form */
// GNAssetHelper::registerScript('Login', "jlbd.users_login.Libs.initForm($('#userLoginForm'));", CClientScript::POS_READY);
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'addObjectForm',
	'enableClientValidation' => true,
	// 'clientOptions'=> array('validateOnSubmit'=>true),
	'htmlOptions'=>array('class' => 'well'),
)); ?>
	<legend>Login</legend>
	<?php echo $form->textFieldRow($model, 'name', array('class'=>'span3','placeholder'=>'Name'));?>
	<?php echo $form->textAreaRow($model, 'description', array('class'=>'span3','placeholder'=>'Description')); ?>
	
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'label' => 'Login',
		'type' => 'primary',
	));?>
<?php $this->endWidget(); ?>
</div>