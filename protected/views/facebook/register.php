<div class="form user-registration wd-business-form jlbd-form-basic">
	<?php 
	$this->widget('widgets.breadcrumb.JLBDBreadcrumb', array(
			'arrBreadcrums' 	=> array(
					array('label'=>'Home', 'url'=>  JLRouter::createAbsoluteUrl('/'), 'class' => 'wd-finish-link'),
					array('label'=>"Register New Account", 'class' => 'wd-finish-notlink'),
			),
	));
	?>	
	<h3 class="wd-title wd-title-detail">Register New account</h3>
	<p class="wd-note wd-none-italic">Fields with <span class="wd-required">*</span> are required.</p>
<?php $this->widget('widgets.uniform.UniForm'); ?>
<?php echo CUFHtml::beginForm(); ?>
 
<?php echo CUFHtml::errorSummary($model); ?>
 
<?php echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels')); ?>
<?php echo CUFHtml::openActiveCtrlHolder($model,'email'); ?>
<?php echo CUFHtml::activeLabelEx($model,'email'); ?>
<?php echo CUFHtml::activeTextField($model, 'email', array('maxlength'=>64)); ?>
<?php echo CUFHtml::closeCtrlHolder(); ?>
 
<?php echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels')); ?>
<?php echo CUFHtml::openActiveCtrlHolder($model,'firstname'); ?>
<?php echo CUFHtml::activeLabelEx($model,'firstname'); ?>
<?php echo CUFHtml::activeTextField($model, 'firstname', array('maxlength'=>64)); ?>
<?php echo CUFHtml::closeCtrlHolder(); ?> 

<?php echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels')); ?>
<?php echo CUFHtml::openActiveCtrlHolder($model,'lastname'); ?>
<?php echo CUFHtml::activeLabelEx($model,'lastname'); ?>
<?php echo CUFHtml::activeTextField($model, 'lastname', array('maxlength'=>64)); ?>
<?php echo CUFHtml::closeCtrlHolder(); ?> 

<?php echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels')); ?>
<?php echo CUFHtml::openActiveCtrlHolder($model,'location'); ?>
<?php echo CUFHtml::activeLabelEx($model,'location'); ?>
<?php echo CUFHtml::activeTextField($model, 'location', array('maxlength'=>64)); ?>
<?php echo CUFHtml::closeCtrlHolder(); ?> 

<?php echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels')); ?>
<?php echo CUFHtml::openActiveCtrlHolder($model,'password'); ?>
<?php echo CUFHtml::activeLabelEx($model,'password'); ?>
<?php echo CUFHtml::activePasswordField	($model, 'password', array('maxlength'=>64)); ?>
<?php echo CUFHtml::closeCtrlHolder(); ?> 


<?php echo CUFHtml::closeTag('fieldset'); ?>
<?php echo CUFHtml::hiddenField('FacebookRegister[id]',$model->id); ?>
<?php echo CUFHtml::hiddenField('FacebookRegister[firstname]',$model->firstname); ?>
<?php echo CUFHtml::hiddenField('FacebookRegister[username]',$model->username); ?>
<div class="buttonHolder">
<?php echo CUFHtml::resetButton(Yii::t('general', 'Reset')); ?>
<?php echo CUFHtml::submitButton(Yii::t('general', 'Create')); ?>
</div>
<?php echo CHtml::link('Do you have a current existing account with us ?',array('/facebook/map')); ?>
<?php echo CUFHtml::endForm(); ?>
</div>
