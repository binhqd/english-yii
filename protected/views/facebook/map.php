<div class="form user-registration wd-business-form jlbd-form-basic">
	<?php 
	$this->widget('widgets.breadcrumb.JLBDBreadcrumb', array(
			'arrBreadcrums' 	=> array(
					array('label'=>'Home', 'url'=>  JLRouter::createAbsoluteUrl('/'), 'class' => 'wd-finish-link'),
					array('label'=>"Mapping With Exist Account", 'class' => 'wd-finish-notlink'),
			),
	));
	?>	
	<h3 class="wd-title wd-title-detail">Mapping with exist account</h3>
	<p class="wd-note wd-none-italic">Fields with <span class="wd-required">*</span> are required.</p>
<?php $this->widget('widgets.uniform.UniForm'); ?>
<?php echo CUFHtml::beginForm(); ?>
 
<?php echo CUFHtml::errorSummary($model); ?>
 
<?php echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels')); ?>
<?php echo CUFHtml::openActiveCtrlHolder($model,'username'); ?>
<?php echo CUFHtml::activeLabelEx($model,'username'); ?>
<?php echo CUFHtml::activeTextField($model, 'username', array('maxlength'=>64)); ?>
<?php echo CUFHtml::closeCtrlHolder(); ?>
 
<?php echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels')); ?>
<?php echo CUFHtml::openActiveCtrlHolder($model,'password'); ?>
<?php echo CUFHtml::activeLabelEx($model,'password'); ?>
<?php echo CUFHtml::activePasswordField($model, 'password', array('maxlength'=>64)); ?>
<?php echo CUFHtml::closeCtrlHolder(); ?> 

<?php echo CUFHtml::closeTag('fieldset'); ?>

<div class="buttonHolder">
<?php echo CUFHtml::resetButton(Yii::t('general', 'Reset')); ?>
<?php echo CUFHtml::submitButton(Yii::t('general', 'Login')); ?>
</div>
<?php echo CHtml::link('Do you want to create new a account ?',array('/facebook/register')); ?>
<?php echo CUFHtml::endForm(); ?>
</div>
