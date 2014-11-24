<div class="post">
	<div class="title">
		Picture #<?php echo $index + 1?>
	</div>

	<div class="content">
		<img width="300" height="200" src="<?php echo Yii::app()->request->baseUrl; ?>/images/<?php echo $data->image ?>" />
	</div>
</div>