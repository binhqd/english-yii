<?php

$fullName = "";
$objectId = "";
$albumID = IDHelper::uuidFromBinary($album->id, true);


$avatar = "";
if (!empty($album->owner)) {
	$owner = $album->owner;
	
	$fullName = $owner->displayname;
	$ownerID = IDHelper::uuidFromBinary($owner->id, true);
	$userLikeID = IDHelper::uuidFromBinary($userLike->id, true);
	$avatar = ZoneRouter::CDNUrl("/upload/user-photos/{$userLikeID}/fill/40-40/{$userLike->profile->image}?album_id={$userLikeID}");
	
	$countComment = ZoneComment::model()->countComments(IDHelper::uuidFromBinary($this->activity->id));
	
?>

<li class="wd-stream-story" id="article-item" album_id="<?php echo $albumID;?>" style="display: block">
	<div class="wd-story-content pullViewAllComment<?php echo IDHelper::uuidFromBinary($this->activity->id,true);?>">
		<div class="wd-head-storycontent">
			
			<a href="<?php echo  ZoneRouter::createUrl('/profile/'.$userLike->username);?>"
				class="wd-avatar">
				<img src="<?php echo $avatar;?>" alt="<?php echo $userLike->displayname;?>" height="40" width="40" />
			</a>
			
			<div class="wd-head-storyinnercontent">
				<h3 class="wd_tt_n1">
				
					<?php
					$photo_id = null;
					$nameImage = null;
					if(!empty($images[0])){
						$photo_id = $images[0]['photo']['id'];
						$nameImage = $images[0]['photo']['image'];
					}
					?>
					<a href="<?php echo ZoneRouter::createUrl('/profile/'.$userLike->username);?>" class="wd-name">
						<?php echo $userLike->displayname;?>
					</a>
					like album 
					<a href="<?php echo ZoneRouter::createUrl("/resource/album?album_id={$albumID}");?>" ><?php echo $album->title;?></a>
				</h3>
				
				
				<p class="wd-date-post timeago" data-title="<?php echo date(DATE_ISO8601, strtotime($this->activity->created));?>"> </p>
			</div>
			<span class="wd-arrow-down"></span>
			<div class="clear"></div>
		</div>
		
		<div class="wd-like-content bbor-solid-1 custom-like-activities">

			
			<div class="wd-likearticle-content wd-stream-gallery mt10">
				<ul class="wd-stream-gallery-51 multiple-screen" >
					<?php
					$countImages = count($images);
					
					switch($countImages){
						case 1:
							$image = $images[0];
							$urlPhotoPopup = GNRouter::createUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$albumID));
							$imagesize = null;
							if(file_exists("upload/gallery/".$image['photo']['image'])){
								$imagesize  = @getimagesize("http://".$_SERVER["SERVER_NAME"]."/upload/gallery/".$image['photo']['image']);
							}
							echo '<li class="wd-element-1">';
							if(!empty($imagesize[0]) && $imagesize[0]>=648 ){
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$albumID.'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/thumbs/648-10000/'.$image['photo']['image'].'?album_id='.$albumID.'"  /></a>';
							}else{
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$albumID.'" photo_id="'.$image['photo']['id'].'"><img style="width:auto" src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/thumbs/648-10000/'.$image['photo']['image'].'?album_id='.$albumID.'"  /></a>';
							}
							echo '</li>';
							
						break;
						case 2:
							foreach($images as $key=>$image){
								
								$urlPhotoPopup = GNRouter::createUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$albumID));
								$imagesize = null;
								
								$class = "";
								if($key == 0)  $class = "cmr19";
								echo '<li class="wd-element-2 mb9 '.$class.'">';
								
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$albumID.'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/319-319/'.$image['photo']['image'].'?album_id='.$albumID.'"  /></a>';
								
								echo '</li>';
							}
						break;
						case 3:
							foreach($images as $key=>$image){
								
								$urlPhotoPopup = GNRouter::createUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$albumID));
								$imagesize = null;
								if(file_exists("upload/gallery/".$image['photo']['image'])){
									$imagesize  = @getimagesize("http://".$_SERVER["SERVER_NAME"]."/upload/gallery/".$image['photo']['image']);
								}
								$class = "";
								if($key != $countImages -1 )  $class = "cmr15";
								echo '<li class="wd-element-3 mb9 '.$class.'">';
								
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$albumID.'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/230-230/'.$image['photo']['image'].'?album_id='.$albumID.'"  /></a>';
								
								echo '</li>';
							}
						break;
						case 4:
							foreach($images as $key=>$image){
								
								$urlPhotoPopup = GNRouter::createUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$albumID));
								$imagesize = null;
								if(file_exists("upload/gallery/".$image['photo']['image'])){
									$imagesize  = @getimagesize("http://".$_SERVER["SERVER_NAME"]."/upload/gallery/".$image['photo']['image']);
								}
								$class = "";
								if($key != $countImages -1 )  $class = "cmr12";
								echo '<li class="wd-element-4 mb9  '.$class.'">';
								
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$albumID.'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/155-155/'.$image['photo']['image'].'?album_id='.$albumID.'"  /></a>';
								
								echo '</li>';
							}
						break;
						case 5:
							foreach($images as $key=>$image){
								
								$urlPhotoPopup = GNRouter::createUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$albumID));
								$imagesize = null;
								if(file_exists("upload/gallery/".$image['photo']['image'])){
									$imagesize  = @getimagesize("http://".$_SERVER["SERVER_NAME"]."/upload/gallery/".$image['photo']['image']);
								}
								$class = "";
								if($key != $countImages -1 )  $class = "cmr10";
								
								if($key==0){
									echo '<li class="wd-element-m5 mb9  '.$class.'">';
									echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$albumID.'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/319-319/'.$image['photo']['image'].'?album_id='.$albumID.'"  /></a>';
									echo '</li>';
								}else{
									echo '<li class="wd-element-5 mb9  '.$class.'">';
									echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$albumID.'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/155-155/'.$image['photo']['image'].'?album_id='.$albumID.'"  /></a>';
									echo '</li>';
								}
								
								
							}
						break;
						
						default:
							foreach($images as $key=>$image){
								
								$urlPhotoPopup = GNRouter::createUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$albumID));
								$imagesize = null;
								if(file_exists("upload/gallery/".$image['photo']['image'])){
									$imagesize  = @getimagesize("http://".$_SERVER["SERVER_NAME"]."/upload/gallery/".$image['photo']['image']);
								}
								$class = "";
								if($key != $countImages -1 )  $class = "cmr10";
								echo '<li class="wd-element-3 mb9 '.$class.'">';
								
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$albumID.'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/230-230/'.$image['photo']['image'].'?album_id='.$albumID.'"  /></a>';
								
								echo '</li>';
							}
						break;
					}
					?>

				</ul>
			</div>
		
		</div>
		
		<div class="wd-action-storycontent">
			<?php
			
			$this->widget('widgets.like.Like', array(
				'objectId'=>IDHelper::uuidFromBinary($this->activity->id),
				'actionLike'=> GNRouter::createUrl('like/liked/liked'),
				'actionUnlike'=> GNRouter::createUrl('like/liked/like'),
				'modelObject'	=> 'application.modules.like.models.LikeObject',
				'modelStatistic'	=> 'application.modules.like.models.LikeStatistic',
				'classUnlike'=>'wd-like-bt',
				'classLike'=>'wd-like-bt wd-liked-bt',

			));

			?>
			
			<?php	Yii::app()->controller->renderPartial('//common/activity/_viewAllComment',array('activityID'=>IDHelper::uuidFromBinary($this->activity->id,true),'limit'=>3,'countComment'=>$countComment)) ?>
			<div class="clear"></div>
		</div>
		
		
		<?php

		$this->widget('widgets.comment.Comment', array(
			'objectId'=>IDHelper::uuidFromBinary($this->activity->id,true),
			'viewMore'=>false,
			'countComment'=>$countComment,
			'loadJsTimeAgo'=>false,
			'limit'=>3,
			'viewItemPath'=>'widgets.comment.views.item'
		));
		?>
		
	</div>
</li>
<?php
}else{
	// dump($album->attributes);
}
?>