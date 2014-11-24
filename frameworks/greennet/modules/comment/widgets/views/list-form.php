<script type="text/javascript" src="/assets/default/js/jquery.tmpl.min.js"></script>
<div id='listId-<?php echo $this->objectId?>' class='coregreennet-comment-list-container' objectId="<?php echo $this->objectId?>" loadReverse="<?php echo $this->loadReverse?>" limit="<?php echo $this->limit?>" preloads="<?php echo $this->preLoads?>" totalComments="<?php echo $this->totalComments?>">
	<div class="coregreennet-comment-list" >
	</div>
</div>
<?php
	$listCommentId	= "listId-{$this->objectId}";
	$this->renderFile(Yii::getPathOfAlias($this->listCommentsTemplate) . ".php");
?>
<script language="javascript">
	var $viewMoreCommentUrl		= '<?php echo $this->viewMoreUrl?>';
	var $actionDeleteComment	= '<?php echo $this->deleteCommentUrl?>';
	var $numberOfWord			= <?php echo $this->numberOfWord?>;
	var $readMoreText			= ' <?php echo $this->readMoreText?>';
	var $readLessText			= ' <?php echo $this->readLessText?>';
	var $readLessShow			= '<?php echo $this->readLessShow?>';
	
	$(document).ready(function (){
		var $listComment	= <?php echo CJSON::encode($out);?>;
		var comments		= $.tmpl($('#coregreennet-comment-item-template'), $listComment);
		$('#<?php echo $listCommentId;?> .coregreennet-comment-list').append(comments);
		var $_preloads	= <?php echo $this->preLoads?>;
		var $_total		= <?php echo $this->totalComments?>;
		if ($_preloads>=$_total) {
			$('#listId-<?php echo $this->objectId?>').parents('.coregreennet-wp-comments').find('.coregreennet-show-comments').remove();
		}
	});
</script>