<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/css/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/css/bootstrap-responsive.min.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/css/bootstrap-image-gallery.min.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/css/jquery.fileupload-ui.css" media="screen" />

<noscript><link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/css/jquery.fileupload-ui-noscript.css"></noscript>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/vendor/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/jquery/tmpl.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/jquery/load-image.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/jquery/canvas-to-blob.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/jquery/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/jquery/bootstrap-image-gallery.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/jquery.fileupload.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/jquery.fileupload-fp.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/jquery.fileupload-ui.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/GNupload/js/main.js"></script>

<p>File was uploaded.</p>
<?php echo CHtml::beginForm('','post',array 
	('enctype'=>'multipart/form-data',
	'id' => 'myform'))?>
	<?php echo CHtml::activeLabel($classModel, 'title')?>
	<?php echo CHtml::activeTextField($classModel, 'title')?>
	<?php echo CHtml::error($classModel, 'title')?>
	<?php echo CHtml::activeLabel($classModel, 'description')?>
	<?php echo CHtml::activeTextArea($classModel,	'description')?><br>
	<?php echo CHtml::submitButton('Save')?>
<?php echo CHtml::endForm()?>
<hr>
<?php
	$this->widget('xupload.XUpload', array(
		'url' => Yii::app()->createUrl("site/upload"),
		'model' => $modelXUploadForm,
		'attribute' => 'file',
		'multiple' => true,
	));
?>