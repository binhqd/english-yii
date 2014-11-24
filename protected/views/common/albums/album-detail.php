<?php 
$user = currentUser();

Yii::import('application.modules.users.models.ZoneUserAvatar');
?>

<?php
GNAssetHelper::init(array(
'image'		=> 'img',
'css'		=> 'css',
'script'	=> 'js',
));
GNAssetHelper::setBase('myzone_v1');

GNAssetHelper::cssFile('search-activities');
GNAssetHelper::cssFile('topsearch-pagelet-form');
GNAssetHelper::cssFile('viewall-photo');
GNAssetHelper::cssFile('pagelet-stream-post');
GNAssetHelper::cssFile('pagelet-composer-img-att-content');
GNAssetHelper::cssFile('pagelet-composer-photopost-content');
GNAssetHelper::cssFile('add-photo-update');
GNAssetHelper::cssFile('action-button-photo-update');





// GNAssetHelper::scriptFile('imagesloaded.pkgd.min', CClientScript::POS_END);
// GNAssetHelper::scriptFile('jquery.wookmark.min', CClientScript::POS_END);
// GNAssetHelper::scriptFile('masonry.pkgd.min', CClientScript::POS_END);
// GNAssetHelper::scriptFile('jquery.infinitescroll.min', CClientScript::POS_END);

GNAssetHelper::scriptFile('jquery.unveil', CClientScript::POS_HEAD);
// GNAssetHelper::scriptFile('zone.album.photo', CClientScript::POS_END);
GNAssetHelper::scriptFile('all-user-photos', CClientScript::POS_END);

?>
<?php 
if($edit == null)
	$this->renderPartial('application.modules.photos.views.templates.list-photo-item');
else
	$this->renderPartial('application.modules.photos.views.templates.list-photo-item-edit');

?>

<?php $this->renderPartial('application.modules.photos.views.templates.popup-photo');?>
<?php $this->renderPartial('application.modules.photos.views.templates.popup-photo-content');?>
<?php //$this->renderPartial('application.modules.photos.views.templates.popup-add-photo');?>
<?php
$this->renderPartial('//common/user-related', compact('user'));
$this->widget('ext.timeago.JTimeAgo', array(
    'selector' => ' .timeago',
 
));
?>
<script language='javascript'>
var albumPhotos = <?php echo @CJSON::encode($photos)?>;


</script>

<div class="wd-container">
	<div class="wd-center wd-center-content-layout3">
		<div class="wd-right-content">
			<!-- How You're Connected -->
			<?php $this->widget('application.modules.followings.components.widgets.ZoneFollowingHowConnected', array(
				'object_id' => $user->hexID,
			)); ?>
			<!-- How You're Connected .end -->
			<!-- People’s you may know -->
			<?php $this->widget('application.modules.friends.components.widgets.ZoneFriendsPeopleYouMayKnow'); ?>
			<!-- People’s you may know .end -->
			<!-- People also viewed -->
			<?php $this->renderPartial('application.modules.users.views.elements.people-also-view') ?>
			<!-- People also viewed .end -->
			<!-- YouLook for mobile -->
			<?php $this->renderPartial('application.modules.users.views.elements.youlook-for-mobile') ?>
			<!-- YouLook for mobile .end -->
			
		</div>
		<div class="wd-contain-content">
	<!-- header line -->
			<?php
			$this->renderPartial('application.modules.users.views.elements.user-interaction-status', array(
					'user' => $user,
					// 'pages'=>'album'
				));
				
			
			?>
			<!-- header line .end-->
			<!-- main content -->
			<div class="wd-main-content" >
				<div class="wd-headmain-photo">
				
					<div class="wd-headmain-photo-top">
						<!--<div class="wd-act-button-photo-update">
							<a href="javascript:void(0)"  class="wd-bt-add-photo"><span class="wd-icon-add-photo"></span>New photos</a>
							<a href="javascript:void(0)" validate="1" id="doneUpload" album_id="<?php echo IDHelper::uuidFromBinary($album->id,true);?>" style="display:none" class="wd-bt-done-upload wd-save-button">Done upload</a>
							<a href="javascript:void(0)" onclick="cancelPhotos()" style="display:none" id="cancelUpload" class="wd-bt-cancel">Cancel</a>
						</div>-->
						<?php
						$tag = "album";
						$totalPic = $totalPicProfile;
						
						if($tag!= null) $totalPic = $totalPhotos-$totalPicProfile;
						$this->renderPartial('application.modules.photos.views.menu',array(
							'tag'=>$tag,
							'user'=>$user,
							'totalPicProfile'=>$totalPicProfile,
							'totalPhotos'=>$totalPhotos
						));
						?>
						
						<div class="clear"></div>
					</div>
				</div>
				<div class="wd-head-info-album-detail">
					<?php if($album->owner_id == currentUser()->id):?>
						<div class="wd-act-button-photo-update  wd-head-info-album-detail-edit">
    						<?php if($edit == null):?>
    						<div class="wd-act-button-photo-update wd-act-button-photo-update-editpage">
								<a id="add-photo" class="wd-bt-add-photo" href="javascript:void(0)" onclick="addPhotos()">
									<img src="<?php echo baseUrl();?>/img/38-1.gif" alt="loading" style="display:none; height:7px;">
									<span class="wd-icon-add-photo"></span>Add photos</a>
								<a id="post-photo" class="wd-bt-done-upload" style="display:none" href="javascript:void(0)">Done upload</a>
								<a id="cancelUpload" class="wd-bt-cancel" style="display:none" href="javascript:void(0)" onclick="cancelPhotos()">Cancel</a>
								<a href="<?php echo ZoneRouter::createUrl('/resource/album?album_id='.IDHelper::uuidFromBinary($album->id,true)."&edit=true");?>" class="wd-bt-add-photo" id="edit-photo">Edit</a>
							</div>
    						<?php else:?>
    							<a href="<?php echo ZoneRouter::createUrl('/resource/album?album_id='.IDHelper::uuidFromBinary($album->id,true));?>" class="wd-bt-done-upload wd-save-button" id="post-photo">
    								<img src="<?php echo baseUrl();?>/img/38-1.gif" alt="loading" style="display:none; height:7px;">
    								Save
    							</a>
    							<a href="<?php echo ZoneRouter::createUrl('/resource/album?album_id='.IDHelper::uuidFromBinary($album->id,true));?>" class="wd-bt-cancel" id="post-photo">
    								Cancel
    							</a>
    						<?php endif;?>
    					</div>
					<?php endif;?>
					<?php if($edit == null):?>
					<h2 class="wd_tt_1"><?php echo $album->title;?></h2>
					<p class="wd-description-album-detail"><?php echo $album->description;?></p>
					<?php else:?>
						<div class="wd-add-album-photo-update wd-pagelet-composer-mainform">
							<fieldset>
								<div class="wd-pagelet-composer-title-inputbox wd-title-inputbox">
									<textarea aria-haspopup="true" placeholder="Say something about this..." role="textbox" rows="2" cols="97" id="title11Album"><?php echo $album->title;?></textarea>
								</div>
								<div class="wd-pagelet-composer-title-inputbox wd-description-inputbox">
									<textarea aria-haspopup="true" placeholder="Say something about this..." role="textbox" rows="2" cols="97" id="des11Album"><?php echo $album->description;?></textarea>
								</div>
							</fieldset>
						</div>
						<script>

						$('body').on('keyup', '#title11Album', function(e){
							$("#nameAlbum").val($(this).val());
						});
						$('body').on('keyup', '#des11Album', function(e){
							$("#desAlbum").val($(this).val());
						});
						</script>
					<?php endif;?>
				</div>
				<div class="wd-pagelet-images-wiew">
					
					<ul class="wd-view-all-photo wd-view-all-albumphoto-edit" id="allPhotoContainer1">
						<div id="filesContainer<?php echo $strTokenPost;?>"></div>
						<?php if(empty($photos)):?>
						<div class="wd-empty-results-description">
							<p class="mt35">Hi! There are no photos in this album.</p>
						</div>
						<?php endif;?>
						
					</ul>
					<script language='javascript'>
					<?php if($edit == null):?>
					
						var renderedPhotos = $.tmpl($('#tmplListPhotoItem'), albumPhotos);
					
					<?php else:?>
					
						var renderedPhotos = $.tmpl($('#tmplListPhotoItemEdit'), albumPhotos);

					<?php endif;?>
					$('#allPhotoContainer1').append(renderedPhotos);
					</script>
					<div class="clear"></div>
				</div>
				<?php if($edit == null):?>
				
					<div class="wd-comment-album-detail">
						<div class="wd-action-storycontent">
							<?php
							$countComment = ZoneComment::model()->countComments(IDHelper::uuidFromBinary($album->id,true));
							$this->widget('widgets.like.Like', array(
								'objectId'=>IDHelper::uuidFromBinary($album->id,true),
								'actionLike'=> GNRouter::createUrl('like/liked/liked'),
								'actionUnlike'=> GNRouter::createUrl('like/liked/like'),
								'modelObject'	=> 'application.modules.like.models.LikeObject',
								'modelStatistic'	=> 'application.modules.like.models.LikeStatistic',
								'classUnlike'=>'wd-like-bt',
								'classLike'=>'wd-like-bt wd-liked-bt',
								
							));

							?>
							
							<?php Yii::app()->controller->renderPartial('//common/activity/_viewAllComment',array('activityID'=>IDHelper::uuidFromBinary($album->id,true),'countComment'=>$countComment,'limit'=>3,'style'=>'margin-left:10px;')) ?>
							<div class="clear"></div>
						</div>
						
						<?php
						
						$this->widget('widgets.comment.Comment', array(
							'objectId'=>IDHelper::uuidFromBinary($album->id,true),
							'viewMore'=>false,
							'countComment'=>$countComment,
							'loadJsTimeAgo'=>false,
							'limit'=>3,
							'viewItemPath'=>'widgets.comment.views.item'
						));
						?>
						
					</div>
				
					<div class="wd-pagelet-images-wiew">
						<h2 class="wd_tt_1">Other album</h2>
						<ul class="wd-view-all-photo wd-view-all-albumphoto" id="allPhotoContainer">
							<?php $this->renderPartial('application.modules.photos.views.user-photos.item-album',array(
								'albums'	=> $otherAlbums['data'],
								'user'		=> $user
							));
							?>
						</ul>
						<div class="clear"></div>
					</div>
					<?php if($otherAlbums['pages']->itemCount - $otherAlbums['pages']->pageSize>0):?>
						<a class="wd-list-stream-seamore show-more-album"  href="<?php echo ZoneRouter::createUrl('/resource/album?album_id='.IDHelper::uuidFromBinary($album->id,true));?>" page="1" limit="<?php echo $otherAlbums['pages']->pageSize;?>" countPhoto="<?php echo $otherAlbums['pages']->itemCount - $otherAlbums['pages']->pageSize;?>">
							<img src="<?php echo baseUrl();?>/img/front/loading-3.gif" style="display:none" alt="loading" class="wd-icon-loading">Show more albums</a>
					<?php endif;?>
				<?php endif;?>
				
				
			</div>
		</div>
	</div>
</div>





<script>

$(document).ready(function(){
	
	
	
	page = "album";
	globalToken = "<?php echo $strTokenPost;?>";
	
	$("img.lazy").unveil(300);
	
	$(".image-1").on('mouseover',function(e){
		if($(this).attr('count') > 1){
			$(this).hide();
			$(this).parent().find(".image-2").fadeIn(1000);
		}
	});
	$(".image-2").on('mouseout',function(e){
		$(this).hide();
		$(this).parent().find(".image-1").fadeIn(1000);
	});
	
});


// setInterval(function(){
	// try{
		// $("img.lazy").unveil(300);
	// }catch(e){
		// console.log(e.message);
	// }
// },1000);
function removeItem(fileid){
	$("#pushImages"+globalToken+" input[ref='"+fileid+"']").remove();
	$("#"+fileid).fadeOut(500);

}
</script>
<div class="gallery<?php echo $strTokenPost;?>" style="display:none">
<?php 
	$formPost=$this->beginWidget('CActiveForm', array(
		'id'=>"formPullImages",
		'enableAjaxValidation'=>false,
		'htmlOptions' => array(
			'enctype' => 'multipart/form-data',
		),
		'action'=>GNRouter::createUrl('/resource/createAlbum')
	));
	?>
	<div id="pushImages<?php echo $strTokenPost;?>">
		<?php if($edit != null ):?>
			<?php foreach($photos as $photo):
				$photo = $photo['photo'];
			?>
				
				<input ref='<?php echo $photo['id'];?>'  type='hidden' name='images[]' value='<?php echo $photo['id'];?>'/>
				<input id='des<?php echo $photo['id'];?>' type='hidden' name='des[<?php echo $photo['id'];?>]' value='<?php echo $photo['description'];?>'/>
			<?php endforeach;?>
		<?php endif;?>
	</div>
	<input type="hidden" id="nameAlbum" name="ZoneResourceAlbum[title]" value="<?php echo $album->title;?>">
	<input type="hidden" name="object_id" value="<?php echo IDHelper::uuidFromBinary($album->owner_id,true);?>"/>
	
	<input type="hidden" name="ZoneImagePoster[]" value="<?php echo currentUser()->hexID?>">
	
	<input type="hidden" name="returnType" value="json"/>
	<input type="hidden" name="returnPhotos" value="true"/>
	
	<input type="hidden"  name="album_id" value="<?php echo $album_id;?>">
	<input type="hidden" id="desAlbum" name="name" value="<?php echo ($album->description != null) ? $album->description: "";?>" >
	<?php $this->endWidget();?>
	

<?php
		
	$this->widget('greennet.modules.gallery.widgets.GNManageGalleryWidget', array(
		'model'			=> 'application.modules.resources.models.ZoneResourceImage',
		'fieldName'		=> 'image',
		// 'object_id'		=> $this->namespaceID,
		'object_id'		=> null,
		'uploadPath'	=> 'upload/gallery/',
		'fileUri'		=> '/upload/gallery',
		
		'deleteUrl'		=> GNRouter::createUrl('/resources/zoneResourceImage/delete-image'),
		'maxNumberOfFiles'	=> 1000,
		// 'overrideOptions'	=> array(
			// 'done' => 'function(e, data) {
							// data.context.find(\'.wd-photo-ul1\').css("opacity", 1);
							// data.context.find(\'.wd-processing\').remove();
						// }'
		// ),
		//'uploadTemplate'	
		'callbacks'	=>array(
			'change'		=>'
				var typeImages = ["image/gif","image/jpeg","image/png"];
				var inArray = false;
				$.each(typeImages,function(x,y){
					if(data.files[0].type  == y){
						inArray = true;
						return;
					}
				});
				if(!inArray){
					return;
				}
				$(".photoContainer").slideDown();
				$("#edit-photo").hide();
				objCountFile.photo.addFile = objCountFile.photo.addFile + data.files.length;
				$("#doneUpload").attr("class","");
				$("#doneUpload").addClass("wd-bt-cancel");
				
				showOpacityBtnAddPhoto();

				
			',
			'added'		=>'
				var input = $("<input ref=\'"+data.result.files[0].fileid+"\' type=\'hidden\' name=\'images[]\' value=\'"+data.result.files[0].fileid+"\'/>");
				$("#pushImages'.$strTokenPost.'").append(input);
				
				objCountFile.photo.doneFile = objCountFile.photo.doneFile+1;
				if(objCountFile.photo.doneFile == objCountFile.photo.addFile){
					$("#doneUpload").removeClass("wd-bt-cancel");
					$("#doneUpload").addClass("wd-bt-done-upload");
					hideOpacityBtnAddPhoto();
				}
				$("#add-photo").hide();
				$("#post-photo").show();
				$("#cancelUpload").show();
			',
			'deleted'	=>'
				if (res.error) {
					
				} else {
					$("#pushImages'.$strTokenPost.' input[ref=\'"+res.fileid+"\']").remove();
					$("#pushImages'.$strTokenPost.' input[ref=\'titleimage"+res.fileid+"\']").remove();
				}
				
			'
		),
		'uploadUrl'	=> GNRouter::createUrl('/resource/upload'),
		'inputs'	=> array(
			'ZoneImagePoster[]'	=> currentUser()->hexID
		),
		'uploadTemplatePath'=>'widgets.formPost.views.upload-view-all-album',
		'downloadTemplatePath'=>'widgets.formPost.views.download-view-all-album',
		'width'		=> 206,	// width of image preview , default 150
		'height'	=> 206,
		'objectHtmlOptions'=>array(
			'dropzone'=>"dropzone-{$strTokenPost}",
			'gallery'=>"gallery-{$strTokenPost}",
			'fileupload'=>"fileupload-{$strTokenPost}",
			'filesContainer'=>"filesContainer{$strTokenPost}"
		),
		'autoUpload'=>"true"
	));
	
?>
</div>
