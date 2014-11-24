<?php 
if (!isset($title)) $title = "JustLook Message";
if (!isset($type)) $type = JLController::MESSAGE_INFO;
if (!isset($content)) $content = "Content for message";

$status = array(
	1	=> 'info',
	2	=> 'error',
	3	=> 'warning',
	4	=> 'message',
);

GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));

GNAssetHelper::setBase('justlook');
GNAssetHelper::cssFile('message-layout');
?>

<div class="wd-main-content">
	<div class="wd-section">
		<div class="wd-message-layout">
			<span class="wd-icon-mess wd-icon-mess-<?php echo $status[$type]?>">Icon</span>
			<div class="wd-mess-contain">
				<h2 class="wd-title"><?php echo $title?></h2>
				<?php echo $content;?>
			</div>
		</div>
	</div>	
</div>
<div class="clear"></div>