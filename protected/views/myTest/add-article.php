<form enctype="multipart/form-data" class="form-horizontal"
	id="dntbusiness-form" action="/articles/postArticle/create"
	method="post">
	<input type='hidden' name='ZoneArticleAuthor[]' value='<?php echo currentUser()->hexID?>'/>
	<input type='hidden' name='ZoneArticleNamespace[]' value='5163E365C5504EE7aaaaaaaaaaaaaaa'/>
	<input type='hidden' name='requestType' value='json'/>
	
	<fieldset>
		<div class="controls">
			<p class="note">
				Fields with <span class="required">*</span> are required.
			</p>
		</div>

		<div class="control-group ">
			<label class="control-label required" for="ZoneArticle_title">Title <span
				class="required">*</span>
			</label>
			<div class="controls">
				<input class="span5" name="ZoneArticle[title]"
					id="ZoneArticle_title" type="text">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label required" for="ZoneArticle_content">Content
				<span class="required">*</span>
			</label>
			<div class="controls">
					<textarea id="ZoneArticle_content" name="ZoneArticle[content]"
						style="width: 500px; height: 197px;"></textarea>
				</div>
			</div>
		</div>
		<!--upload image-->
		<div class="control-group">
			<label class="control-label" for="ZoneArticle_image">Image</label>
			<div class="controls">
				<input id="ytZoneArticle_image" type="hidden" value=""
					name="ZoneArticle[image]"><input name="ZoneArticle[image]"
					id="ZoneArticle_image" type="file">
			</div>
		</div>

		<div class="form-actions">
			<button class="btn btn-primary" type="submit" name="yt0">Create</button>
			<button class="btn" type="reset" name="yt1">Reset</button>
		</div>

	</fieldset>
</form>

<?php 
$this->widget('greennet.modules.gallery.widgets.GNManageGalleryWidget', array(
	'model'			=> 'application.modules.resources.models.ZoneResourceImage',
	'fieldName'		=> 'image',
	'object_id'		=> '',
	'uploadPath'	=> 'upload/gallery/',
	'fileUri'		=> '/upload/gallery',
	'deleteUrl'		=> GNRouter::createUrl('/resources/zoneResourceImage/delete-image'),
	//'maxNumberOfFiles'	=> 3,
	//'uploadTemplate'	
	'callbacks'	=>array(
		'added'		=>'
			if (typeof data.result.files[0].error != "undefined" && typeof data.result.files[0].error) {
				/*jlbd.dialog.notify({
					type : "error",
					message : data.result.files[0].message
				});*/
			}
			//console.log(data);
			var input = $("<input ref=\'"+data.result.files[0].fileid+"\' type=\'hidden\' name=\'images[]\' value=\'"+data.result.files[0].fileid+"\'/>");
			$("#dntbusiness-form").append(input);
			
		',
		'deleted'	=>'
			/**
			 * Returning value:
			 * res.fileid
			 * res.filename
			 */
			if (res.error) {
				jlbd.dialog.notify({
					type : "error",
					message : res.message
				});
			} else {
				//$("#addDestination input[ref=\'"+res.fileid+"\']").remove();
			}
			
		'
	),
	'uploadUrl'	=> GNRouter::createUrl('/resources/zoneResourceImage/upload'),
	'uploadTemplatePath'	=> 'greennet.modules.gallery.widgets.views.upload',
	'downloadTemplatePath' => 'greennet.modules.gallery.widgets.views.download',
	'width'		=> 150,	// width of image preview , default 150
	'height'	=> 150
));
?>
