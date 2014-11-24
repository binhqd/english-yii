<style type="text/css">
.inputSQL {clear:both;}

button.btn, input[type="submit"].btn {
	clear: both;
	display: block;
	margin: 10px 0px 0px 540px;
}
.grid-view {
	padding-top: 0px;
}
.jl_column {font-size:12px;}
.jl_column p {margin:3px;}
.stacktrace {display:none;}
</style>

<?php $this->widget('ext.bootstrap.widgets.BootBreadcrumbs', array(
	'links'=>array(
		'Error & Warning Logs'
	),
)); ?>

<?php /** @var BootActiveForm $form */
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js',
));
GNAssetHelper::setBase('justlook', "photo_business");
GNAssetHelper::cssFile('jquery.fancybox-1.3.4');

GNAssetHelper::scriptFile('jquery.fancybox-1.3.4.pack', CClientScript::POS_END);
?>

<div style=" float:left;">
	<form method='post' action='<?php echo JLRouter::createUrl('/admin_manage/logs/massDelete');?>'>
	<div class="form-actions">
	<?php $this->widget('ext.bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'ok white', 'label'=>'Delete All Checked')); ?>
	</div>
	<?php if ($pages->pageCount>1) { ?>
	<div class="pagination">
		<?php
			$to		=	$pages->offset+1;
			$from	=	$pages->offset+$pages->limit;
			
			if($from >=$item_count)	$from = $item_count;
			$this->widget('CLinkPager', array(
				'cssFile'=> '',
				'pages' => $pages,
				'header' => '',
				'footer' => '',
				'firstPageLabel' => 'First',
				'prevPageLabel' => 'Prev',
				'nextPageLabel' => 'Next',
				'lastPageLabel' => 'Last',
				'cssFile'=>''
			));
		?>
	</div>
	<?php
	}
	?>
	<div class="grid-view">	
		<?php 
		Yii::import("application.extensions.browser.CBrowserComponent");
		$browserComponent = new CBrowserComponent();
		?>
		<?php $this->widget('ext.bootstrap.widgets.TbGridView', array(
			'dataProvider'	=> $logs,
			//'pager'			=> $model->search(),
			'template'		=> "{items}",
			'type'			=> 'striped bordered condensed',
			'columns'		=> array(
				array('name'=>'id', 'header'=>'#'),
				array('name'=>'_c', 'header'=>'<input type=\'checkbox\' id=\'cbxCheckall\'/>', 'value'=>'CHtml::checkBox("cid[]",null,array("class" => "checkitem", "value"=> $data->_id->{\'$id\'},"id"=>"cid_" . $data->_id->{\'$id\'}))', 'type' => 'raw'),
				array('name'=>'message', 'header'=>'Message', 'value' => 'JLLog::model()->renderMessage($data)', 'type' => 'raw', 'htmlOptions'	=> array('class' => 'jl_column')),
				array('name'=>'logtime', 'header'=> 'Date', 'value' => 'date("d/m/Y H:i:s", $data->logtime)', 'htmlOptions'	=> array('class' => 'jl_column')),
				array('name'=>'category', 'header'=> 'Category', 'htmlOptions'	=> array('class' => 'jl_column')),
				array('name'=>'user', 'header'=> 'user', 'htmlOptions'	=> array('class' => 'jl_column')),
				array('name'=>'ip', 'header'=> 'IP', 'htmlOptions'	=> array('class' => 'jl_column')),
				array(
					'class'=>'ext.bootstrap.widgets.BootButtonColumn',
					'buttons' => array(
						'delete' => array(
							'url' => 'array("delete","id"=>"$data->id")',
						),
						'update'	=> array(
							'visible' => 'false'
						),
						'view' => array(
							'url' => 'array("view","id"=>"$data->id")',
						),
					),			
					'htmlOptions'=>array('style'=>'width: 60px', 'class' => 'jl_column'),
				),
			),
		)); ?>
		
	</div>
	<?php if ($pages->pageCount>1) { ?>
	<div class="pagination">
		
		<?php
			$to		=	$pages->offset+1;
			$from	=	$pages->offset+$pages->limit;
			if($from >=$item_count)	$from = $item_count;
			$this->widget('CLinkPager', array(
				'cssFile'=> '',
				'pages' => $pages,
				'header' => '',
				'footer' => '',
				'firstPageLabel' => 'First',
				'prevPageLabel' => 'Prev',
				'nextPageLabel' => 'Next',
				'lastPageLabel' => 'Last',
				'cssFile'=>''
			));
		?>
	</div>
	<?php
	}
	?>
	<div class="form-actions">
	    <?php $this->widget('ext.bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'ok white', 'label'=>'Delete All Checked')); ?>
	</div>
	</form>
</div>

<script language="javascript">
$(document).ready(function() {
	$('#btnRemoveGoogleBot').click(function() {
		$.ajax({
			url : homeURL + '/admin_manage/logs/removeGoogleBot',
			success : function() {
				
			}
		});
	});

	$('#cbxCheckall').click(function() {
		if (this.checked) {
			$('input.checkitem').attr('checked', 'checked');
		} else {
			$('input.checkitem').removeAttr('checked');
		}
	});

	$('.showstack').click(function() {
		var _parent = $(this).closest('td');
		_parent.find('.stacktrace').show();
		$(this).hide();
		return false;
	});
	$('.hidestack').click(function() {
		var _parent = $(this).closest('td');
		_parent.find('.stacktrace').hide();
		_parent.find('.showstack').show();
		return false;
	});
});
</script>