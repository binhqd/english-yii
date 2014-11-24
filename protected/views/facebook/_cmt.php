<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php Yii::app()->clientScript->registerCoreScript('yiiactiveform'); ?>
<h3>Leave a Comment</h3>
<?php $this->renderPartial('_form',array('id' => $id)); ?>
<h3>
	<?php echo count($comments) . ' comments from Facebook'; ?>
</h3>
<?php foreach($comments as $comment): ?>
<div class="comment" id="c<?php echo $comment['id']; ?>">

	<div class="author">
		<img class="profileimage" name="" src="https://graph.facebook.com/<?php echo $comment['from']['id']; ?>/picture?type=normal"  alt="<?php echo $comment['from']['name'] ?>" />
		<a href="http://facebook.com/profile.php?id=<?php echo $comment['from']['id']; ?>"><?php echo $comment['from']['name']; ?></a> says:
	</div>

	<div class="time">
		<?php echo $comment['created_time']; ?>
	</div>

	<div class="content">
		<?php echo nl2br(CHtml::encode($comment['message'])); ?>
	</div>

</div><!-- comment -->
<?php endforeach; ?>