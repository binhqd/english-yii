<!--
 *
 *@Usage	: This class used to confirm email.
 *@author	: Chu Tieu
 *@Version	: 1.0
 *@Create	: 02-02-2013
 *
 -->
  <?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));
GNAssetHelper::setBase('application.modules.users.assets', 'user');
GNAssetHelper::scriptFile('jlbd.users.change_email', CClientScript::POS_END);
?>
<div class="span4">
<?php /** @var BootActiveForm $form */
GNAssetHelper::registerScript('confirmChangeEmail', "jlbd.users_changeEmail.Libs.initForm($('#confirmChangeEmail'));", CClientScript::POS_READY);
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'confirmChangeEmail',
	'htmlOptions'=>array('class'=>'well'),
	'enableClientValidation'=>true,
)); ?>
<legend>Change Email</legend>
<?php echo $form->labelEx($model,'email'); ?>
<?php echo $form->textField($model,'email', array('class'=>'span3')); ?>
<?php echo $form->error($model,'email'); ?>
<br>
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Send')); ?>
<?php $this->endWidget(); ?>
</div>