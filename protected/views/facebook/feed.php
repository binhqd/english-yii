<?php
$this->breadcrumbs=array(
	'Facebook'=>array('/facebook'),
	'Feed',
);?>
<h1>Your activity in facebook</h1>
<?php if(!$login): ?> 
<ul>
<?php foreach ($feeds['data'] as $item) { ?>
     <li>
	 	 <?php foreach($item as $key => $val)
	 	 	{
	 	 		if(is_array($val))
	 	 			continue;
				switch ($key) {
			       case 'id':
			       case 'type':
			       case 'icon':
			       case 'caption':
			       case 'created_time':
			       case 'updated_time':			
				   case 'picture':        
			       case 'application':
				   		break;			       		
			       case 'link':
			       		if(isset($item['picture']))
			       		echo '<br/><a href="'. $val. '"><img src="' . $item['picture'] . '"/></a>';
			       		else
			       		{
			       			if(isset($item['name']))
			       				echo '<br/><a href="'. $val. '">' . $item['name'] . '</a>';
			       		}			       		
			       		break;	
					default :
						echo '<br/>' . $val;					 
			    }	
	 	 	}	 	 	
		   ?>
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
echo '<h3>Login Facebook to view your facebook activity.</h3>';
echo '<a href="'.$login.'">';
echo '<img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif" alt="Login"/>';
echo '</a>';
endif; ?>