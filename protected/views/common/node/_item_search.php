<?php
	
	$images = ZoneInstanceRender::getResourceImage(array(
		'zone_id'=>$node['zone_id'],
		'image'=>array()
	));
	$totalArticle = ZoneArticle::model()->countArticlesByObject(IDHelper::uuidToBinary($node['zone_id']));
		
	$totalImages = ZoneResourceImage::model()->countImages(IDHelper::uuidToBinary($node['zone_id']));
	
	$owner = ZoneInstance::initNode($node['zone_id'])->getOwner(); 
	$owner = GNUser::model()->findByPk(IDHelper::uuidToBinary($owner['zone_id']));
	// dump($images);
	if(!empty($images['image']['photo']['image'])){
		$height = "auto";
		$urlImage = ZoneRouter::CDNUrl('/upload/gallery/thumbs/196-10000/'.$images['image']['photo']['image'].'?album_id='.$images['image']['photo']['album_id']);
	}else{
		$height = "415";
		if($node['label']==ZoneUser::$userNode){
			$height = '275';
		}
		$urlImage = GNRouter::createUrl('/site/placehold',array('t'=>'196x215-282828-969696'));

	}
	if(empty($images['image']['ratio'])){
		$ratio = 0;
	}else{
		$ratio = $images['image']['ratio'];
	}
	
	// if($ratio == 0){
		// $height = 415;
	// }else{
		// $height = floor(196/$ratio) + 180;
		
	// }
	
	
	
	
	
?>
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
	<div class="wd-streamstory-viewall-action-composer wd-item-easy " style="opacity:0;height:<?php echo $height;?>px" >
		<div class="wd-streamstory-viewall-action-content pb10 <?php if ($isFollowing) echo 'wd-follow-green'; ?> js-following-switch_color" data-token="object_<?php echo $node['zone_id']; ?>" <?php if($node['label']==ZoneUser::$userNode) echo 'style="border-bottom: none;"'?>>
			<a href="<?php echo GNRouter::createUrl('/zone/pages/detail/',array('id'=>$node['zone_id']));?>" class="wd-photo-ce">
				<img src="<?php echo $urlImage;?>" alt="<?php echo (!empty($images['image']['title'])) ? $images['image']['title'] : "";?>"  />
			</a>
			
			<div class="wd-streamstory-viewall-action-right">
				<h2 class="wd_tt_st_4"  style="max-width:196px;"><a href="<?php echo GNRouter::createUrl('/zone/pages/detail/',array('id'=>$node['zone_id']));?>"><?php echo $node['name'];?></a></h2>
				
				<?php if($node['label']!=ZoneUser::$userNode) : ?>
					<?php if ($isFollowing) : ?>
						<a href="javascript:void(0)" class="wd-icon-1 wd-icon-following-node floatR wd-tooltip-hover js-following-request" data-action="unfollow" data-token="object_<?php echo $node['zone_id']; ?>" title="Unfollow">Unfollow</a>
					<?php else : ?>
						<a href="javascript:void(0)" class="wd-icon-1 wd-icon-follow-node floatR wd-tooltip-hover js-following-request" data-action="follow" data-token="object_<?php echo $node['zone_id']; ?>" title="Follow">Follow</a>
					<?php endif; ?>
				<?php endif;?>
				<p class="wd-gray-cl-1"><?php echo $node['label'];?></p>
			</div>
		</div>
		
		<?php if($node['label']!=ZoneUser::$userNode) : ?>
		
		<div class="wd-streamstory-viewall-action-content-more wd-general-userpost">
			<ul class="wd-statuscontent-left">
				<li><a href="<?php echo GNRouter::createUrl('/followings/list/followers', array('token'=>'object_'.$node['zone_id'])); ?>"><span class="wd-value js-follower-count" data-token="object_<?php echo $node['zone_id']; ?>"><?php echo $countFollowers; ?></span><span class="wd-tt js-follower-count-text" data-token="object_<?php echo $node['zone_id']; ?>">Follower<?php echo $countFollowers==1?'':'s'; ?></span></a></li>
				<li><a href="<?php echo GNRouter::createUrl('/articles/views/index',array('id'=>$node['zone_id']));?>"><span class="wd-value"><?php echo $totalArticle;?></span><span class="wd-tt"><?php echo ($totalArticle == 1) ? "Article" : "Articles";?></span></a></li>
				<li class="mr0"><a href="<?php echo GNRouter::createUrl('/photos/views/index',array('id'=>$node['zone_id']));?>"><span class="wd-value"><?php echo $totalImages;?></span><span class="wd-tt"><?php echo ($totalImages == 1) ? "Photo" : "Photos";?></span></a></li>
			</ul>
			<?php
			if(!empty($owner)){
			?>
			<div class="wd-bg-line">
				<div class="wd-userpost-right">
					<p class="wd-uerpost-name"><span class="wd-tt">created by</span>
						<a href="<?php echo ZoneRouter::createUrl('profile/'.$owner->username);?>" class="wd-uername"><?php echo $owner->displayname;?></a></p>
					<a href="<?php echo ZoneRouter::createUrl('profile/'.$owner->username);?>" class="wd-avatar">
						<img src="<?php echo ZoneRouter::CDNUrl('/upload/user-photos/'.IDHelper::uuidFromBinary($owner->id,true).'/fill/34-34/'.$owner->profile->image).'?album_id='.IDHelper::uuidFromBinary($owner->id,true);?>" alt="" height="34" width="34"/>
					</a>
				</div>
			</div>
			<?php
			}
			?>
		</div>
		
		<?php endif;?>
		
	</div>
	