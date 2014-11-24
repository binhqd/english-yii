<?php 
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));
?>
<style>
.page_not_found {
	width: 958px;
	margin:auto;
}
.page_not_found .message {
	text-align: center;
	padding: 40px;
	font-size: 18px;
	font-weight: bold;
}
</style>
<?php $this->beginContent('//layouts/main'); ?>
<div class='page_not_found'>
	<div class='message'>
		<?php echo Yii::t("justlook", "The page you are browsing is currently not exists. Please try another page on the navigator")?>
		<?php if (in_array(APPLICATION_SCOPE, array("SITE_MANAGER", "TEMPLATE_MANAGER")) && $_SERVER['HTTP_HOST'] != JLTL_PREVIEW_DOMAIN) : ?>
		<br/><br/><a href="<?php echo JLRouter::createAbsoluteUrl('/sites/page/create', array('alias' => $_GET['alias']))?>" class="dialog_link"><?php echo Yii::t("cpanel", "Click here")?></a> <?php echo Yii::t("cpanel", "to Create new page")?>
		<?php endif; ?>
		<?php GNAssetHelper::setBase("jlbackend"); ?>
		<?php GNAssetHelper::cssFile('jquery.fancybox-1.3.4'); ?>
	</div>
	<div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".dialog_link").fancybox({	
			//Hàm này để sử dụng mở tất cả các dialog của hệ thống, từ dialog của cpanel
			'scrolling'		: 'no',
			'type'		: 'iframe',
			'height'		: '100%'
		});
	});
</script>
<?php $this->endContent(); ?>

