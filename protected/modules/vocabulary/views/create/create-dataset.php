<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'userRegistrationForm',
	'enableClientValidation' => true,
	'enableAjaxValidation' => true,
	//'action'=>GNRouter::createUrl('/users/registration'),
	'clientOptions'=> array(
		'validateOnSubmit'=>true,
		'validateOnChange'=>false
	),
	'htmlOptions'=>array('class' => 'well'),
)); ?>
<fieldset>
	<legend>Create dataset</legend>
	<?php echo $form->textField($model,'title', array('class'=>'span3','placeholder'=>'Dataset Title:'));?>
	
	<div class="wd-submit">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'', 'label'=>'Create',
			'htmlOptions'=>array(
				'class'=>'btn-submit'
			)
		)); ?>
	</div>
</fieldset>
<?php $this->endWidget();?>