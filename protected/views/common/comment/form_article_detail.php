<div class="wd-entry-content-box wd-entry-content-box-2 <?php if(empty($this->listComment)) echo "bdbno";?>"  id="box-comment-<?php echo $this->strToken;?>">
	<a href="<?php echo ZoneRouter::createUrl('/profile');?>" class="wd-thumb" title="<?php echo $this->currentUser->displayname;?>">
		<img class="avatar" width="44" height="44" alt="<?php echo $this->currentUser->displayname;?>" src="<?php echo ZoneRouter::CDNUrl("/upload/user-photos/".IDHelper::uuidFromBinary($this->currentUser->id, true)."/fill/44-44/" . $this->currentUser->profile->image)."?album_id=".IDHelper::uuidFromBinary($this->currentUser->id, true);?>">
	</a>
	<div class="wd-contenright-box">
		<form id="frmAddReview<?php echo $this->strToken;?>" method="POST" action="<?php echo GNRouter::createUrl('/comments/comment/addComment')?>">
			<input type="hidden" value="<?php echo $this->strToken;?>" name="strTokenComment">
			<input type="hidden" value="<?php echo $this->objectId;?>" name="objectId">
			<input type="hidden" value="<?php echo $this->viewItemPath;?>" name="viewItemPath">
			<div class="wd-inputbox">
				<textarea name="contentComment" class="wd-font-11" cols="97" rows="2" onkeydown="submitReview('<?php echo $this->strToken;?>',event,'<?php echo $this->objectId;?>')" id="textareaReview<?php echo $this->strToken;?>"></textarea>
			</div>
			<div class="wd-submitbox">
				<input type="button" class="required_login wd-submit-bt" value="Post" onclick="addReview('<?php echo $this->strToken;?>');">
			</div>
		</form>
	</div>
</div>