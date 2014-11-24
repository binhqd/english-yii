<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<script src="http://blueimp.github.com/cdn/js/bootstrap.min.js"></script>
<?php
					
				$this->widget('greennet.modules.gallery.widgets.GNManageGalleryWidget', array(
					'model'			=> 'application.modules.resources.models.ZoneResourceImage',
					'fieldName'		=> 'image',
					// 'object_id'		=> $this->namespaceID,
					'object_id'		=> null,
					'uploadPath'	=> 'upload/gallery/',
					'fileUri'		=> '/upload/gallery',
					'deleteUrl'		=> GNRouter::createUrl('/resources/zoneResourceImage/delete-image'),
					//'maxNumberOfFiles'	=> 3,
					//'uploadTemplate'	
					'callbacks'	=>array(
						'change'		=>'
							// console.log(data.files)
						',
						'added'		=>'
							console.log(data);
							//var input = $("<input ref=\'"+data.result.files[0].fileid+"\' type=\'hidden\' name=\'images[]\' value=\'"+data.result.files[0].fileid+"\'/>");
							//$("#addDestination").append(input);
							
						',
						'deleted'	=>'
							if (res.error) {
								
							} else {
								//$("#addDestination input[ref=\'"+res.fileid+"\']").remove();
							}
							
						'
						
					),
					'uploadUrl'	=> GNRouter::createUrl('/resources/zoneResourceImage/upload'),
					
					'uploadTemplatePath'=>'widgets.formPost.views.upload',
					'downloadTemplatePath'=>'widgets.formPost.views.download',
					'width'		=> 80,	// width of image preview , default 150
					'height'	=> 80,
					'objectHtmlOptions'=>array(
						'dropzone'=>"dropzone12",
						'gallery'=>"gallery12",
						'fileupload'=>"fileupload-12",
						'filesContainer'=>"filesContainer12"
					)
				));
				
			?>
			<div id="filesContainer12">
			
			</div>