<?php $this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Assignments'),
); ?>

<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.form').toggle();
	return false;
});
");
?>

<div id="assignments">

	<h2><?php echo Rights::t('core', 'Assignments'); ?></h2>

	<p>
		<?php echo Rights::t('core', 'Here you can view which permissions has been assigned to each user.'); ?>
	</p>

	<!-- huytbt Thêm chức năng lọc User theo Role -->
	<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
	<div class="form" style="display:none;padding: 0px 20px;">

		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'id'=>'filter-form',
			'method'=>'post',
		)); ?>

		<div class="row">
			<?php echo $form->label($model,'name'); ?>
			<?php echo $form->dropDownList($model,'name', CMap::mergeArray(array('*'=>'-------------'), CHtml::listData($model->findAllByAttributes(array('type'=>CAuthItem::TYPE_ROLE)), 'name', 'description'))); ?>
		</div>

		<div class="row">
			<?php echo $form->label($modelUser,'username'); ?>
			<?php echo $form->textField($modelUser,'username'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($modelUser,'email'); ?>
			<?php echo $form->textField($modelUser,'email'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($modelUser,'firstname'); ?>
			<?php echo $form->textField($modelUser,'firstname'); ?>
		</div>

		<div class="row">
			<?php echo $form->label($modelUser,'lastname'); ?>
			<?php echo $form->textField($modelUser,'lastname'); ?>
		</div>

	   	<div class="row buttons">
			<?php echo CHtml::submitButton('Search'); ?>
		</div>

	<?php $this->endWidget(); ?>

	</div>
	<!-- /huytbt Thêm chức năng lọc User theo Role -->

	<?php $this->widget('bootstrap.widgets.TbGridView', array(
		'type'=>'striped bordered condensed',
		'dataProvider'=>$dataProvider,
		'template'=>"{items}\n{pager}",
		'emptyText'=>Rights::t('core', 'No users found.'),
		'htmlOptions'=>array('class'=>'grid-view assignment-table'),
		'columns'=>array(
			array(
				'name'=>'name',
				'header'=>Rights::t('core', 'Name'),
				'type'=>'raw',
				'htmlOptions'=>array('class'=>'name-column'),
				'value'=>'$data->getAssignmentNameLink()',
			),
			array(
				'name'=>'assignments',
				'header'=>Rights::t('core', 'Roles'),
				'type'=>'raw',
				'htmlOptions'=>array('class'=>'role-column'),
				'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_ROLE)',
			),
			array(
				'name'=>'assignments',
				'header'=>Rights::t('core', 'Tasks'),
				'type'=>'raw',
				'htmlOptions'=>array('class'=>'task-column'),
				'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_TASK)',
			),
			array(
				'name'=>'assignments',
				'header'=>Rights::t('core', 'Operations'),
				'type'=>'raw',
				'htmlOptions'=>array('class'=>'operation-column'),
				'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_OPERATION)',
			),
		)
	)); ?>

</div>