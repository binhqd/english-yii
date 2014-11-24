<?php 
$user = currentUser();
$this->pageTitle = $article->title;
?>
<?php 
GNAssetHelper::init(array(
'image'		=> 'img',
'css'		=> 'css',
'script'	=> 'js',
));
?>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php GNAssetHelper::setBase('myzone_v1');?>
<?php 
GNAssetHelper::cssFile('pagelet-composer-img-att-content');
GNAssetHelper::cssFile('topinfo-article');
GNAssetHelper::cssFile('comment-box-of-entry');
GNAssetHelper::cssFile('makerpage-objectnode-rightlc');
GNAssetHelper::cssFile('connect-w-network-r');
GNAssetHelper::cssFile('related-articles-r');

GNAssetHelper::scriptFile('zone.photos', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.unveil', CClientScript::POS_HEAD);
GNAssetHelper::scriptFile('all-user-photos', CClientScript::POS_END);
$images = $article->getImages($article->id);

?>
<?php $this->renderPartial('application.modules.photos.views.templates.popup-photo');?>
<?php $this->renderPartial('application.modules.photos.views.templates.popup-photo-content');?>
<?php //$this->renderPartial('//common/user-related', compact('user'));

$this->widget('widgets.node.SlideBar', array(
				'nodeId' => !empty($node['node']['zone_id']) ? $node['node']['zone_id'] : "",
));
?>


<div class="wd-container">
	<div class="wd-center wd-center-content-layout2">
		<div class="wd-right-content">

			<!-- How You're Connected -->
			<?php $this->widget('application.modules.followings.components.widgets.ZoneFollowingHowConnected', array(
				'object_id' => !empty($node['node']['zone_id']) ? $node['node']['zone_id'] : "",
			)); ?>
			<!-- How You're Connected .end -->
			<!-- People’s you may know -->
			<?php $this->widget('application.modules.friends.components.widgets.ZoneFriendsPeopleYouMayKnow'); ?>
			<!-- People’s you may know .end -->
			<!-- People also viewed -->
			<?php $this->renderPartial('application.modules.users.views.elements.people-also-view') ?>
			<!-- People also viewed .end -->
			<!-- YouLook for mobile -->
			<?php $this->renderPartial('application.modules.users.views.elements.youlook-for-mobile') ?>
			<!-- YouLook for mobile .end -->
			
		</div>
		<div class="wd-contain-content">
<!-- header line -->
			<?php
			$this->widget('application.modules.articles.widgets.NamespaceMenuWidget', array(
					'namespaceID' => !empty($node['node']['zone_id']) ? $node['node']['zone_id'] : "",
					'targetNode'=>false,
					'viewPath' => 'application.modules.articles.widgets.views.menu'
				));
			?>
			
<!-- header line .end-->
<!-- left content -->
			<div class="wd-right-main-content">
<!-- person avatar -->
				<div class="wd-makerpage-objectnode-rightlc bbor-solid-2">
					<div class="wd-makerpage-objectnode-rightlc-content">
						<a href="<?php echo ZoneRouter::createUrl('/profile/' . $author->username)?>" class="wd-avatar">
						<img width="54" height="54" alt=""
							src="<?php echo ZoneRouter::CDNUrl('/upload/user-photos/' . IDHelper::uuidFromBinary($author->id, true) . '/fill/54-54/' . $author->profile->image .'?album_id='. IDHelper::uuidFromBinary($author->id, true))?>"> </a>
						<div class="wd-makerpage-objectnode-info">
							<h4>
								<a href="<?php echo ZoneRouter::createUrl('/profile/' . $author->username)?>"><?php echo $author->displayname?></a>
							</h4>

							<?php
							if(!empty($author->profile->status_text)){
							?>
							<p><strong><?php echo $author->profile->status_text;?></strong></p>
							<?php
							}
							?>
		
							
							<?php
							
							if (!empty($author->profile->location)):
								
								$location = ZoneArticleNamespace::model()->nodeInfo($author->profile->location);
								
								if(!empty($location)):
							?>
							<p class="wd-gray-cl-1 mt5">
								<?php echo CHtml::link($location['name'],ZoneRouter::createUrl('/zone/pages/detail',array('id'=>$location['zone_id'])));?>
							</p>
							<?php
								else:
							?>
								<p class="wd-gray-cl-1 mt5">
									<?php echo $author->profile->location;?>
								</p>
							<?php
								endif;
							endif;
							?>
							
						</div>
					</div>
					<div class="wd-makerpage-objectnode-rightlc-action" style="<?php echo (currentUser()->id == $author->id) ? "height:28px;min-height:28px;" : "";?>">
						<div class="wd-makerpage-objn-interaction-status">
							<ul class="wd-user-interaction-status">
								
								<li class="mr15"><a href="<?php echo ZoneRouter::createUrl('/articles/views/index',array('id'=>IDHelper::uuidFromBinary($author->id, true)));?>"><span class="wd-icon-1 wd-icon-contribution">&nbsp;</span><span class="wd-value"><?php echo $countArticle;?></span></a></li>
								<li><a href="<?php echo ZoneRouter::createUrl('/userphotos?uid='.IDHelper::uuidFromBinary($author->id, true));?>"><span class="wd-icon-1 wd-icon-photo">&nbsp;</span><span class="wd-value"><?php echo $totalPhotos;?></span></a></li>
							</ul>
						</div>
						<div class="wd-action-more-buttons wd-action-more-orange-buttons wd_parenttoggle">
							<?php 
							$this->renderPartial('application.modules.users.views.profile._button_action', array(
							'user' => $author,
							)); ?>
						</div>
					</div>
					<span class="wd-makerpage-objectnode-arrowleft"></span>
					<div class="clear"></div>
				</div>
				
				<?php
				if(!empty($relatedArticles)):
				?>
				<div class="wd-related-articles-r bbor-solid-2">
					<h3 class="wd_tt_st_3 bbor-solid-2">Related articles</h3>
					<ul class="wd-related-articles-list">
						<?php foreach($relatedArticles as $key=>$relatedArticle):
						
							$imagesArticle = $relatedArticle->getImages($relatedArticle->id);
							if(!empty($imagesArticle[0])){
								$albumId = $imagesArticle[0]['photo']['album_id'];
							}
						?>
						<li>
							<?php if($relatedArticle->image != null):?>
								<a class="wd-avatar" href="<?php echo GNRouter::createUrl('/article?article_id='.IDHelper::uuidFromBinary($relatedArticle->id,true));?>">
									<img width="54" height="54" alt="" src="<?php echo ZoneRouter::CDNUrl('/');?>/upload/gallery/fill/54-54/<?php echo $relatedArticle->image;?>?album_id=<?php echo $albumId?>">
								</a>
							<?php
							else:
								
							endif;
							?>
							<div class="wd-articles-info">
								<h4>
									<a href="<?php echo GNRouter::createUrl('/article?article_id='.IDHelper::uuidFromBinary($relatedArticle->id,true));?>"><?php echo $relatedArticle->title;?></a>
								</h4>
								<p class="wd-userpost">Written by 
									<a href="<?php echo GNRouter::createUrl('/profile/'.$relatedArticle->author->user->username);?>"><?php echo $relatedArticle->author->user->displayname;?></a>
								</p>
							</div>
						</li>
						
						<?php
						endforeach;
						?>
					</ul>
				</div>
				<?php
					
				endif;
				?>
				
				<div class="wd-connect-w-network-r bbor-solid-2">
					<h3 class="wd_tt_st_3">Connect with network</h3>
					<ul class="wd-connect-w-network-rl">
						<li>
							<a href="#" class="wd-connect-network-item"><span class="wd-icon-network-47 wd-icon-face"></span><span class="wd-value">1281</span><span class="wd-title">likes</span></a>
						</li>
						<li>
							<a href="#" class="wd-connect-network-item"><span class="wd-icon-network-47 wd-icon-tweets"></span><span class="wd-value">1856</span><span class="wd-title">tweets</span></a>
						</li>
						<li>
							<a href="#" class="wd-connect-network-item"><span class="wd-icon-network-47 wd-icon-plus"></span><span class="wd-value">1260</span><span class="wd-title">plus</span></a>
						</li>
					</ul>
				</div>
<!-- person avatar .end-->
			</div>
<!-- left content .end -->
<!-- main content -->
			<div class="wd-main-content">
				
				
				<div class="wd-article-detail-content">
					<div class="wd-topinfo-article mb20">
						<?php 
						if(!empty($images[0])): 
							$album_id = $images[0]['photo']['album_id'];
						?>
						<div class="wd-photo-article">
							<img width="105" height="105" alt=""
										src="<?php echo ZoneRouter::CDNUrl("/")."/upload/gallery/fill/105-105/{$images[0]['photo']['image']}";?>?album_id=<?php echo $album_id;?>">
						</div>
						<?php endif;?>
						<div class="wd-topinfo-article-r">
							<h2 class="wd_tt_3 mb10"><?php echo $article->title;?></h2>
							<p>
							written by <a href="<?php echo ZoneRouter::createUrl('/profile/' . $author->username)?>"><?php echo $author->displayname?></a>
							</p>
							<p class="wd-posted-date timeago" data-title="<?php echo  date(DATE_ISO8601,strtotime($article->created));?>">posted on Wed 3rd Apr 2013</p>
							
						</div>
					</div>
					<div class="wd-article-entry">
						<?php echo $article->content;?>
					</div>
					
					<div class="wd-pp-like-content">
							<?php
							$this->widget('widgets.like.Like', array(
								'objectId'		=> IDHelper::uuidFromBinary($article->id),
								'actionLike'	=> GNRouter::createUrl('like/liked/liked'),
								'actionUnlike'	=> GNRouter::createUrl('like/liked/like'),
								'modelObject'	=> 'application.modules.like.models.LikeObject',
								'modelStatistic'=> 'application.modules.like.models.LikeStatistic',
								'classUnlike'	=>'wd-like-bt',
								'classLike'		=>'wd-like-bt wd-liked-bt',
							));
				
							?>
					</div>
					
					<?php
					
					if(!empty($images) && count(($images))>1){
					?>
					<div class="wd-pagelet-composer-img-att-content">
						
						<ul class="wd-list-photopost">
							<?php foreach($images as $key=>$image):
							$album_id = $image['photo']['album_id'];
							?>
							<li class="wd-itemphotopost">
								<div class="wd-photoload">
									<a class="lnkViewPhotoDetail" 
										href="<?php echo GNRouter::createUrl('/photos/viewPhoto',array('photo_id'=>$image['photo']['id'],"album_id"=>$album_id));?>"  
										photo_id="<?php echo $image['photo']['id']?>" 
										album_id="<?php echo $album_id;?>"
										type="gallery"
										filename="<?php echo $image['photo']['image'];?>">
									<img width="41" height="41" alt=""
										src="<?php echo ZoneRouter::CDNUrl("/")."/upload/gallery/fill/41-41/{$image['photo']['image']}";?>?album_id=<?php echo $album_id;?>">
									</a>
								</div>
							</li>
							<?php endforeach;?>
						</ul>
						
					</div>
					<?php
					}
					?>
					
					<div class="clear"></div>
				</div>
				
				
				<div class="wd-comment-box-ofEntry pullViewAllComment<?php echo IDHelper::uuidFromBinary($article->id)?>" id="comments">
					<div class="wd-comment-box-title bbor-solid-2 " id = "wd-comment-viewall<?php echo IDHelper::uuidFromBinary($article->id);?>">
						<?php
							$countComment = ZoneComment::model()->countComments(IDHelper::uuidFromBinary($article->id));
							
						?>
						<h2 class="wd_tt_n2"><strong id="numberComment"><?php echo $countComment;?></strong> <label id="textNumberComment"><?php echo ($countComment == 1) ? "Comment" : "Comments";?></label></h2>
					</div>
					
					<div class="wd-commentstory-ofEntry">
						<?php
						$this->widget('widgets.comment.Comment', array(
							'objectId'		=> IDHelper::uuidFromBinary($article->id),
							'viewMore'		=>true,
							'loadJsTimeAgo'	=>true,
							'alwayShow'=>true,
							'limit'			=>5,
							'viewItemPath'=>'application.views.common.comment.item_article_detail',
							'viewFormPath'=>'application.views.common.comment.form_article_detail',
						));
						?>

					</div>
					
					
				</div>
			</div>
<!-- main content .end-->
		</div>
		<div class="clear"></div>
	</div>
</div>

<script>
// When the Document Object Model is ready
jQuery(document).ready(function(){
	if(window.location.hash == "#comments"){
	
		var catTopPosition = jQuery('#comments').offset().top;

		jQuery('html, body').animate({scrollTop:catTopPosition}, 'slow');
	}
	
});
</script>
