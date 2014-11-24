<?php $this->widget('ext.bootstrap.widgets.BootBreadcrumbs', array(
	'links'=>array(
		'Feedbacks'	=> JLRouter::createUrl('/admin_manage/feedback'),
		$feedback['name']
	),
)); ?>
<?php $this->widget('ext.bootstrap.widgets.BootAlert'); ?>
<?php
Yii::app()->user->getFlash('success', '<strong>Well done!</strong> You successfully read this important alert message.');
Yii::app()->user->getFlash('info', '<strong>Heads up!</strong> This alert needs your attention, but it\'s not super important.');
Yii::app()->user->getFlash('warning', '<strong>Warning!</strong> Best check yo self, you\'re not looking too good.');
Yii::app()->user->getFlash('error', '<strong>Oh snap!</strong> Change a few things up and try submitting again.');
?>
<div class='content'>
<?php $this->widget('bootstrap.widgets.BootDetailView', array(
	'data'	=> $feedback,
	'attributes'=>array(
		array('name'=>'name', 'label'=>'Name'),
		array('label'=>'User', 'value' => $feedback->user == null ? "<span style='color: #ff0000'>Guest</span>" : CHtml::link($feedback->user->username . " ({$feedback->user->firstname} {$feedback->user->lastname})", array('/dashboard?u=' . IDHelper::uuidFromBinary($feedback->user->id)), array('target' => '_blank')), 'type'=>'raw'),
		array('name'=>'created', 'label'=>'Sent on:', 'value' => date("d/m/Y H:i:s", strtotime($feedback->created))),
		array('name'=>'platform', 'label'=>'Operating System'),
		array('name'=>'browser', 'label'=>'Browser'),
		array('name'=>'screen', 'label'=>'Screen', 'value' => JLFeedback::screenSize($feedback->screen)),
		array('name'=>'content', 'label'=>'Feedback Content', 'value' => str_replace("\n" , "<br/>", $feedback->content), 'type' => 'raw'),
		array('name'=>'url', 'label'=>'URL', 'value' => CHtml::link($feedback->url, $feedback->url, array('target' => '_blank')), 'type'=>'raw'),
		array('label'=>'Snapshot', 'value' => CHtml::link('Click here to view snapshot', array('/admin_manage/feedback/viewSnapshot','id'=> IDHelper::uuidFromBinary($feedback->id)), array('target' => '_blank')), 'type'=>'raw'),
		array('label'=>'Canvas', 'value' => CHtml::link('Click here to view canvas', array('/admin_manage/feedback/viewCanvas','id'=> IDHelper::uuidFromBinary($feedback->id)), array('target' => '_blank')), 'type'=>'raw')
	),
)); ?>

<?php $this->widget('bootstrap.widgets.BootButton', array(
	'icon'=>'remove', 'label' => 'Delete',
	'url'	=> JLRouter::createUrl('/admin_manage/feedback/delete', array('fid' => IDHelper::uuidFromBinary($feedback->id)))
)); 
?>
</div>

