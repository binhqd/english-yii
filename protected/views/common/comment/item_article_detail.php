<div class="wd-entry-content-box item-box  <?php echo empty($show) ? "hide-row-comment" : "";?> " style="display:<?php echo empty($show) ? "none" : "block";?>">
	<a class="wd-thumb" href="<?php echo $comment->profileUrl;?>" title="<?php echo $comment->displayname;?>">
		<img class="avatar" width="44" height="44" alt="<?php echo $comment->displayname;?>" src="<?php echo $comment->avatarUrl;?>">
	</a>

	<div class="wd-contenright-box">
		<p class="wd-commentpost">
			<a href="<?php echo $comment->profileUrl;?>" class="wd-userpost-name"><?php echo $comment->displayname;?> </a>
			
			<span class="wd-time-posted timeago" data-title="<?php echo  date(DATE_ISO8601,strtotime($comment->commentDate));?>"></span></p>
		<div class="wd-entry-content-detail" content="">
			<label style="display:none">
				<?php echo $comment->commentContent;?>
			</label>
			<label>
			<?php
				$content =  JLStringHelper::char_limiter_word($comment->commentContent,200);
				$threeWord = substr($content, -3);
				echo $content;
				if($threeWord == "30;"){
					echo CHtml::link('See more','javascript:void(0)',array('class'=>'truncate_more_link'));
				}
			?>
			</label>
		</div>
	</div>
</div>