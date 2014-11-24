<?php
//if(currentUser()->isGuest){
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js',
	));
	GNAssetHelper::setBase('justlook');
	GNAssetHelper::cssFile('reset');
	GNAssetHelper::cssFile('common');
	
	GNAssetHelper::setBase('application.modules.user.assets');
	GNAssetHelper::scriptFile('jlbd.fix.login-full', CClientScript::POS_END);
	?>
	<div style="display:none;" id="container-login-full-hide">
		<?php 
			Yii::app()->controller->renderPartial("application.modules.user.views.login.login-full");
		?>
	</div>
<?php
//}
?>
<a href="#jlbd-login-container" id="wd-auto-show-fancy" style="display:none;">Auto Show Fancybox</a>