<?php
Yii::app()->clientScript->registerScript('delete_selected_items', " 
	$('.delete-selected-items-form form .btn-delete-selected-items').click(function(){
		var confirm = false;
		if ($('.chkArticle input:checked').length <= 0) {
			jlbd.dialog.notify({
				'type'		: 'message',
				'autoHide'	: true,
				'message'	: 'Please select at least one item to delete!'
			});
			return false;
		}
		
		jlbd.dialog.confirm('Confirm delete', 'Are you sure to delete selected items?', function(reply) {
			if (reply) {
				$('.delete-selected-items-form form .mass_action').val('delete');
				$('.delete-selected-items-form form').submit();
			}
		});
		return false;
	});
");
?>

<div class="main-inner custom-color-orange">
	<div class="container">
		<div class="row">
			<div class="span9">
				<div class="widget widget-box">
					<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
						'homeLink'	=> CHtml::link('Dashboard', array('/admin_manage/dashboard')),
						'links'		=> array(
							'Article'=> array('/articles/admin'),
							'List',
						),
					)); ?>
					<fieldset>
						<legend>List Article</legend>
						<div class="search-form">
	
						<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
							'id'=>'dntbusiness-form',
							'enableAjaxValidation'=>false,
							'action'=>Yii::app()->createUrl($this->route),
							'method'=>'get',
							'type'=>'search',
						)); ?>
						
							<?php echo $form->textFieldRow($model, 'title', array('class'=>'span3', 'prepend'=>'<i class="icon-search"></i>')); ?>
							<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Search')); ?>
						
						<?php $this->endWidget(); ?> 
						</div><!-- search-form -->
	
						<div class="delete-selected-items-form">
							<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
								'id'		=> 'dntbusiness-delete-selected-items-form',
								'action'	=> GNRouter::createUrl($this->action->bulkDeleteUrl),
							)); ?>
							<?php echo CHtml::hiddenField(get_class($model).'[mass_action]', '', array('class'=>'mass_action')); ?>
							<!-- list-article-form -->
							<?php $this->widget('bootstrap.widgets.TbGridView', array(
								'type'			=> 'striped bordered condensed',
								'id'			=> 'dntbusiness-grid',
								'dataProvider'	=> $model->search(),
								// 'filter'		=> $model,
								'enableSorting'	=> false,
								'ajaxUpdate'	=> false,
								'columns'		=> array(
									array(
										'id'			=> 'a_ids',
										'class'			=> 'CCheckBoxColumn',
										'selectableRows'=> 50,
										'value'			=> 'IDHelper::uuidFromBinary($data->id, true)',
										'htmlOptions'	=> array('width'=>'20px', 'class' => 'chkArticle'),
									),
									'title',
									'alias',
									// 'description',
									array(
										'name'	=> 'created',
										'value'	=> 'date("Y-m-d", strtotime($data->created))'
									),
									array(
										'header'			=> 'Action',
										'class'				=> 'CButtonColumn',
										'template'=>'{view}{update}{delete}',
										'viewButtonUrl'		=> 'GNRouter::createUrl("'.$this->action->viewUri.'", array("alias" => $data->alias))',
										'deleteButtonUrl'	=> 'GNRouter::createUrl("'.$this->action->deleteUri.'", array("a_id" => IDHelper::uuidFromBinary($data["id"], true)))',
										'updateButtonUrl'	=> 'GNRouter::createUrl("'.$this->action->editUri.'", array("a_id" => IDHelper::uuidFromBinary($data["id"], true)))',
										'buttons'=>array(
											
										),
									),
								),
							)); ?>
							<?php $this->widget('bootstrap.widgets.TbButton', array(
								'label'	=> 'Create',
								'url'	=> GNRouter::createUrl($this->action->createUri),
							)); ?>
							<?php $this->widget('bootstrap.widgets.TbButton', array(
								'htmlOptions'	=> array(
									'class'	=> 'btn-delete-selected-items',
								),
								'label'			=> 'Delete selected items',
							)); ?>
							<?php $this->endWidget(); ?>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</div>