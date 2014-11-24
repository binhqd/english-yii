<?php 
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));
?>

<!-- CSS Files -->
<?php GNAssetHelper::setBase('justlook');?>
<?php GNAssetHelper::cssFile('validationEngine.jquery');?>
<?php GNAssetHelper::cssFile('jlbd.form.basic');?>
<?php //GNAssetHelper::cssFile('jlbd-popup');?>
<?php GNAssetHelper::scriptFile('jquery.validationEngine', CClientScript::POS_END);?>

<?php GNAssetHelper::setBase('application.modules.user.assets', "user");?>
<?php GNAssetHelper::scriptFile('en/jlbd.registration', CClientScript::POS_END);?>
<?php GNAssetHelper::scriptFile('jlbd.registration.form', CClientScript::POS_END);?>

<div style=" float:left; width:900px;margin-left: 100px;">
	
	<!-- Gọi Boostrap alert  -->
	<?php $this->widget('bootstrap.widgets.TbAlert', array(
			'block'=>true, // display a larger alert block?
			'fade'=>true, // use transitions?
			'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
			'alerts'=>array( // configurations per alert type
				'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
			)
		)); 
	?>
	
	<div class="form user-registration wd-business-form jlbd-form-basic">
		<h3 class="wd-title wd-title-detail">Created New account</h3>
		<p class="wd-note wd-none-italic">Fields with <span class="wd-required" style="color:red;">*</span> are required.</p>
		<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm', array(
			'id'=>'admin_created_user',
			'enableAjaxValidation'=>false,
		));
		?>
		<fieldset>
		<?php echo $form->errorSummary($model); ?>
			<div class="row wd-input">
				<label for="JLRegistrationInfoForm_email" class="required">Email <span class="required">*</span></label>
				<?php echo $form->textField($model,'email', array('class' => 'validate[required,custom[email]] text-input')); ?>
				<?php //echo $form->error($model,'email'); ?>
			</div>
			
			<div class="row wd-input">
				<label for="JLRegistrationInfoForm_email" class="required">Password <span class="required">*</span></label>
				<?php echo $form->passwordField($model,'textPassword', array('class' => 'validate[required,[minSize[6],maxSize[128]]] text-input')); ?>
				<?php //echo $form->error($model,'password'); ?>
			</div>
			
			<div class="row wd-input">
				<label for="JLRegistrationInfoForm_email" class="required">First name <span class="required">*</span></label>
				<?php echo $form->textField($model,'firstname', array('class' => 'validate[maxSize[20]] text-input')); ?>
				<?php //echo $form->error($model,'firstname'); ?>
			</div>
			
			<div class="row wd-input">
				<label for="JLRegistrationInfoForm_email" class="required">Last name <span class="required">*</span></label>
				<?php echo $form->textField($model,'lastname', array('class' => 'validate[maxSize[50]] text-input')); ?>
				<?php //echo $form->error($model,'lastname'); ?>
			</div>
			
			<div class="row wd-submit">
				<div class="submit">
					<?php echo CHtml::submitButton(UserModule::t('Register')); ?>
				</div>
			</div>
		</fieldset>
		<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>
<div style=" float:left; width:250px; margin-left:10px; margin-top:20px;">
<?php $this->widget('ext.bootstrap.widgets.BootMenu', array(
    'type'=>'list', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>false, // whether this is a stacked menu
    'items'=>array(
        array('label'=>'List user account', 'url'=>Yii::app()->createUrl('admin_manage/manageUser/listUser'), 'active'=>true),
    ),
)); ?>
</div>