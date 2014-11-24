<?php
$this->pageTitle = $user->displayName;

GNAssetHelper::init(array(
	'image' => 'img',
	'css' => 'css',
	'script' => 'js',
));
GNAssetHelper::setBase('myzone_v1');

GNAssetHelper::cssFile('sort-wall');
GNAssetHelper::cssFile('profile-action-top');

GNAssetHelper::scriptFile('jquery.jtruncate.pack', CClientScript::POS_END);
GNAssetHelper::scriptFile('zone.profile', CClientScript::POS_END);


GNAssetHelper::setBase('application.modules.followings.assets');
GNAssetHelper::scriptFile('script.followings', CClientScript::POS_BEGIN);
GNAssetHelper::registerScript('followings', '$.Followings.initLinks($(".js-following-request"));', CClientScript::POS_READY);

$this->renderPartial('//common/user-related', compact('user'));

?>

<?php $this->renderPartial('application.modules.photos.views.templates.popup-photo')?>
<?php $this->renderPartial('application.modules.photos.views.templates.popup-photo-content')?>
<div class="wd-container">
	<div class="wd-center">
		<div class="wd-right-content">
			<!-- How You're Connected -->
			<?php $this->widget('application.modules.friends.components.widgets.ZoneFriendsHowConnected', array(
				'user' => $user,
			)); ?>
			<!-- How You're Connected .end -->
			<!-- People’s you may know -->
			<?php $this->widget('application.modules.friends.components.widgets.ZoneFriendsPeopleYouMayKnow'); ?>
			<!-- People’s you may know .end -->
			<!-- People also viewed -->
			<?php $this->renderPartial('application.modules.users.views.elements.people-also-view')?>
			<!-- People also viewed .end -->
			<!-- YouLook for mobile -->
			<?php $this->renderPartial('application.modules.users.views.elements.youlook-for-mobile')?>
			<!-- YouLook for mobile .end -->
		</div>
		<div class="wd-contain-content">
			<?php $this->renderPartial("application.views.common.header.message-full");?>
			<!-- header line -->
			<?php $this->renderPartial('application.modules.users.views.elements.user-interaction-status', array(
				'user' => $user,
			)); ?>
			<!-- header line .end-->
			<!-- left content -->
			<div class="wd-left-content">
				<?php
				
				$mainAvatar = $user->profile;
				$splitStrImage = explode(".",$mainAvatar->image);
				$photoAvatarId = !empty($splitStrImage[0]) ? $splitStrImage[0] : "";
				?>
				<!-- person avatar -->
				<div class="wd-left-block">
					<div class="wd-topleft-person">
						<div class="wd-primary-avatar custom-avatar-upload wd-primary-avatar-upload">
							<a
								href="<?php echo ZoneRouter::createUrl("/photos/viewPhoto?photo_id=".$photoAvatarId."&album_id={$user->hexID}&type=user")?>"
								class="wd-thumb-img lnkViewPhotoDetail" type='user'
								photo_id='<?php echo $photoAvatarId;?>'
								album_id='<?php echo $user->hexID;?>'
								filename='<?php echo $mainAvatar->image;?>'> 
								<img id="avatarChange" src="<?php echo ZoneRouter::CDNUrl("/upload/user-photos/{$user->hexID}/fill/147-147/{$mainAvatar->image}?album_id={$user->hexID}")?>" alt="<?php echo $user->displayname?>">
							</a>
							
							<?php 
							/**
							 * This zone used change user avatar 
							 **/
							
							if($user->hexID == currentUser()->hexID):
								echo '<div class="image-select-loading"  style=" display:none; background: #ffffff; ">&nbsp;</div>';
							?>
							<span class="wd-uploadavatar-bt" id="triggerUploadAvatar">
								<span class="wd-icon"></span>
								<span class="wd-text">Upload Avatar</span>
							
							</span>
							<?php
								echo '<span class="wd-uploadavatar-bt" style="display:none">';
								echo '<span class="wd-icon"></span>';
								$this->widget('ext.imageSelect.ButtonSelect',  array(
									'path'=>!empty($mainAvatar) ? ZoneRouter::CDNUrl("/upload/user-photos/".$user->hexID."/fill/193-193/" .$mainAvatar['photo']['image']."?album_id=".$user->hexID) : ZoneRouter::CDNUrl("/upload/user-photos/".$user->hexID."/fill/193-193/"),
									'alt'=>$user->displayname,
									'text'=>'Update Avatar',
									'uploadUrl'=>GNRouter::createUrl('/users/edit/change-avatar'),
									'htmlOptions'=>array(
										'class'=>'bgop30 avatarChange'
									),
									'choose'=>'
										var fileArr = input[0].files;
										var typeImages = ["image/gif","image/jpeg","image/png"];
										var errorUploadAvatar = true;
										for ( var i = 0, len =fileArr.length; i < len; i++ ) {
											$.each(typeImages,function(x,y){
												if(fileArr[i].type  == y){
													errorUploadAvatar = false;
												}
											});
										}
										
										if(errorUploadAvatar){
											jAlert("There was a problem with the image file.", "Bad Image", function(){
												$(".image-select-loading").hide();
											});
											
											return false;
										}
										
									',
									'success'=>'
										$("#avatarChange").attr("src", homeURL+"/upload/user-photos/"+jlbd.user.collection.current.user.id+"/fill/147-147/"+responseText.data.image);
										$("#div_image_select_"+imageSelectId+" img").attr("src", responseText.data.image);
										$.each($(".me"),function(x,y){
											var size = $(y).attr("size");
											$(y).attr("src",homeURL+"/upload/user-photos/"+jlbd.user.collection.current.user.id+"/fill/"+size+"/"+responseText.data.image);
										});
										$(".totalPhotoUser").html(responseText.data.count);
										$(".image-select-loading").hide();
									'
								));
								echo '</span>';
							endif;?>
						</div>
						<div class="wd-view-person">
							<h2 class="wd_tt_5"><?php echo $user->displayname;?></h2>
							<?php if($user->hexID != currentUser()->hexID && !empty($profile->status_text)):?>
								<p class="wd-experience-pc"><?php echo $profile->status_text;?></p>
							<?php endif;?>
							<?php
							
							if (!empty($profile->location)):
								echo '<p class="wd-country-user">';
								echo MyZoneHelper::formatLocation($profile->location);
								echo '</p>';
							endif;
							?>
							
						</div>
						<?php
						
						if($user->hexID == currentUser()->hexID):
							// dump($user->profile->tableSchema->columns['lastsyncfbphotos']);
							// dump($user->profile->lastsyncfbphotos,false);
							
							
						?>
							<div class="step3">
								<div class="wd-syncphoto-avatar wd-syncphoto-avatar-loaded">
									<div class="pullImageSyncFb">
									</div>
									<a href="<?php echo ZoneRouter::createUrl("/userphotos?uid={$user->hexID}");?>" class="wd-seamore-pt"><span class="wd-arrow"></span></a>
								</div>
							</div>
							<div class="step2">
								<a class="wd-syncphoto-avatar wd-syncphoto-avatar-loading" href="javascript:void(0)">
									<img src="<?php echo baseUrl();?>/img/front/loading-2.gif" alt="loading" class="wd-icon-loading">
									<span class="wd-text-2">Syncing <span class="wd-text-loading"> ... </span> photos</span>
								</a>
							</div>
							<div class="step1">
								<a class="wd-syncphoto-avatar js-button-sync-photo" href="<?php echo ZoneRouter::createUrl('/facebook/checkPhotoPermission'); ?>">
									<span class="wd-icon-face-sync"></span>
									<span class="wd-text-1">Sync photos </span>
									<span class="wd-text-2">from facebook account</span>
								</a>
							</div>
							<?php if (!empty($user->profile) && isset($user->profile->tableSchema->columns['lastsyncfbphotos']) && !empty($user->profile->lastsyncfbphotos)) : ?>
							<div class="step4">
								<a class="wd-syncphoto-avatar js-button-sync-photo" href="<?php echo ZoneRouter::createUrl('/facebook/checkPhotoPermission'); ?>">
									<span class="wd-icon-face-sync"></span>
									<span class="wd-text-3">Last updated on <span class="timeago-sync" title="<?php echo date(DATE_ISO8601, $user->profile->lastsyncfbphotos); ?>">
									<script> $('.timeago-sync').timeago(); </script>
									</span></span>
									<span class="wd-text-4">Sync Photos Facebook</span>
								</a>
							</div>
							
							<?php endif;?>

						<?php
						else:
						?>
						<div class="wd-action-more-buttons wd-action-more-orange-buttons wd_parenttoggle">
							<?php
							$this->renderPartial('application.modules.users.views.profile._button_action', array(
								'user' => $user,
							));
							?>
						</div>
						<?php
						endif;
						?>
						
						
					</div>
				</div>
				
				
				<?php
				
				$this->widget('widgets.user.UserProperties', array(
					'zoneId'=>IDHelper::uuidFromBinary($user->id,true),
					'type'=>'userNode'
				));
				?>
				
				
			</div>
			
			<!-- left content .end -->
			<!-- main content -->
			<div class="wd-main-content">
				<?php if(currentUser()->hexID == $user->hexID):?>
				<div class="wd-profile-action-top">
					<div class="wd-profile-action-top-content">
						<span class="wd-icon-thumb wd-icon-block-1"></span>
						<div class="wd-leftcontent">
							<a class="wd-close-topmess wd-close-top2 wd-tooltip-hover"  title="Close" target="wd-profile-action-top"></a>
							<h2 class="wd_tt_6">Find people you know</h2>
							<p class="wd-disc">Search by name or look for classmates and coworkers.</p>
							<fieldset class="wd-topsearch-pagelet-form">
								<div class="wd-input-search">
									<form action="<?php echo GNRouter::createUrl('/friends/list/find'); ?>" method="GET">
										<input id="js-people-search" type="text" class="wd-text-search" name="keyword" placeholder="Search...">
										<input type="submit" class="wd-submit wd-tooltip-hover"  title="Search" value="">
									</form>
									<?php
										Yii::import('ext.jqautocomplete.jqAutocomplete');
										$json_options = array(
											'script'=> GNRouter::createUrl('/friends/list/find?autocompete=true&'),
											'varName'=>'keyword',
											'showMoreResults'=>false,
											'valueSep' => null,
											'selectFirst' => false,
											'maxResults'=>10,
											'callback' =>'js:function(obj){
												window.location.href = "'.GNRouter::createUrl("/profile").'/"+obj.username ;
											}',
											'submit' => 'js:function(e) {
												console.log(123);
											}'
											
										);
										jqAutocomplete::addAutocomplete('#js-people-search', $json_options);
									?>
								</div>
							</fieldset>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<?php else:?>
				<div class="wd-profile-action-top bdbno pb5">
					<div class="wd-profile-action-top-content">
						<a class="wd-close-topmess wd-close-top2"></a>
						<span class="wd-icon-thumb wd-icon-block-3"></span>
						<div class="wd-leftcontent">
							<a class="wd-close-topmess wd-close-top2 wd-tooltip-hover"  title="Close" target="wd-profile-action-top"></a>
							<h2 class="wd_tt_6">Invite Tom H to connect on YouLook</h2>
							<p class="wd-disc">How do you know <a href="#" class="wd-username">Tom H</a> ?</p>
							<fieldset class="wd-choice-connect-form">
								<div class="wd-input wd-connect-radio-customer">
									<div class="radio"><span class=""><input type="radio" name="sex" value="Female" class="wd-radio-cus" checked="checked"></span></div><label class="mr25">Colleague</label>
								</div>
								<div class="wd-input wd-connect-radio-customer">
									<div class="radio"><span class=""><input type="radio" name="sex" value="Female" class="wd-radio-cus" checked="checked"></span></div><label class="mr25">Classmate</label>
								</div>
								<div class="wd-input wd-connect-radio-customer">
									<div class="radio"><span class=""><input type="radio" name="sex" value="Female" class="wd-radio-cus" checked="checked"></span></div><label class="mr25">We’ve done business together</label>
								</div>
								<div class="wd-input wd-connect-radio-customer">
									<div class="radio"><span class="checked"><input type="radio" name="sex" value="Female" class="wd-radio-cus" checked="checked"></span></div><label class="mr25">Friend</label>
								</div>
								<p class="wd-note-yl"><span class="wd-text-str">Important:</span> Only invite people you know well and who know you.</p>
								<div class="wd-submit">
									<button class="note-action-top-bt" type="submit" name="yt0">Send invitation</button>
									<span class="wd-or">Or</span><a href="#" class="wd-link">Cancel</a>
								</div>
							</fieldset>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<?php endif;?>
				
				<div class="wd-top-pagelet">
					<?php
					
						$this->widget('widgets.formPost.FormPost',array(
							'authorId'=>currentUser()->hexID,
							'namespaceId'=>$user->hexID,
							'bothType'=>true,
							'type'=>'user',
							'textPostArticle'=>'Status',
							'placeholderPostArticle'=>'',
							'textPostPhoto'=>'Photos',
							'placeholderPostPhoto'=>'Write something...',
							'urlPost'=>ZoneRouter::createUrl('/status/default/create'),
							'bothField'=>false,
							'realTime'=>array(
								'containerObject'=>'#articleSelector',
								'viewArticle'=>'application.views.common.article',
								'status'=>true
							)
							
						));
					
					?>
					
				</div>
				
				<!-- pagelet-stream -->
				<div class="wd-pagelet-stream">
					<?php
					
					$this->widget('widgets.filter.Filter');
					?>

					<ul class="wd-list-stream" id="articleSelector">
						<?php
						$this->renderPartial('//common/activity/wall',array(
							'activities'=>$activities,
							'user'=>$user
						));
						

						
						?>
					</ul>
					<?php
					$this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
						'contentSelector' => '#articleSelector',
						'itemSelector' => 'li#article-item',
						'loadingText' => 'Loading more...',
						'customStyle'=>'overflow: hidden;  width: 135px;  font-size: 12px;  color: #777;  margin: 0 auto 10px;',
						//'pixelsFromNavToBottom' => 20,
						'donetext' => ' ',
						'debug' => false,
						'pages' => $pages,
						'callback'=>' 
							try{
								initPopup();
								jQuery(" .timeago").timeago();
								$(".wd-inputbox textarea").autosize();

								$.Followings.initLinks($(".js-following-request"));
								zone.Common.Event.loadEmoticon();
								zone.photo.init({});

								/*Tooltip*/
								$(".wd-tooltip-hover").tipsy({gravity: "s"});
							}catch(e){
								console.log(e.message);
							}
						',
						'maxPage' => ceil($pages->itemCount/$pages->pageSize),
						'loading'=>array(
							'img'=>baseUrl()."img/front/ajax-loader.gif",
							'msgText'=>'Loading more...'
						)
					));
					?>
				</div>
				
				<!-- pagelet-stream .end -->
			</div>
			<!-- main content .end-->
			
		</div>
		<div class="clear"></div>
	</div>
</div>

<script>
var page = {
		type	: 'ProfileHome',
		id		: '<?php echo currentUser()->hexID;?>'
	};
	
$(document).ready(function(e){
	var infScroller = $('#articleSelector');
	zone.photo.init({
		beforePopupOpen : function() {
			//console.log('pause');
			if (typeof infScroller != "undefined") infScroller.infinitescroll('pause');
		},
		afterPopupOpened : function() {
			
		},
		afterPopupClosed : function() {
			//console.log('resume');
			if (typeof infScroller != "undefined") infScroller.infinitescroll('resume');
		}
	});
});

</script>