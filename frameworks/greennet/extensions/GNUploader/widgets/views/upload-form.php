<style>
.dropzone {
	border:1px solid #ddd;
	background: url();
}
.gallery-item {float:left; width: 150;margin:10px;position:relative;height: 150px;width: 150px;}
.gallery-item .delete {position:absolute;top:3px;right: 3px;}
.gallery {border:1px solid #ddd;
	min-height: 100px;}
</style>
<?php 
$id = md5(uniqid());
$dropzone = "dropzone-{$id}";
$galleryID = "gallery-{$id}";

GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js',
));
GNAssetHelper::setBase('greennet.extensions.GNUploader.assets.jQueryUploadFile');

// CSS Files
GNAssetHelper::cssFile('style');
GNAssetHelper::cssFile('jquery.fileupload-ui');

// Script Files
GNAssetHelper::scriptFile('tmpl.min');
GNAssetHelper::scriptFile('vendor/jquery.ui.widget');
GNAssetHelper::scriptFile('jquery.iframe-transport');
GNAssetHelper::scriptFile('jquery.fileupload');
GNAssetHelper::scriptFile('jquery.fileupload-fp');
GNAssetHelper::scriptFile('jquery.fileupload-ui');
GNAssetHelper::scriptFile('main');
?>

<?php 
// echo CHtml::beginForm('','post',array 
// 	('enctype'=>'multipart/form-data',
// 	'id' => 'myform'));
?>
	
<?php // echo CHtml::submitButton('Save')?>
<?php // echo CHtml::endForm()?>
<hr>
<?php
// 	$this->widget('xupload.XUpload', array(
// 		'url' => Yii::app()->createUrl("site/upload"),
// 		'model' => $modelXUploadForm,
// 		'attribute' => 'file',
// 		'multiple' => true,
// 	));
?>
<div id='<?php echo $galleryID?>' class='gallery'>
	<?php 
	$this->widget('greennet.modules.gallery.widgets.GNGalleryWidget', array(
		'uri'			=> '/gallery',
		'model'			=> 'GalleryItem',
		'ref'			=> $this->object_id,
		'deleteUrl'		=> $this->deleteUrl
	));
	?>
	<div id='<?php echo $dropzone;?>' class='dropzone gallery-item'>
	</div>
	<div style='clear:both'></div>
</div>
<form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
		<!-- Redirect browsers with JavaScript disabled to the origin page -->
		<noscript><input type="hidden" name="redirect" value="http://blueimp.github.com/jQuery-File-Upload/"></noscript>
		<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
		<div class="row fileupload-buttonbar">
			<div class="span7">
				<!-- The fileinput-button span is used to style the file input field as button -->
				<button type="submit" class="btn btn-primary start">
					<i class="icon-upload icon-white"></i>
					<span>Start upload</span>
				</button>
				
				<input type="checkbox" class="toggle">
			</div>
			<!-- The global progress information -->
			<div class="span5 fileupload-progress fade">
				<!-- The global progress bar -->
				<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
					<div class="bar" style="width:0%;"></div>
				</div>
				<!-- The extended global progress information -->
				<div class="progress-extended">&nbsp;</div>
			</div>
		</div>
		<!-- The loading indicator is shown during file processing -->
		<div class="fileupload-loading"></div>
		<br>
		<!-- The table listing the files available for upload/download -->
		<table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
	</form>
	
<!-- The template to display files available for upload -->
<?php $this->renderFile(Yii::getPathOfAlias($this->uploadTemplatePath) . ".php")?>
<!-- The template to display files available for download -->
<?php $this->renderFile(Yii::getPathOfAlias($this->downloadTemplatePath) . ".php")?>

<script language='javascript'>
//Initialize the jQuery File Upload widget:

$(document).ready(function() {
	$('#fileupload').fileupload({
		// Uncomment the following to send cross-domain cookies:
		//xhrFields: {withCredentials: true},
		url: '<?php echo $this->uploadUrl?>',
		paramName : '<?php echo "{$this->model->name}[$this->fieldName]";?>',
		dropZone : $('#<?php echo $dropzone?>'),
		formData : [{
			name : 'object_id',
			value : '<?php echo $this->object_id?>'
		}]
	});
});

$('#<?php echo $dropzone;?> .gallery-item .delete').click(function() {
	var fileID = $(this).parent().attr('ref');
	var _parent = $(this).parent();
	$.ajax({
		url : '<?php echo $this->deleteUrl?>?fileid=' + fileID,
		success : function(res) {
			if (!res.error) {
				_parent.remove();
			} else {
				// TODO: Display alert popup
				
			}
		}
	});
});

</script>