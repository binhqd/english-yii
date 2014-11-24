<?php
	$images = $article->getImages($article->id, 1);
	
	$imagePrimary = "";
	if($article->image!=null){
		$imagePrimary = GNRouter::createUrl("/")."/upload/gallery/fill/101-101/{$article->image}";
	}else{
		
		if(!empty($images)) $imagePrimary = ZoneRouter::CDNUrl("/")."/upload/gallery/fill/101-101/{$images[0]['photo']['image']}?album_id={$images[0]['photo']['album_id']}";
		// else $imagePrimary = GNRouter::createUrl('/site/placehold',array('t'=>'148x148-282828-969696'));
	}
	$totalComment = ZoneComment::model()->countComments(IDHelper::uuidFromBinary($article->id,true));
	$totalLike = LikeStatistic::countLike(IDHelper::uuidFromBinary($article->id,true));
?>
<li class="wd-streamstory-lo2-item js-article-id-<?php echo IDHelper::uuidFromBinary($article->id,true); ?>" id="article-item">
	<div class="wd-streamstory-viewall-action-composer">
		<div class="wd-streamstory-viewall-action-content">
			<?php
			if($imagePrimary != ""){
			?>
			<a href="<?php echo GNRouter::createUrl('/article?article_id=' . IDHelper::uuidFromBinary($article->id,true));?>" class="wd-photo"><img src="<?php echo $imagePrimary;?>" alt="" height="101" width="101"></a>
			<?php
			}
			?>
			<div class="wd-streamstory-viewall-action-right">
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
				<h2 class="wd_tt_st_4"><a href="<?php echo GNRouter::createUrl('/article?article_id=' . IDHelper::uuidFromBinary($article->id,true));?>"><?php echo $article->title;?></a></h2>
				<p class="wd-postdate">posted on <label data-title="<?php echo  date(DATE_ISO8601,strtotime($article->created));?>" class="timeago">Wed 10th Apr 2013</label></p>
				<p class="wd-postcontent">
						<?php 
							echo JLStringHelper::char_limiter_word(strip_tags($article->content,""),200);
							
						?>
				<p>
			</div>
			<div class="clear"></div>
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
				<a href="<?php echo GNRouter::createUrl('/profile/'.$article->author->user->username);?>" class="wd-avatar"><img src="<?php echo ZoneRouter::CDNUrl('/');?>/upload/user-photos/<?php echo IDHelper::uuidFromBinary($article->author->user->id,true);?>/fill/34-34/<?php echo $article->author->user->profile->image;?>?album=<?php echo IDHelper::uuidFromBinary($article->author->user->id,true);?>" alt="" height="34" width="34"></a>
			</div>
			<?php
			}
			?>
		</div>
	</div>
</li>
