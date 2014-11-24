<?php 
$this->widget('greennet.modules.gallery.widgets.GNManageGalleryWidget', array(
	'model'			=> 'application.modules.resources.models.ZoneResourceImage',
	'fieldName'		=> 'image',
	'object_id'		=> $this->namespaceID,
	'uploadPath'	=> 'upload/gallery/',
	'fileUri'		=> '/upload/gallery',
	'deleteUrl'		=> GNRouter::createUrl('/resources/zoneResourceImage/delete-image'),
	//'maxNumberOfFiles'	=> 3,
	//'uploadTemplate'	
	'callbacks'	=>array(
		'added'		=>'
			if (typeof data.result.files[0].error != "undefined" && typeof data.result.files[0].error) {
				jlbd.dialog.notify({
					type : "error",
					message : data.result.files[0].message
				});
			}
			//console.log(data);
			//var input = $("<input ref=\'"+data.result.files[0].fileid+"\' type=\'hidden\' name=\'images[]\' value=\'"+data.result.files[0].fileid+"\'/>");
			//$("#addDestination").append(input);
			
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