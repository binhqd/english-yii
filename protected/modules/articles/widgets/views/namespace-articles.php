<div class="wd-list-news clear">
	<?php if (!empty($articles)):?>
	<ul>
		<?php foreach ($articles as $article):?>
		<li>
			<h4 class="wd-title"><a href="<?php echo ZoneRouter::createUrl('/article?article_id=' . $article['id'])?>"><?php echo $article['title']?></a></h4>
			<p class="wd-text-calendar">By <a href="<?php echo GNRouter::createUrl('/profile/', array('id' => IDHelper::uuidFromBinary($article['author_id'], true)))?>"><?php echo $article['author']?></a> posted <?php echo date('M jS Y H:i A', strtotime($article['created']))?></p>
			<a href="#"><img src="<?php echo ZoneRouter::CDNUrl('upload/gallery'); ?>/fill/134-134/<?php echo $article['image']?>" alt="" /></a>
			
			<?php echo GNStringHelper::word_limiter($article['description'], 100)?>
			
			<p><a href="<?php echo ZoneRouter::createUrl('/article?article_id=' . $article['id'])?>" class="wd-read-more">Read More</a><a href="#">250 Comments</a></p>
			<p class="icon-like-unlike"><span class="icon-22 icon-like">&nbsp;</span><span class="wd-bg-num">0</span><span class="icon-22 icon-dislike">&nbsp;</span><span class="wd-bg-num">0</span></p>
		</li>
		<?php endforeach;?>
	</ul>
	<?php endif;?>
</div>
<!-- <div class="wd-loading">
	<span class="wd-bg-loading">Loading ...</span>
</div> -->