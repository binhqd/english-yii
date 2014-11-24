<?php
$this->breadcrumbs=array(
	'Facebook'=>array('/facebook'),
	'Friend',
);?>
<h1>Facebook Friends</h1>
<?php if(!$login): ?> 
<ul>
<?php foreach ($friends['data'] as $item) { ?>
     <li>
     	<a href="http://www.facebook.com/profile.php?id=<?php echo $item['id']  ?>">
	 	<img class="profileimage" name="" src="https://graph.facebook.com/<?php echo $item['id']; ?>/picture?type=normal"  alt="<?php echo $item['name'] ?>" />
	 	<?php echo $item['name'] ?>
	 	</a>
	 </li> 
<?php } ?>
</ul>
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
echo '<h3>Login Facebook to view your facebook friend.</h3>';
echo '<a href="'.$login.'">';
echo '<img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif" alt="Login"/>';
echo '</a>';
endif; ?>