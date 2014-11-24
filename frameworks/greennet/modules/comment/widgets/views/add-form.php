<?php
	$id				= md5(uniqid());
	$listCommentId	= "listId-{$this->objectId}";
	$textarea		= "textarea-{$this->objectId}";
	$this->renderFile(Yii::getPathOfAlias($this->viewAddForm) . ".php");
?>
<script language='javascript'>
var	$actionAdd		= '<?php echo $this->addCommentUrl?>';
</script>