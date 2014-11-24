<?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));

GNAssetHelper::setBase('greennet.modules.users.assets', 'user');
GNAssetHelper::scriptFile('jlbd.users.login', CClientScript::POS_END);
?>

<div class="span4">
<?php /** @var BootActiveForm $form */
GNAssetHelper::registerScript('Login', "jlbd.users_login.Libs.initForm($('#userLoginForm'));", CClientScript::POS_READY);
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' => 'userLoginForm',
	'enableClientValidation' => true,
	// 'clientOptions'=> array('validateOnSubmit'=>true),
	'htmlOptions'=>array('class' => 'well'),
)); ?>
	<legend>Login</legend>
	<?php echo $form->textFieldRow($model, 'email', array('class'=>'span3','placeholder'=>'Enter your email address'));?>
	<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span3','placeholder'=>'Enter your password')); ?>
	<?php echo $form->checkboxRow($model, 'rememberMe'); ?>
	<?php
		$renderEmail = !empty($model->email) ? '/email/'.$model->email : '';
	?>
	<a class = "muted" href="<?php echo GNRouter::createUrl('/recover/forgot_password'.$renderEmail); ?>">Forgot your password?</a></br>
	<a class = "muted" href="<?php echo GNRouter::createUrl('/users/twitter/');?>">Connect to twitter</a></br>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'label' => 'Login',
		'type' => 'primary',
	));?>
<?php $this->endWidget(); ?>
</div>