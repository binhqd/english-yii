<?php /** @var BootActiveForm $form */
//GNAssetHelper::registerScript('editProfile', "jlbd.users_editprofile.Libs.initForm($('#editProfile'));", CClientScript::POS_READY);
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'contactForm',
	'enableClientValidation'=>true,
	'htmlOptions'=>array('class'=>'well', 'enctype' => 'multipart/form-data'),
)); ?>
	
<?php 
$flash = app()->session["flash"];
if (!empty($flash)) :
unset(app()->session['flash']);
?>
<?php if ($flash['error']):?>
<div class="alert alert-error"><?php echo $flash['message']?></div>
<?php else :?>
<div class="alert alert-success"><?php echo $flash['message']?></div>
<?php endif;?>
<?php endif;?>
<fieldset>
	<div class="">
	<?php echo $form->labelEx($model,'name'); ?>
	<?php echo $form->textField($model,'name', array('class'=>'span3')); ?>
	<?php echo $form->error($model,'name'); ?>
	</div>
	
	<div class="">
	<?php echo $form->labelEx($model,'email'); ?>
	<?php echo $form->textField($model,'email', array('class'=>'span3')); ?>
	<?php echo $form->error($model,'email'); ?>
	</div>
	
	<div class="">
	<?php echo $form->labelEx($model,'phone'); ?>
	<?php echo $form->textField($model,'phone', array('class'=>'span3')); ?>
	<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="">
	<?php echo $form->labelEx($model,'content'); ?>
	<?php echo $form->textarea($model,'content', array('class'=>'span3')); ?>
	<?php echo $form->error($model,'content'); ?>
	</div>
	
</fieldset>
<fieldset>
	<button type="submit" class="btn btn-primary">
		Submit
	</button>
	<button type="reset" class="btn">
		Reset
	</button>
</fieldset>
<?php $this->endWidget(); ?>