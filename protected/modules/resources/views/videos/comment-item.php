<div class="wd-content-box emoticon js-comment-box item-box <?php if (isset($isLastComment) && $isLastComment) echo 'bdbno'; ?> <?php echo empty($show) ? "hide-row-comment" : "";?> " style="display:<?php echo empty($show) ? "none" : "block";?>" anchor="<?php echo $comment->commentId;?>">
	<?php if($comment->isOwner) :?>
		<span class="js-delete-comment wd-tooltip-hover wd-delete-comment" token="<?php echo $comment->commentId?>" style="opacity: 0; float: right; cursor: pointer;">X</span>
	<?php endif;?>
	<?php
		if(!empty($this->onPopup) && $this->onPopup){
	?>
	<a href="javascript:void(0)" onclick="parent.window.location.href='<?php echo $comment->profileUrl;?>'" class="wd-thumb" data-title="<?php echo $comment->displayname;?>">
		<img class="avatar"  alt="<?php echo $comment->displayname;?>" src="<?php echo $comment->avatarUrl;?>">
	</a>
	<?php
	}else{
	?>
	<a href="<?php echo $comment->profileUrl;?>" class="wd-thumb" title="<?php echo $comment->displayname;?>">
		<img class="avatar" alt="<?php echo $comment->displayname;?>" src="<?php echo $comment->avatarUrl;?>">
	</a>
	<?php
	}
	?>
	<div class="comment-item">
		<p class="wd-commentpost fl r10">
			<?php
			if(!empty($this->onPopup) && $this->onPopup){
			?>
				<a href="javascript:void(0)" onclick="parent.window.location.href='<?php echo $comment->profileUrl;?>'" >
					<strong><?php echo $comment->displayname;?></strong>
				</a> 
			<?php
			}else{
			?>
				<a href="<?php echo $comment->profileUrl;?>">
					<strong><?php echo $comment->displayname;?></strong> 
				</a> 
				<p class='wd-date-post'><label class="timeago" data-title="<?php echo  date(DATE_ISO8601,strtotime($comment->commentDate));?>"></label></p>
			<?php
			}
			?>
			
			<label style="display:none">
				<?php echo strip_tags($comment->commentContent,"<p>");?>
			</label>
			<div style="padding-left: 55px;">
				<span class="youlook-comment-video-detail">
					<?php
						echo $comment->commentContent;
					?>
				</span>
			</div>
		</p>
		
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(e){
			registerDeleteComment($('.js-delete-comment'));
	});
</script>