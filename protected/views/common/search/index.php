<?php
GNAssetHelper::init(array(
	'image' => 'img',
	'css' => 'css',
	'script' => 'js'
));

GNAssetHelper::setBase('application.modules.followings.assets');
GNAssetHelper::scriptFile('script.followings', CClientScript::POS_BEGIN);
GNAssetHelper::registerScript('followings', '$.Followings.initLinks($(".js-following-request"));', CClientScript::POS_READY);
?>
<?php
$offsetTop = 0;
if(!empty(Yii::app()->session['offsetTop'])) $offsetTop = Yii::app()->session['offsetTop'];
if($page == 1 ){
	$offsetTop = 0;
	Yii::app()->session['offsetTop'] = 0;
}
if($type == "landingpage" && $page == 1){
	if(!empty($result['nodes'][0])){
		$node = $result['nodes'][0];
		$image = ZoneInstanceRender::getResourceImage(array(
			'zone_id'=>$result['nodes'][0]['zone_id'],
			'image'=>array()
		));
		$images = ZoneResourceImage::model()->getPhotos($result['nodes'][0]['zone_id'],4);
		$totalArticle = ZoneArticle::model()->countArticlesByObject(IDHelper::uuidToBinary($node['zone_id']));

		$totalImages = ZoneResourceImage::model()->countImages(IDHelper::uuidToBinary($node['zone_id']));
		
		$owner = ZoneInstance::initNode($node['zone_id'])->getOwner(); 
		$owner = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($owner['zone_id']));
?>
	<div class="wd-streamstory-viewall-action-composer stamp wd-item-large" style="top:0px;opacity:0; ">
		<div class="wd-topleft-person">
			<div class="wd-person-img">
				<a class="wd-main-image" href="<?php echo GNRouter::createUrl('/zone/pages/detail/',array('id'=>$node['zone_id']));?>">
				
					<?php if(!empty($image['image']['photo']['image'])):?>
						<img src="<?php echo ZoneRouter::CDNUrl('/');?>/upload/gallery/fill/214-214/<?php echo $image['image']['photo']['image'];?>?album_id=<?php echo $image['image']['photo']['album_id'];?>" alt="<?php echo $image['image']['photo']['title'];?>"  />
					<?php else:?>
						<img src="<?php echo GNRouter::createUrl('/site/placehold',array('t'=>'214x214-282828-969696'));?>" alt="" />
					<?php endif;?>
					
				</a>
				<ul class="wd-gallery-1">
					<?php
					foreach($images as $cnt=>$img){
					?>
						
						<li class="wd-<?php echo ($cnt<=1) ? "mlb":"ml";?>-img <?php echo ($cnt==0) ? "wd-first-elm":"";?>">
							<a href="<?php echo GNRouter::createUrl('/zone/pages/detail/',array('id'=>$node['zone_id']));?>" class="wd-thumb-img">
								<img src="<?php echo ZoneRouter::CDNUrl("/upload/gallery/fill/101-101/" . $img['photo']['image'])?>?album_id=<?php echo $node['zone_id'];?>" title="<?php echo $img['photo']['title'];?>"/> 
							</a>
						</li>
					<?php
					}
					?>
				</ul>
			</div>
			<?php
			$isFollowing = false;
			if (!currentUser()->isGuest) {
				currentUser()->attachBehavior('UserFollowing', 'application.modules.followings.components.behaviors.GNUserFollowingBehavior'); // Attach behavior following for user
				$isFollowing = currentUser()->isFollowing(IDHelper::uuidToBinary($node['zone_id']));
				currentUser()->detachBehavior('UserFollowing');
			}
			Yii::import('application.modules.followings.models.ZoneFollowing');
			$countFollowers = ZoneFollowing::model()->countFollowers(IDHelper::uuidToBinary($node['zone_id']));
			?>
			<div class="wd-view-person <?php if ($isFollowing) echo 'wd-follow-green'; ?> js-following-switch_color" data-token="object_<?php echo $node['zone_id']; ?>">
				<h2 class="wd_tt_1">
					<a href="<?php echo GNRouter::createUrl('/zone/pages/detail/',array('id'=>$node['zone_id']));?>">
						<?php echo $node['name'];?>
					</a>
				</h2>
				<?php if ($isFollowing) : ?>
					<a href="javascript:void(0)" class="wd-icon-1 wd-icon-following-node floatR wd-tooltip-hover js-following-request" data-action="unfollow" data-token="object_<?php echo $node['zone_id']; ?>" title="Unfollow">Unfollow</a>
				<?php else : ?>
					<a href="javascript:void(0)" class="wd-icon-1 wd-icon-follow-node floatR wd-tooltip-hover js-following-request" data-action="follow" data-token="object_<?php echo $node['zone_id']; ?>" title="Follow">Follow</a>
				<?php endif; ?>
				<p class="wd-gray-cl-1 mt5"><?php echo $node['label'];?></p>
			</div>
			<div class="wd-streamstory-viewall-action-content-more wd-general-userpost">
				<div class="wd-userpost-right ">
					
					<p class="wd-uerpost-name"><span class="wd-tt">created by</span>
						<a href="<?php echo ZoneRouter::createUrl('/profile/'.$owner->username);?>" class="wd-uername"><?php echo $owner->displayname;?></a>
					</p>
					<?php if(!empty($owner->profile)):?>
					<a href="<?php echo ZoneRouter::createUrl('/profile/'.$owner->username);?>" class="wd-avatar">
						<img src="<?php echo ZoneRouter::CDNUrl('/upload/user-photos/'.IDHelper::uuidFromBinary($owner->id,true).'/fill/49-49/'.$owner->profile->image).'?album_id='.IDHelper::uuidFromBinary($owner->id,true);?>" alt="" height="49" width="49"/>
					</a>
					<?php endif;?>
				</div>
				<ul class="wd-statuscontent-left">
					<li><a href="<?php echo GNRouter::createUrl('/followings/list/followers', array('token'=>'object_'.$node['zone_id'])); ?>"><span class="wd-value js-follower-count" data-token="object_<?php echo $node['zone_id']; ?>"><?php echo $countFollowers; ?></span><span class="wd-tt js-follower-count-text" data-token="object_<?php echo $node['zone_id']; ?>">Follower<?php echo $countFollowers==1?'':'s'; ?></span></a></li>
					<li><a href="<?php echo GNRouter::createUrl('/articles/views/index',array('id'=>$result['nodes'][0]['zone_id']));?>"><span class="wd-value"><?php echo $totalArticle;?></span><span class="wd-tt"><?php echo ($totalArticle == 1) ? "Article" : "Articles";?></span></a></li>
					<li class="mr0"><a href="<?php echo GNRouter::createUrl('/photos/views/index',array('id'=>$result['nodes'][0]['zone_id']));?>"><span class="wd-value"><?php echo $totalImages;?></span><span class="wd-tt"><?php echo ($totalImages == 1) ? "Photo" : "Photos";?></span></a></li>
				</ul>
			</div>
		</div>
		<?php
		$this->renderPartial('application.views.common.user.people_interacted');
		?>
		
	</div>
<?php		
	}
}else{
	// todo
}
if(!empty($result['articles'])){
	// $tmpHeightArticle = 0;
	
	foreach($result['articles'] as $key=>$article){
		// if($article->title!=null){
			$images = $article->getImages($article->id);
			$strObjId = IDHelper::uuidFromBinary($article->id,true);
			$imagePrimary = "";
			if($article->image!=null){
				$imagePrimary = ZoneRouter::CDNUrl("/")."/upload/gallery/fill/101-101/{$article->image}?album_id={$strObjId}";
			}else{
				
				if(!empty($images)) $imagePrimary = ZoneRouter::CDNUrl("/")."/upload/gallery/fill/101-101/{$images[0]['photo']['image']}?album_id={$images[0]['photo']['album_id']}";
			}
			$totalComment = ZoneComment::model()->countComments(IDHelper::uuidFromBinary($article->id,true));
			$totalLike = LikeStatistic::countLike(IDHelper::uuidFromBinary($article->id,true));
			
			// if($offsetTop != 0){
				if($type == "landingpage" && $key == 0 && $page == 1){
					$offsetTop = $offsetTop + 470;
				}else{
					if($page == 1 && $key == 0) $offsetTop = 0;
					else $offsetTop = $offsetTop + 220;
					
					
				}
			// }
			
			Yii::app()->session['offsetTop'] = $offsetTop;
	?>
				<div class="wd-streamstory-viewall-action-composer stamp wd-item-large" style="top:<?php echo $offsetTop;?>px;opacity:0;left:20px; position:absolute">
					<div class="wd-streamstory-viewall-action-content">
						<?php
						if($imagePrimary != ""){
						?>
						<a href="<?php echo GNRouter::createUrl('/article?article_id=' . IDHelper::uuidFromBinary($article->id,true));?>" class="wd-photo"><img src="<?php echo $imagePrimary;?>" alt="" height="101" width="101"></a>
						<?php
						}
						?>
						
						<div class="wd-streamstory-viewall-action-right">
							<h2 class="wd_tt_st_4"><a href="<?php echo GNRouter::createUrl('/article?article_id=' . IDHelper::uuidFromBinary($article->id,true));?>"><?php echo $article->title;?></a></h2>
							<p class="wd-postdate">posted on <label data-title="<?php echo  date(DATE_ISO8601,strtotime($article->created));?>" class="timeago">Wed 10th Apr 2013</label></p>
							<p class="wd-postcontent">
									<?php 
										echo JLStringHelper::char_limiter_word(strip_tags($article->content,""),200);
										
									?>
							<p>
						</div>
					</div>
					<div class="wd-streamstory-viewall-action-content-more">
						<ul class="wd-statuscontent-left">
							<li><a href="<?php echo GNRouter::createUrl('/article?article_id=' . IDHelper::uuidFromBinary($article->id,true));?>#likes"><span class="wd-value"><?php echo $totalLike;?></span><span class="wd-tt"><?php echo ($totalLike == 1) ? "Like" : "Likes";?></span></a></li>
							<li><a href="<?php echo GNRouter::createUrl('/article?article_id=' . IDHelper::uuidFromBinary($article->id,true));?>#comments"><span class="wd-value"><?php echo $totalComment;?></span><span class="wd-tt"><?php echo ($totalComment == 1) ? "Comment" : "Comments";?></span></a></li>
						</ul>
						<?php
						if(!empty($article->author->user)){
						?>
						<div class="wd-userpost-right">
							<p class="wd-uerpost-name"><span class="wd-tt">written by</span>
								<a href="<?php echo GNRouter::createUrl('/profile/'.$article->author->user->username);?>" class="wd-uername">
									<?php echo $article->author->user->displayname;?>
								</a>
							</p>
							<a href="<?php echo GNRouter::createUrl('/profile/'.$article->author->user->username);?>" class="wd-avatar">
								<img src="<?php echo ZoneRouter::CDNUrl('/');?>/upload/user-photos/<?php echo IDHelper::uuidFromBinary($article->author->user->id,true);?>/fill/34-34/<?php echo $article->author->user->profile->image;?>?album_id=<?php echo IDHelper::uuidFromBinary($article->author->user->id,true);?>" alt="" height="34" width="34">
							</a>
						</div>
						<?php
						}
						?>
					</div>
				</div>
<?php

		
		// }
	}
}



if(!empty($result['nodes'])){
	foreach($result['nodes'] as $key=>$node){
		if($type == "landingpage" && $key==0){
			
		}else{
			$this->renderPartial('application.views.common.node._item_search',array(
				'node'=>$node,
				'page'=>$page
			));
		}

	}
}
?>
