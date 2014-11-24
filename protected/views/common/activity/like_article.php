<?php

$fullName = "";

$articleId = IDHelper::uuidFromBinary($article->id,true);
$avatar = "";
if(!empty($article->author->user)){
	$fullName = $userLike->displayname;
	$strIdUserLike = IDHelper::uuidFromBinary($userLike->id,true);
	
	$avatar = ZoneRouter::CDNUrl('/')."/upload/user-photos/{$strIdUserLike}/fill/40-40/{$userLike->profile->image}?album_id={$strIdUserLike}";

	$images = $article->getImages($article->id);
	
	$countComment = ZoneComment::model()->countComments(IDHelper::uuidFromBinary($article->id));
?>

<li class="wd-stream-story" id="article-item">
	<div class="wd-story-content pullViewAllComment<?php echo IDHelper::uuidFromBinary($article->id,true);?>">
		<div class="wd-head-storycontent">
			<a href="<?php echo GNRouter::createUrl('/profile/'.$userLike->username);?>" class="wd-avatar"><img
				src="<?php echo $avatar;?>" alt="<?php echo $fullName;?>"
				height="40" width="40" />
			</a>
			<div class="wd-head-storyinnercontent">
				
				<h3 class="wd_tt_n1">
					<a href="<?php echo ( IDHelper::uuidFromBinary($article->author->user->id,true) == currentUser()->hexID ) ? GNRouter::createUrl('/profile') : GNRouter::createUrl('/profile/'.$article->author->user->username);?>" class="wd-name"><?php echo $fullName;?></a>
					
					<?php
					
					$node = null;
					if(!empty($article->namespace)){
						$node = $article->namespace->nodeInfo(IDHelper::uuidFromBinary($article->namespace->holder_id,true));
						echo " liked an article of ";
						echo CHtml::link($node['name'],ZoneRouter::createUrl('/zone/pages/detail',array('id'=>$node['zone_id'])));
					} else {
						echo " liked an article ";
					}
					?>
					
					<!--<a href="#">article</a>.-->
				</h3>
				<p class="wd-date-post timeago" data-title="<?php echo  date(DATE_ISO8601,strtotime($this->activity->created));?>"></p>
			</div>
			<span class="wd-arrow-down"></span>
			<div class="clear"></div>
		</div>
		<div class="wd-addnew-content bbor-solid-1">
			<?php if(!empty($images)):?>
				<?php
				$image = $images[0];
				$urlPhotoPopup = GNRouter::createUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'article_id'=>IDHelper::uuidFromBinary($article->id,true)));
				?>
				<a class="wd-addnew-image lnkViewPhotoDetail" href="<?php echo $urlPhotoPopup;?>" photo_id="<?php echo $image['photo']['id'];?>" album_id="<?php echo $image['photo']['album_id'];?>" 
					filename="<?php echo $image['photo']['image'];?>">
					<img src="<?php echo ZoneRouter::CDNUrl('/').'/upload/gallery/fill/165-165/'.$image['photo']['image'].'?album_id='.$image['photo']['album_id'];?>"  height="70" width="70">
				</a>
			<?php endif;?>
			<div class="wd-addnew-text">
				<div class="wd-nameposter">
					<h3 class="wd_tt_n1"><a href="<?php echo GNRouter::createUrl('/article?article_id='.IDHelper::uuidFromBinary($article->id,true));?>" class="wd-title"><?php echo $article->title;?></a> </h3>
					<p class="wd-user-post">written by 
					<?php echo CHtml::link($article->author->user->displayname,ZoneRouter::createUrl('/profile/'.$article->author->user->username));?>
					</p>
				</div>
				<div class="wd-disc">
					<p><?php echo JLStringHelper::char_limiter_word(strip_tags($article->content,""),100);?></p>
				</div>
			</div>
		</div>
		
		<div class="wd-action-storycontent">
			<?php
			
			$this->widget('widgets.like.Like', array(
				// 'objectId'=>IDHelper::uuidFromBinary($this->activity->id),
				'objectId'=>IDHelper::uuidFromBinary($article->id),
				'actionLike'=> GNRouter::createUrl('like/liked/liked'),
				'actionUnlike'=> GNRouter::createUrl('like/liked/like'),
				'modelObject'	=> 'application.modules.like.models.LikeObject',
				'modelStatistic'	=> 'application.modules.like.models.LikeStatistic',
				'classUnlike'=>'wd-like-bt',
				'classLike'=>'wd-like-bt wd-liked-bt',

			));

			?>
			<?php	Yii::app()->controller->renderPartial('//common/activity/_viewAllComment',array('activityID'=>IDHelper::uuidFromBinary($article->id,true),'limit'=>3,'countComment'=>$countComment)) ?>
			<div class="clear"></div>
		</div>
		
		
		<?php

		$this->widget('widgets.comment.Comment', array(
			// 'objectId'=>IDHelper::uuidFromBinary($this->activity->id),
			'objectId'=>IDHelper::uuidFromBinary($article->id,true),
			'viewMore'=>false,
			'loadJsTimeAgo'=>false,
			'countComment'=>$countComment,
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