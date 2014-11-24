<?php

$fullName = "";
$objectId = "";
$articleId = IDHelper::uuidFromBinary($article->id,true);
$avatar = "";
if(!empty($article->author->user) && $article->data_status == ZoneArticle::DATA_STATUS_NORMAL){
	$fullName = $article->author->user->displayname;
	$objectId = IDHelper::uuidFromBinary($article->author->user->id,true);
	
	$avatar = ZoneRouter::CDNUrl('/')."/upload/user-photos/{$objectId}/fill/40-40/{$article->author->user->profile->image}?album_id={$objectId}";


	$images = $article->getImages($article->id,1);
	

	$countComment = ZoneComment::model()->countComments($articleId);
	$token = md5(uniqid(32));
?>

<li class="wd-stream-story <?php echo $token;?> js-article-id-<?php echo IDHelper::uuidFromBinary($article->id,true); ?>"  id="article-item" article_id="<?php echo IDHelper::uuidFromBinary($article->id,true);?>">
	<a href="<?php echo ( IDHelper::uuidFromBinary($article->author->user->id,true) == currentUser()->hexID ) ? GNRouter::createUrl('/profile') : GNRouter::createUrl('/profile/'.$article->author->user->username);?>" class="wd-main-avatar">
			<img
				src="<?php echo $avatar;?>" alt="<?php echo $fullName;?>"
				height="40" width="40" />
	</a>
	<div class="wd-story-content pullViewAllComment<?php echo $articleId;?>">
		<div class="wd-head-storycontent">
			<div class="wd-setting-streamstory wd_parenttoggle">
				<?php if ($article->isOwner) : ?>
				<span class="wd-icon-16 wd-icon-setting-stream-story wd_toggle_bt wd-tooltip-hover" title="More functions..."></span>
				<?php endif; ?>
				<div class="wd-setting-stream-content wd_toggle">
					<span class="wd-uparrow-1"></span>
					<ul>
						<!--<li><a href="javascript:void(0)">Report spam</a></li>-->
						<li><a href="javascript:void(0)"
							class="js-article-delete" 
							data-container=".js-article-id-<?php echo IDHelper::uuidFromBinary($article->id,true); ?>" 
							data-url="<?php echo ZoneRouter::createUrl('/articles/delete', array('article_id'=>IDHelper::uuidFromBinary($article->id,true))); ?>">Delete</a></li>
						<?php 
						// if($article->author->user->id == currentUser()->id):
							// echo '<li>';
							// echo CHtml::link('Delete',Yii::app()->createUrl('/activities/admin/delete',array('id'=>IDHelper::uuidFromBinary($article->id,true))),array(
								// 'class'=>'deletePost',
								// 'item'=>$token
							// ));
							// echo '</li>';
						// endif;
						?>
					</ul>
				</div>
			</div>
			<p class="wd-timepost-new timeago" data-title="<?php echo date(DATE_ISO8601, strtotime($article->created));?>"></p>
			<div class="wd-head-storyinnercontent">
				<h3 class="wd_tt_n1">
					<a href="<?php echo ( IDHelper::uuidFromBinary($article->author->user->id,true) == currentUser()->hexID ) ? GNRouter::createUrl('/profile') : GNRouter::createUrl('/profile/'.$article->author->user->username);?>" class="wd-name"><?php echo $fullName;?></a>
					<?php
					$node = null;
					if(!empty($article->namespace)){
						$node = $article->namespace->nodeInfo(IDHelper::uuidFromBinary($article->namespace->holder_id,true));
						echo "wrote an article for ";
						echo CHtml::link($node['name'],ZoneRouter::createUrl('/zone/pages/detail',array('id'=>$node['zone_id'])));
					}else{
						echo "wrote a new article ";
					}
					?>
				</h3>
			</div>
			<div class="clear"></div>
		</div>
		<div class="wd-addnew-content">
			<?php if(!empty($images)):?>
				<?php
				$image = $images[0];
				$urlPhotoPopup = GNRouter::createUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'article_id'=>IDHelper::uuidFromBinary($article->id,true)));
				?>
				<a class="wd-addnew-image lnkViewPhotoDetail" href="<?php echo $urlPhotoPopup;?>" photo_id="<?php echo $image['photo']['id'];?>" album_id="<?php echo $image['photo']['album_id'];?>" 
					filename="<?php echo $image['photo']['image'];?>">
					<img src="<?php echo ZoneRouter::CDNUrl('/').'/upload/gallery/fill/100-100/'.$image['photo']['image'].'?album_id='.$image['photo']['album_id'];?>" >
				</a>
			<?php endif;?>
			
			<div class="wd-addnew-text">
				<div class="wd-nameposter">
					<h3 class="wd_tt_n1"><a href="<?php echo GNRouter::createUrl('/article?article_id='.IDHelper::uuidFromBinary($article->id,true));?>" class="wd-title"><?php echo $article->title;?></a> </h3>
				</div>
				<div class="wd-disc">
					<div class="article-description"><?php echo JLStringHelper::char_limiter_word(strip_tags($article->content,""),200);?></div>
				</div>
			</div>
		</div>
		<div class="wd-action-storycontent">
			<?php
			
			$this->widget('widgets.like.Like', array(
				'objectId'=>$articleId,
				'nodeId'=>($node == null) ? 0 : $node['zone_id'],
				'actionLike'=> GNRouter::createUrl('like/liked/liked'),
				'actionUnlike'=> GNRouter::createUrl('like/liked/like'),
				'modelObject'	=> 'application.modules.like.models.LikeObject',
				'modelStatistic'	=> 'application.modules.like.models.LikeStatistic',
				'classUnlike'=>'wd-like-bt',
				'classLike'=>'wd-like-bt wd-liked-bt',
				
			));

			?>
			
			
			
			<?php	Yii::app()->controller->renderPartial('//common/activity/_viewAllComment',array('activityID'=>$articleId,'countComment'=>$countComment,'limit'=>3)) ?>
			
			
			<div class="clear"></div>
		</div>
		<?php
		
		$this->widget('widgets.comment.Comment', array(
			'objectId'=>$articleId,
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
	//dump($article->attributes,false);
}
?>