<?php

$fullName = "";
$objectId = "";


$avatar = "";
if (!empty($owner)) {
	$albumID = IDHelper::uuidFromBinary($album->id, true);
	$ownerID = IDHelper::uuidFromBinary($owner->id, true);
	
	$fullName = $owner->displayname;
	$ownerID = IDHelper::uuidFromBinary($owner->id, true);
	$avatar = ZoneRouter::CDNUrl("/upload/user-photos/{$ownerID}/fill/40-40/{$owner->profile->image}?album_id={$ownerID}");
	

	
	$node = null;
	$type = "user";
	if(!empty($images[0])){
		$node = ZoneArticleNamespace::model()->nodeInfo($images[0]['photo']['object_id']);
		$type = "gallery";
	}
	$countImages = count($images);
	
	$countComment = ZoneComment::model()->countComments($albumID);
	
	?>

<li class="wd-stream-story" id="article-item" album_id="<?php echo $albumID;?>" style="display: block">
	<div class="wd-story-content pullViewAllComment<?php echo $albumID;?>">
		<div class="wd-head-storycontent">
			<a href="<?php echo ($ownerID == currentUser()->hexID ) ? ZoneRouter::createUrl('/profile') : ZoneRouter::createUrl('/profile/'.$owner->username);?>"
				class="wd-avatar">
				<img src="<?php echo $avatar;?>" alt="<?php echo $fullName;?>" height="40" width="40" />
			</a>

			<div class="wd-head-storyinnercontent">
				<h3 class="wd_tt_n1">
					<a href="<?php echo ($ownerID == currentUser()->hexID ) ? GNRouter::createUrl('/profile') : GNRouter::createUrl('/profile/'.$owner->username);?>" class="wd-name">
					<?php echo $fullName;?>
					</a>
					<?php
					if(!empty($node) && $ownerID != $node['zone_id']):
					?>
					added <?php echo ($countImages == 1) ? "photo" : "photos";?> 
					for 
					<a href="<?php echo !empty($node) ? ZoneRouter::createUrl('/zone/pages/detail',array('id'=>$node['zone_id'])) : "";?>"><?php echo !empty($node) ? $node['name']: "";?></a>
					<?php
					endif;
					

					$albumNamespace = null;
					if(!empty($album->AlbumNamespace)){
						$albumNamespace = IDHelper::uuidFromBinary($album->AlbumNamespace->holder_id,true);
					}
					
					?>

					<!--<a href="#">article</a>.-->
				</h3>
				<p class="wd-date-post timeago" data-title="<?php echo date(DATE_ISO8601, strtotime($album->created));?>"></p>
			</div>
			<span class="wd-arrow-down"></span>
			<div class="clear"></div>
		</div>

		<div class="wd-addnew-content bbor-solid-1">
			<p><?php echo $album->title;?></p>
			<div class="wd-stream-gallery mt10">
				<ul class="wd-stream-gallery-51 multiple-screen" >
					<?php
					
					
					switch($countImages){
						case 1:
							$image = $images[0];
							$urlPhotoPopup = ZoneRouter::CDNUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$image['photo']['album_id']));
							$imagesize = null;
							if(file_exists("upload/gallery/".$image['photo']['image'])){
								$imagesize  = @getimagesize("http://".$_SERVER["SERVER_NAME"]."/upload/gallery/".$image['photo']['image']);
							}
							echo '<li class="wd-element-1">';
							if(!empty($imagesize[0]) && $imagesize[0]>=648 ){
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" type="'.$type.'" class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$image['photo']['album_id'].'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/thumbs/648-10000/'.$image['photo']['image'].'?album_id='.$image['photo']['album_id'].'"  /></a>';
							}else{
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" type="'.$type.'"  class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$image['photo']['album_id'].'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/thumbs/648-10000/'.$image['photo']['image'].'?album_id='.$image['photo']['album_id'].'"  /></a>';
							}
							echo '</li>';
							
						break;
						case 2:
							foreach($images as $key=>$image){
								$urlPhotoPopup = ZoneRouter::CDNUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$image['photo']['album_id']));
								$imagesize = null;
								
								$class = "";
								if($key == 0)  $class = "mr9";
								echo '<li class="wd-element-2 mb9 '.$class.'">';
								
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" type="'.$type.'"  class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$image['photo']['album_id'].'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/319-319/'.$image['photo']['image'].'?album_id='.$image['photo']['album_id'].'"  /></a>';
								
								echo '</li>';
							}
						break;
						case 3:
							foreach($images as $key=>$image){
								
								$urlPhotoPopup = ZoneRouter::CDNUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$image['photo']['album_id']));
								$imagesize = null;
								if(file_exists("upload/gallery/".$image['photo']['image'])){
									$imagesize  = @getimagesize("http://".$_SERVER["SERVER_NAME"]."/upload/gallery/".$image['photo']['image']);
								}
								$class = "";
								if($key != $countImages -1 )  $class = "mr9";
								echo '<li class="wd-element-3 mb9 '.$class.'">';
								
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" type="'.$type.'"  class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$image['photo']['album_id'].'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/230-230/'.$image['photo']['image'].'?album_id='.$image['photo']['album_id'].'"  /></a>';
								
								echo '</li>';
							}
						break;
						case 4:
							foreach($images as $key=>$image){
								
								$urlPhotoPopup = ZoneRouter::CDNUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$image['photo']['album_id']));
								$imagesize = null;
								if(file_exists("upload/gallery/".$image['photo']['image'])){
									$imagesize  = @getimagesize("http://".$_SERVER["SERVER_NAME"]."/upload/gallery/".$image['photo']['image']);
								}
								$class = "";
								if($key != $countImages -1 )  $class = "mr9";
								echo '<li class="wd-element-4 mb9  '.$class.'">';
								
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" type="'.$type.'"  class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$image['photo']['album_id'].'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/155-155/'.$image['photo']['image'].'?album_id='.$image['photo']['album_id'].'"  /></a>';
								
								echo '</li>';
							}
						break;
						case 5:
							foreach($images as $key=>$image){
								
								$urlPhotoPopup = ZoneRouter::CDNUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$image['photo']['album_id']));
								$imagesize = null;
								if(file_exists("upload/gallery/".$image['photo']['image'])){
									$imagesize  = @getimagesize("http://".$_SERVER["SERVER_NAME"]."/upload/gallery/".$image['photo']['image']);
								}
								$class = "";
								if($key != $countImages -1 )  $class = "mr9";
								


								if($key==0){
									echo '<li class="wd-element-m5 mb9  '.$class.'">';
									echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" type="'.$type.'"  class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$image['photo']['album_id'].'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/319-319/'.$image['photo']['image'].'?album_id='.$image['photo']['album_id'].'"  /></a>';
									echo '</li>';
								}else{
									if($key % 2 == 0 ) $class = "";

									echo '<li class="wd-element-5 mb9  '.$class.'">';
									echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" type="'.$type.'"  class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$image['photo']['album_id'].'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/155-155/'.$image['photo']['image'].'?album_id='.$image['photo']['album_id'].'"  /></a>';
									echo '</li>';
								}
								
								
							}
						break;
						
						default:
							if (is_array($images))
							foreach($images as $key=>$image){
								$urlPhotoPopup = ZoneRouter::CDNUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'album_id'=>$image['photo']['album_id']));
								$imagesize = null;
								if(file_exists("upload/gallery/".$image['photo']['image'])){
									$imagesize  = @getimagesize("http://".$_SERVER["SERVER_NAME"]."/upload/gallery/".$image['photo']['image']);
								}
								$class = "";
								if(($key+1) % 3 == 0 ) $class = "";
								else $class = "mr9";
								

								echo '<li class="wd-element-3 mb9 '.$class.'">';
								
								echo '<a href="'.$urlPhotoPopup.'" filename="'.$image['photo']['image'].'" type="'.$type.'"  class="wd-thumb-img lnkViewPhotoDetail" style="text-align:center" album_id="'.$image['photo']['album_id'].'" photo_id="'.$image['photo']['id'].'"><img src="'.ZoneRouter::CDNUrl('/').'/upload/gallery/fill/230-230/'.$image['photo']['image'].'?album_id='.$image['photo']['album_id'].'"  /></a>';
								
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
				'objectId'=>$albumID,
				'nodeId'=>($albumNamespace == null) ? 0 : $albumNamespace,
				'actionLike'=> GNRouter::createUrl('like/liked/liked'),
				'actionUnlike'=> GNRouter::createUrl('like/liked/like'),
				'modelObject'	=> 'application.modules.like.models.LikeObject',
				'modelStatistic'	=> 'application.modules.like.models.LikeStatistic',
				'classUnlike'=>'wd-like-bt',
				'classLike'=>'wd-like-bt wd-liked-bt',

			));
			?>
			<?php	Yii::app()->controller->renderPartial('//common/activity/_viewAllComment',array('activityID'=>$albumID,'limit'=>3,'countComment'=>$countComment)) ?>
			<div class="clear"></div>
		</div>
		<?php

		$this->widget('widgets.comment.Comment', array(
			'objectId'=>$albumID,
			'countComment'=>$countComment,
			'viewMore'=>false,
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
