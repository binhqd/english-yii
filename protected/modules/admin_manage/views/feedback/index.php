<?php $this->widget('ext.bootstrap.widgets.BootBreadcrumbs', array(
	'links'=>array(
		'List feedbacks'
	),
)); ?>
<div style="">

<form method='post' action='<?php echo JLRouter::createUrl('/admin_manage/feedback/massDelete');?>'>
<div class="form-actions">
<?php $this->widget('ext.bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'ok white', 'label'=>'Delete All Checked')); ?>
</div>
<?php $this->widget('ext.bootstrap.widgets.BootGridView', array(
	'dataProvider'	=> $model->search(),
	'pager'			=> $model->search(),
	'template'		=> "{items}",
	'type'			=> 'striped bordered condensed',
	'columns'		=> array(
		array('name'=>'id', 'header'=>'#', 'value' => ''),
		array('name'=>'_c', 'header'=>'', 'value'=>'CHtml::checkBox("cid[]",null,array("value"=>IDHelper::uuidFromBinary($data->id),"id"=>"cid_" . IDHelper::uuidFromBinary($data->id)))', 'type' => 'raw'),
		array('name'=>'name', 'header'=>'Name'),
		array('name'=>'user', 'value' => '($data->user == null) ? "<span style=\'color: #ff0000\'>Guest</span>" : CHtml::link($data->user->username . " ({$data->user->firstname} {$data->user->lastname})", array(\'/dashboard?u=\' . IDHelper::uuidFromBinary($data->user->id)), array(\'target\' => \'_blank\'))', 'type'=>'raw', 'header' => 'User'),
		array('name'=>'screen', 'value'	=>	'JLFeedback::screenSize($data->screen)', 'header'=> 'Screen'),
		array('name'=>'browser', 'value'	=>	'$data->browser', 'header'=> 'Browser'),
		array('name'=>'platform', 'value'	=>	'$data->platform', 'header'=> 'Platform'),
		array('name'=>'created', 'header'=>'Created'),
		array(
			'class'=>'ext.bootstrap.widgets.BootButtonColumn',
			'buttons' => array(
				'delete' => array(
					'url' => 'array("delete","fid"=>IDHelper::uuidFromBinary($data->id))',
				),
				'update'	=> array(
					'visible' => 'false'
				),
				'view' => array(
					'url' => 'array("view","id"=>IDHelper::uuidFromBinary($data->id))',
				),
			),			
			'htmlOptions'=>array('style'=>'width: 60px'),
		),
	),
)); ?>
<div class="form-actions">
    <?php $this->widget('ext.bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'ok white', 'label'=>'Delete All Checked')); ?>
</div>
</form>
</div>

