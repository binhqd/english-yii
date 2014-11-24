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

?>

<li class="wd-stream-story js-article-id-<?php echo IDHelper::uuidFromBinary($article->id,true); ?> <?php echo (!empty($hide)) ? "fade-item-article": "";?>" id="article-item" article_id="<?php echo IDHelper::uuidFromBinary($article->id,true);?>" style="display:<?php echo (!empty($hide)) ? "none": "block";?>">
	<div class="wd-story-content pullViewAllComment<?php echo $articleId;?>">
		<div class="wd-head-storycontent">			
			<a href="<?php echo ( IDHelper::uuidFromBinary($article->author->user->id,true) == currentUser()->hexID ) ? GNRouter::createUrl('/profile') : GNRouter::createUrl('/profile/'.$article->author->user->username);?>" class="wd-avatar required_login login_complete"><img
				src="<?php echo $avatar;?>" alt="<?php echo $fullName;?>"
				height="40" width="40" />
			</a>
			<?php 
				GNAssetHelper::setBase('myzone_v1');
				GNAssetHelper::scriptFile('zone.articles', CClientScript::POS_BEGIN);
				GNAssetHelper::registerScript('articles', 'zone.articles.initLinks($(".js-article-delete"));', CClientScript::POS_READY);
			?>
			<div class="wd-setting-streamstory wd_parenttoggle floatR mr10">
				<span class="wd-icon-16 wd-icon-setting-stream-story wd_toggle_bt wd-tooltip-hover" original-title="More functions..."></span>
				<div class="wd-setting-stream-content wd_toggle">
					<span class="wd-uparrow-1"></span>
					<ul>
						<!--<li><a href="javascript:void(0)">Report spam</a></li>-->
						<?php if ($article->isOwner) : ?>
						<li><a href="javascript:void(0)" 
							class="js-article-delete" 
							data-container=".js-article-id-<?php echo IDHelper::uuidFromBinary($article->id,true); ?>" 
							data-url="<?php echo ZoneRouter::createUrl('/articles/delete', array('article_id'=>IDHelper::uuidFromBinary($article->id,true))); ?>">Delete</a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
			<div class="wd-head-storyinnercontent">
				<h3 class="wd_tt_n1">
					
					<a href="<?php echo ( IDHelper::uuidFromBinary($article->author->user->id,true) == currentUser()->hexID ) ? GNRouter::createUrl('/profile') : GNRouter::createUrl('/profile/'.$article->author->user->username);?>" class="wd-name required_login login_complete"><?php echo $fullName;?></a>
					
					<?php
					
					
					
					$node = null;
					if(!empty($article->namespace)){
						$node = $article->namespace->nodeInfo(IDHelper::uuidFromBinary($article->namespace->holder_id,true));
						echo "posted new article for ";
						echo CHtml::link($node['name'],ZoneRouter::createUrl('/zone/pages/detail',array('id'=>$node['zone_id'])));
					}else{
						echo "posted new article ";
					}
					
					
						
						
					
						
						
					$removeBorderDash = "border:none";
					
					
					?>
					
					<!--<a href="#">article</a>.-->
				</h3>
				<p class="wd-date-post timeago" data-title="<?php echo  date(DATE_ISO8601,strtotime($article->created));?>">10 minutes ago</p>
			</div>
			<span class="wd-arrow-down"></span>
			<div class="clear"></div>
		</div>
		<div class="wd-addnew-content bbor-solid-1">
			<?php if(!empty($images)):?>
				<?php
				$image = $images[0];
				$urlPhotoPopup = ZoneRouter::CDNUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],'article_id'=>IDHelper::uuidFromBinary($article->id,true)));
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
					<a href="<?php echo ( IDHelper::uuidFromBinary($article->author->user->id,true) == currentUser()->hexID ) ? GNRouter::createUrl('/profile') : GNRouter::createUrl('/profile/'.$article->author->user->username);?>" >
						<?php echo $fullName;?>
					</a>
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
	
	// dump($article->attributes,false);
}
?>