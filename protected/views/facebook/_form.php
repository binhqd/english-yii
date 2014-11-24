<div class="form">
<?php
 $model = new Comment;
 $better_token = md5(uniqid(rand(), true));
 $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form-' . $better_token,
	'enableAjaxValidation'=>false,
	'action' => Yii::app()->createUrl('/facebook/ajaxaddcomment/',array('object_id' => $id)) 
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'author'); ?>
		<?php echo $form->textField($model,'author',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'author'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<script type="text/javascript">
	$('#<?php echo 'comment-form-' . $better_token; ?>').submit(function(){
		$.post($(this).attr('action'),$(this).serialize(),function(resp){
			if(resp == 'good job')
			{
				$('#<?php echo 'comment-form-' . $better_token; ?> input[type="text"]').val('');
				$('#<?php echo 'comment-form-' . $better_token; ?> textarea').val('');
				$('#<?php echo 'comment-form-' . $better_token; ?> .note').html('Thanks your comment.');
			}
			else
			{
				$('#<?php echo 'comment-form-' . $better_token; ?> .note').html(resp);	
			}							
		});
		return false;	
	});
</script>