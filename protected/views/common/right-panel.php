<?php 
if(currentUser()->isGuest){
	return;
}
?>
<?php 
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js',
));
?>
<?php 
GNAssetHelper::setBase('myzone_v1');
GNAssetHelper::setPriority(100);

GNAssetHelper::scriptFile('zone.sticker', CClientScript::POS_END);
GNAssetHelper::scriptFile('zone.sticker.notification', CClientScript::POS_END);
GNAssetHelper::scriptFile('zone.sticker.notification.init', CClientScript::POS_END);

?>
<script id="tmplStickerItem" type="text/x-jquery-tmpl">
<?php
$this->renderPartial('application.views.common.notification.tmplSticker');
?>
</script>
<div class="wd-right-chat">
	<div class="wd-pagelet-ticker">
		<div class="wd-pagelet-ticker-block">
			<ul class="wd-pagelet-ticker-content" id='zone-sticker'>
			</ul>
			<?php if (!currentUser()->isGuest):?>
			<?php $this->widget('GNTemplateEngine', array(
				'data'	=> array(
					'url'	=> ZoneRouter::createUrl("/api/user/stickerItems", array(
						'id'	=> currentUser()->hexID
					)),
					'type'	=> 'ajax',
					'responseData'	=> 'res.data'
				), // Dữ liệu request ajax
				'template'	=> array(
					'id'	=> 'tmplSticker', // ID của template, có thể đặt tên bất kỳ, miễn sao đừng trùng
					'path'	=> 'application.views.common.notification.tmplSticker', // Đường dẫn đến template
				),
				'container'	=> array(
					'selector'	=> '#zone-sticker', // selector của container chứa dữ liệu sau khi render
					'type'		=> GNTemplateEngine::ADD_APPEND // Hiện tại hỗ trợ 2 kiểu là ADD_APPEND và ADD_PREPEND
				),
				'scriptPos'	=> GNTemplateEngine::POS_IMME, // Hiện tại hỗ trợ 2 kiểu là POS_IMME (render ngay lập tức) và POS_READY
				'callbacks'	=> array(
					'beforeRender'	=> '//console.log(res);',
					'afterRender'	=> '',
				),
			)); ?>
			<?php endif;?>
		</div>
	</div>
	<?php //$this->renderPartial('application.modules.chat.views.common.client');?>
</div>

