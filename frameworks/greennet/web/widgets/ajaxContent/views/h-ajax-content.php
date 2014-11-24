<?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js',
));
GNAssetHelper::setPriority(98);
GNAssetHelper::setBase('greennet.web.widgets.ajaxContent.assets');
if ($supportJQueryTemplate) GNAssetHelper::scriptFile('jquery.tmpl.min', CClientScript::POS_HEAD);
GNAssetHelper::scriptFile('script.linkpager', CClientScript::POS_HEAD);
GNAssetHelper::scriptFile('script.ajax.content', CClientScript::POS_HEAD);
?>

<div id="<?php echo $divID; ?>" class="js-h-ajax-content">
	<?php if (!empty($this->templateID) && !empty($this->firstContent)):?>
	<div class="js-h-ajax-content-item" ref="<?php echo $url; ?>">div>
	<?php endif;?>
	<?php if ($viewFile) : ?>
	<div class="js-h-ajax-content-item" ref="<?php echo $url; ?>">
		<?php $this->renderFile($viewFile, $viewParams); ?>
	</div>
	<?php endif; ?>
</div>

<?php if (!empty($this->templateID) && !empty($this->firstContent)):?>
	<script language="javascript">
	var firstContent = <?php echo @CJSON::encode($this->firstContent);?>;
	$.tmpl($('#<?php echo $this->templateID;?>'), firstContent).appendTo($('#<?php echo $divID; ?>'));
	</script>
<?php else:?>
	<?php
		$jsLoadMoreSuccess = '';
		if ($supportLoadMore !== false && isset($supportLoadMore['jsSuccess']))
			$jsLoadMoreSuccess = $supportLoadMore['jsSuccess'];
	?>
	<?php if ($scriptPosition == HAjaxContent::POS_READY) :?>
		<?php
			$script = "var ajaxcontent = new jQuery.HAjaxContent.Widget('{$divID}', {$jsonOptions}, function(self){{$jsInit}}, function(self){{$jsBeforeSend}}, function(self){{$jsComplete}}, function(self, response, \$newpage){{$jsSuccess}}, function(self, response, \$newpage){{$jsLoadMoreSuccess}});";
			Yii::app()->clientScript->registerScript("HAjaxContent_{$divID}", $script, CClientScript::POS_READY);
		?>
	<?php else:?>
		<script type="text/javascript">
		var ajaxcontent = new jQuery.HAjaxContent.Widget('<?php echo $divID; ?>', <?php echo $jsonOptions; ?>, function(self){<?php echo $jsInit; ?>}, function(self){<?php echo $jsBeforeSend; ?>}, function(self){<?php echo $jsComplete; ?>}, function(self, response, $newpage){<?php echo $jsSuccess; ?>}, function(self, response, $newpage){<?php echo $jsLoadMoreSuccess; ?>});
		</script>
	<?php endif;?>
<?php endif;?>

