<style>
.dropzone {background: url(<?php echo $this->assetUrl?>img/drag-photos.jpg);}
.gallery-item {width: <?php echo $width;?>px;height: <?php echo $height;?>px;}
.disable {display: none;}
</style>

<?php 
$id = md5(uniqid());

$dropzone	= (empty($this->objectHtmlOptions['dropzone']) ) ? "dropzone-{$id}" : $this->objectHtmlOptions['dropzone'];
$galleryID	= (empty($this->objectHtmlOptions['gallery']) ) ? "gallery-{$id}" : $this->objectHtmlOptions['gallery'];
$formID		= (empty($this->objectHtmlOptions['fileupload']) ) ? "fileupload-{$id}" : $this->objectHtmlOptions['fileupload'];


?>
<form id="<?php echo $formID?>" action="<?php echo $this->uploadUrl?>" method="POST" enctype="multipart/form-data">
	<?php foreach ($this->inputs as $name => $value):?>
	<input type='hidden' name='<?php echo $name?>' value='<?php echo $value?>'/>
	<?php endforeach;?>
	<input type='hidden' name='object_id' value='<?php echo $this->object_id?>'/>
	
	<div style='border:1px solid #ddd'>	
		<div id='<?php echo $galleryID?>' class='gallery'>
			<?php 
			$this->widget('greennet.modules.gallery.widgets.GNGalleryWidget', array(
				'uri'			=> $this->fileUri,
				'model'			=> $this->model,
				'ref'			=> $this->object_id,
				'deleteUrl'		=> $this->deleteUrl,
				'width'			=> $this->width,
				'height'		=> $this->height,
				'showDeleteButton'	=> false
			));
			?>
			<?php
			// set dropzone default
			if(empty($this->objectHtmlOptions['dropzone'])){
			?>
				<div id='<?php echo $dropzone;?>' class='dropzone gallery-item'></div>
			<?php 
			}
			?>
			
			
		</div>
		<div class='clear'></div>
	</div>
	<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
	
	<div class="row fileupload-buttonbar">
		<div class="span7">
			<!-- The fileinput-button span is used to style the file input field as button -->
			<span id="disable" class="btn btn-success fileinput-button btnAddFiles'">
				<i class="icon-plus icon-white"></i>
				<span><?php echo Yii::t("greennet", "Add files...");?></span>
				<input type="file" name="files[]" multiple>
			</span>
			<?php if (!$this->autoUpload):?>
			<button type="submit" class="btn btn-primary start">
				<i class="icon-upload icon-white"></i>
				<span><?php echo Yii::t("greennet", "Start upload");?></span>
			</button>
			<?php endif;?>
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
<!-- The template to display files available for upload -->

<?php $this->renderFile(Yii::getPathOfAlias($this->uploadTemplatePath) . ".php")?>
<!-- The template to display files available for download -->
<?php $this->renderFile(Yii::getPathOfAlias($this->downloadTemplatePath) . ".php")?>

<script language='javascript'>
//Initialize the jQuery File Upload widget:
// var into file template download
var assetUrl	= '<?php echo $this->assetUrl;?>';
var fileUri		= '<?php echo $this->fileUri?>';
var width		= '<?php echo $this->width?>';
var height		= '<?php echo $this->height?>';
$(document).ready(function() {
	$('#<?php echo $formID?>').fileupload({
		url: '<?php echo $this->uploadUrl?>',
		paramName : '<?php echo "{$this->model->name}[$this->fieldName]";?>',
		dropZone : $('#<?php echo $dropzone?>'),
		<?php if ($maxNumberOfFiles > 0):?>
		maxNumberOfFiles : <?php echo $maxNumberOfFiles?>,
		<?php endif;?>
		
		//formData : [{
		//	name : 'object_id',
		//	value : '<?php echo $this->object_id?>'
		//}],
		formData : $('#<?php echo $formID?>').serializeArray(),
		filesContainer : $('#<?php echo (empty($this->objectHtmlOptions['filesContainer']) ) ? $galleryID : $this->objectHtmlOptions['filesContainer'];?>'),
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			data.context.find('.progressBar').css(
				'width',
				progress + '%'
			).html(progress + '%');
		},
		acceptFileTypes : /(\.|\/)(gif|jpe?g|png)$/i,
		previewMaxWidth : <?php echo $this->width?>,
		downloadTemplateId : "<?php echo $this->downloadTemplateId;?>",
		uploadTemplateId : "<?php echo $this->uploadTemplateId;?>",
		previewMaxHeight : <?php echo $this->height?>,
		sequentialUploads : <?php echo $this->sequentialUploads ? "true" : "false";?>,
		<?php foreach ($this->overrideOptions as $key => $option):?>
		<?php echo $key?> : <?php echo $option?>,
		<?php endforeach;?>
		autoUpload: <?php echo $this->autoUpload ? "true" : "false";?>
		
	});
});

$('body').on('click', '.btnCancelImage', function(e){
	$(this).parent().find('.cancel button').trigger('click');
});
var number = 0;
var num = 0;
var maxFile		= <?php echo $maxNumberOfFiles?>;

$('#<?php echo $formID?>').bind('fileuploadchange', function (e, data) {
	<?php echo $this->changeCallback;?>
	
});
$('#<?php echo $formID?>').bind('fileuploadadded', function (e, data) {
	var dropzone	= $('#<?php echo $dropzone?>');
	var gallery		= $('#<?php echo $galleryID?>');
	gallery.append(dropzone);
	//gallery.append(gallery.find('.clear'));
	
	var self = $(this);
	
	var lengthData = data.originalFiles.length;
	var lengthArr = 0;
	$.each(data.originalFiles, function(key, item) {
		num++;
		lengthArr++;

		if(item.error) {
			self.find('div.gallery-item[ref="'+item.name+'"]').remove();
			maxFile++;
		}
		if (maxFile!=0 && lengthArr>maxFile) {
			self.find('div.gallery-item[ref="'+item.name+'"]').remove();
		}
	});
	
	num = number++;
	num++;
	if (maxFile!=0 && number>=maxFile) {
		$('#<?php echo $formID?> .span7 #disable').addClass("disable");
		dropzone.addClass("disable");
	}
});
$('#<?php echo $formID?>').bind('fileuploaddone', function (e, data) {
	<?php echo $this->addedCallback;?>
});

$('body').on('click', '.delete', function(e){
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

			<?php echo $this->deletedCallback;?>
		}
	});
});

</script>