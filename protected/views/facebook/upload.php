<?php
$this->breadcrumbs=array(
	'Facebook'=>array('/facebook'),
	'Upload',
);?>
<?php if (Yii::app()->user->hasFlash('success')): ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php 
Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css');
 ?>
<div style="position: relative;">
    <div id="Notification">
    </div>
</div>
<script type="text/javascript"> 
 $(document).ready(function() {
    $('#Notification').jnotifyAddMessage({
        text: '<?php echo Yii::app()->user->getFlash('success'); ?>'
    });
});
</script>
<?php endif; ?>
 
<h1>Image Upload</h1>
<?php if(!$login): ?> 
<div class="form">
<?php echo $form; ?>
</div>
<?php else: ?>
<?php  
$login = $fb->getLoginUrl(
						array( 
							//'display' => 'popup',
			                'scope' => 'user_photos,user_videos,manage_pages,email,offline_access,publish_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown', 
							)
);
$key = 'fb_' . Yii::app()->params['fbAppId'] . '_state';
$session = isset( $_SESSION[$key])? $_SESSION[$key] : null ;
?>
<script type='text/javascript'>
window.fbAsyncInit = function() {FB.init({appId : '<?php echo $session; ?>',session : <?php echo json_encode($session);?>,status : true,cookie : true,xfbml : true});FB.Event.subscribe('auth.login', function() {window.location.reload();});};(function() {var e = document.createElement('script');e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';e.async = true;document.getElementById('fb-root').appendChild(e);}());
</script>
<?php 
echo '<h3>Login Facebook to upload.</h3>';
echo '<a href="'.$login.'">';
echo '<img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif" alt="Login"/>';
echo '</a>';
endif; ?>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_img',
	'template'=>"{items}\n{pager}",
)); ?>