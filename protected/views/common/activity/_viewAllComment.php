<?php
// dump($countComment);
?>
<div class="wd-pp-comment-info wd-pp-comment-info-bt" 
	<?php if( $limit>0 && $countComment > 3) :?>
		onClick="viewAll('<?php echo $activityID?>')"
	<?php endif;?>
	id="wd-comment-viewall<?php echo $activityID;?>" 
	style="<?php echo !empty($style) ? $style : "";?>"
viewPath="widgets.comment.views.item"  objectId="<?php echo $activityID;?>" 
ref="<?php echo ZoneRouter::createUrl('/comments/comment/lists')?>" limit="<?php echo $countComment;?>">
	<span class="wd-comment-bt wd-tooltip-hover" title="Comments"></span>
	<span style="cursor: pointer;">
		<label id="numberComment" ><?php echo $countComment;?></label>
		<label id="textNumberComment" style="cursor: pointer;"><?php echo ($countComment==1) ? " Comment" : " Comments";?></label>
	</span>
</div>