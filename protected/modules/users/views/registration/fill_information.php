<div class="span4">
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'RegistrationForm',
	'type'=>'horizontal',
	'htmlOptions'=>array('class'=>'well'),
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
)); ?>
	<legend>Register by email</legend>
	<div class="">
	<?php echo $form->labelEx($model,'password'); ?>
	<?php echo $form->passwordField($model,'password', array('class'=>'span3')); ?>
	<?php echo $form->error($model,'password'); ?>
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