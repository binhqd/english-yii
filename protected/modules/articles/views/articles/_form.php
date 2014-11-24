<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'dntbusiness-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
	),
)); ?>
<fieldset>
	<?php //echo $form->errorSummary($model); ?>
	<div class="controls">
		<p class="note">Fields with <span class="required">*</span> are required.</p>
	</div>

	<?php echo $form->textFieldRow($model, 'title', array('class'=>'span5')); ?>
	<?php if (!$model->isNewRecord) echo $form->textFieldRow($model, 'alias', array('class'=>'span5')); ?>
	<?php // if (!$model->isNewRecord) echo $form->textFieldRow($model, 'description', array('class'=>'span5','rows'=>3)); ?>
	<?php // echo $form->textAreaRow($model, 'content', array('class'=>'span5')); ?>
	<div class="control-group">
		<?php echo $form->labelEx($model,'content', array('class'=>'control-label')); ?>
		<div class="controls">
			<?php $this->widget('greennet.extensions.wysiwyg.GNWyswyg', array(
				'model'=>$model,
				'attribute'=>'content',
				'options'=>array(
				'width'=>'500px',
				),
			)); ?>
			<?php echo $form->error($model,'content'); ?>
		</div>
	</div>
	<?php
		if(!empty($article_category)) {
			$arrCategory = array();
			foreach($article_category as $category) {
				$arrCategory[IDHelper::uuidfrombinary($category->id)] = $category->name;
			}
			echo $form->checkBoxListRow($modelArticleMapCategory, "category_id", $arrCategory);
		}
	?>
	<!--upload image-->
	<div class="control-group">
		<?php echo $form->labelEx($model,'image',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->fileField($model, 'image'); ?>
			<?php if (!empty($model->image)) : ?>
				<p><img width="200px" src="<?php echo GNRouter::createUrl("{$this->action->uploadPath}fill/150-150/{$model->image}"); ?>"/></p>
			<?php endif; ?>
			<?php echo $form->error($model,'image'); ?>
		</div>
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? 'Create' : 'Save')); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Reset')); ?>
	</div>

</fieldset>
<?php $this->endWidget(); ?>