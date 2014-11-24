<?php 
GNAssetHelper::init(array(
'image'		=> 'img',
'css'		=> 'css',
'script'	=> 'js',
));

GNAssetHelper::setBase('greennet.components.GNUploader.assets.jQueryUploadFile');
// CSS Files
// GNAssetHelper::cssFile('style'); // huytbt removed because style conflict
GNAssetHelper::cssFile('jquery.fileupload-ui');
//GNAssetHelper::cssFile('jquery.fileupload-ui-noscript');

// Script Files
GNAssetHelper::scriptFile('tmpl.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('vendor/jquery.ui.widget', CClientScript::POS_END);
GNAssetHelper::scriptFile('load-image.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('canvas-to-blob.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.blueimp-gallery.min', CClientScript::POS_END);
// 		GNAssetHelper::scriptFile('jquery.blueimp-gallery.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.iframe-transport', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.fileupload', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.fileupload-process', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.fileupload-image', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.fileupload-validate', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.fileupload-ui', CClientScript::POS_END);
?>

<?php $this->renderPartial("application.modules.resources.views.templates.upload")?>
<!-- The template to display files available for download -->
<?php $this->renderPartial("application.modules.resources.views.templates.download")?>

<?php 
$formID = uniqid();
$uploadUrl = ZoneRouter::createUrl('/video/uploadVideo');
$inputs = array(
	'ZoneResourceVideo[object_id]'	=> 'asdfasd'
);
?>

<form id="<?php echo $formID?>" action="<?php echo $uploadUrl?>" method="POST" enctype="multipart/form-data">
	<?php foreach ($inputs as $name => $value):?>
	<input type='hidden' name='<?php echo $name?>' value='<?php echo $value?>'/>
	<?php endforeach;?>
	
	<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
	
	<div class="row fileupload-buttonbar">
		<div class="span7">
			<!-- The fileinput-button span is used to style the file input field as button -->
			<span id="disable" class="btn btn-success fileinput-button btnAddFiles'">
				<i class="icon-plus icon-white"></i>
				<span>Add files...</span>
				<input type="file" name="files[]" multiple>
			</span>
			<button type="submit" class="btn btn-primary start">
				<i class="icon-upload icon-white"></i>
				<span>Start upload</span>
			</button>
		</div>
		<!-- The global progress information -->
	</div>
	<!-- The loading indicator is shown during file processing -->
	
	<br>
	<!-- The table listing the files available for upload/download -->
	<table role="presentation" class="table table-striped">
		<tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">
		</tbody>
	</table>
	
</form>


<script language='javascript'>
$(document).ready(function() {
	$('#<?php echo $formID?>').fileupload({
		url: '<?php echo $uploadUrl?>',
		paramName : 'ZoneResourceVideo[video]',
		maxNumberOfFiles : 1,
		formData : $('#<?php echo $formID?>').serializeArray(),
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			data.context.find('.progressBar').css(
				'width',
				progress + '%'
			).html(progress + '%');
		},
		acceptFileTypes : /(\.|\/)(avi|flv|mp4)$/i,
		downloadTemplateId : "template-download",
		uploadTemplateId : "template-upload",
		autoUpload: false
	});
});

var number = 0;
var num = 0;
var maxFile		= 1;

$('#<?php echo $formID?>').bind('fileuploadchange', function (e, data) {
	<?php //echo $this->changeCallback;?>
	
});

$('#<?php echo $formID?>').bind('fileuploaddone', function (e, data) {
	<?php //echo $this->addedCallback;?>
});

</script>