<?php

$images = $article->getImages($article->id);
?>
<li class="<?php echo ($countRow == $key+1 ) ? "bdbno": "";?>">
	<?php
	if($article->image != null){
	?>
		<a class="wd-avatar" href="<?php echo GNRouter::createUrl('/article?article_id='.IDHelper::uuidFromBinary($article->id,true));?>">
			<img width="54" height="54" alt="" src="<?php echo GNRouter::createUrl('/');?>/upload/gallery/fill/54-54/<?php echo $article->image;?>">
		</a>
	<?php
	}else{
		if(!empty($images)){
			foreach($images as $keyImage=>$image){
				if($keyImage==0){
	?>
				<a class="wd-avatar">
					<img width="54" height="54" alt="" src="<?php echo GNRouter::createUrl('/');?>/upload/gallery/fill/54-54/<?php echo $image['photo']['image'];?>?album_id=<?php echo $image['photo']['album_id'];?>">
				</a>
	<?php
				}
			}
		}else{
	?>
		<a class="wd-avatar" href="<?php echo GNRouter::createUrl('/article?article_id='.IDHelper::uuidFromBinary($article->id,true));?>">
			<img width="54" height="54" alt="" src="<?php echo GNRouter::createUrl('/site/placehold',array('t'=>'50x50-282828-969696'));?>">
		</a>
			
	<?php
		}
	}
	?>
	
	
	<div class="wd-articles-info">
		<h4><a href="<?php echo GNRouter::createUrl('/article?article_id='.IDHelper::uuidFromBinary($article->id,true));?>"><?php echo $article->title;?></a></h4>
		<?php if(!empty($article->author->user)):?>
		<p class="wd-userpost">Written by <a href="<?php echo GNRouter::createUrl('/profile/'.$article->author->user->username);?>"><?php echo $article->author->user->displayname;?></a></p>
		<?php endif;?>
		<p>
		<?php
		echo JLStringHelper::char_limiter_word(strip_tags($article->content,"<p>"),200);
		?>
		</p>
	</div>
	
</li>