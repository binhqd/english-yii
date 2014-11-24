<div style="display:none;">
	<div id="wd-upload-photo" class='upload_photo_form jlbd-popup claim-biz-popup'>
		<div class="wd-popup-content non-border">
			<h4 class="wd-title-addphoto">Upload photos for <?php echo $UserInfo['username']?></h4>
			<div id="bandwidth" class="usage">
				<p id="bandwidth-left">
				Click on the "Add files" button to select photos from your computer. Then click the "Start upload" button to begin uploading photos.
				</p>
				<p class='note'><b>Note</b>: File types allowed: *.jpg, *.png, *.gif</p>
			</div>
			
			<div class="wd-biz-register-form" id="add_photo" style="margin-top:10px; border-bottom:none">
			<?php
			$max = 10;

			$arrParamsUrl = array(
				'uuid'=>$UserInfo['uuid'],
				'type'=>'member'
			);
			if(!empty($_GET['new'])){
				$arrParamsUrl = array(
					'uuid'=>$UserInfo['uuid'],
				);
			}
			$arrParamsUrl['size'] = '85-85';
			
			$urlUpload =JLRouter::createAbsoluteUrl('/admin_manage/manageUser/uploadPhoto',$arrParamsUrl);
			
			
			$this->widget('ext.jqueryupload.JQueryUploadWidget', array(
				'id'	=> 'UserPhoto',
				'url' 	=> JLRouter::createAbsoluteUrl("/admin_manage/manageUser/uploadPhoto") . "?uuid={$UserInfo['uuid']}",
				'name' => 'UserPhoto',
				'attribute' => 'filename',
				'multiple' => true,
				'options' => array(
					'maxNumberOfFiles' => $max,
					'acceptFileTypes' => 'js:/(\.|\/)(gif|jpe?g|png)$/i',
					'maxFileSize'=>10485760,
				),
				'htmlOptions' => array(
					'class' => 'jlb_row jform',
					'id'	=> 'photoUploader-contribute',
				),
				'htmlUIOptions' => array(
					//'htmlHeader' => '<input type="hidden" name="'.get_class($modelPhoto).'[id]" id="'.get_class($modelPhoto).'" value="1">'
				),
				//'cssFile'=>Yii::app()->baseUrl."../../../../../justlook/img/flick/jquery-ui-1.8.18.custom.css"
			));					
			?>
			</div>
		</div>
	</div>
</div>