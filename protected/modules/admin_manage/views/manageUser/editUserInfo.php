<?php 
// debug($userInfo);
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
		<h3 class="wd-title wd-title-detail">Edit User Info</h3>
		<p class="wd-note wd-none-italic">Fields with <span class="wd-required">*</span> are required.</p>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'registration-form',
			'enableAjaxValidation'=>true,
		));
		?>
		<fieldset>
			<div class="row wd-input">
				<?php echo $form->labelEx($model,'username'); ?>
				<?php echo $form->textField($model,'username', array('class' => 'validate[required,[minSize[5],maxSize[20]]] text-input', 'value'=>$userInfo->username)); ?>
				<?php echo $form->error($model,'username'); ?>
			</div>
			
			<div class="row wd-input">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email', array('class' => 'validate[required,custom[email]] text-input','value'=>$userInfo->email)); ?>
				<?php echo $form->error($model,'email'); ?>
				<span class="hint wd-note-amazon wd-font-11">
					<?php echo UserModule::t("Eg. abc@extends"); ?>
				</span>
			</div>
			
			<div class="row wd-input">
				<label for="JLAwaitingBusiness_suburb_name">Firstname </label>
				<?php echo $form->textField($model,'firstname', array('class' => 'validate[maxSize[20]] text-input','value'=>$userInfo->firstname)); ?>
				<?php echo $form->error($model,'firstname'); ?>
			</div>
			
			<div class="row wd-input">
				<label for="JLAwaitingBusiness_suburb_name">Lastname </label>
				<?php echo $form->textField($model,'lastname', array('class' => 'validate[maxSize[50]] text-input','value'=>$userInfo->lastname)); ?>
				<?php echo $form->error($model,'lastname'); ?>
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