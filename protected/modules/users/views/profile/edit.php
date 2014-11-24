<?php
GNAssetHelper::init(array(
'image'		=> 'img',
'css'		=> 'css',
'script'	=> 'js'
));

GNAssetHelper::setBase('myzone_v1');
GNAssetHelper::cssFile('headline-submit');
GNAssetHelper::cssFile('profile-user-view-layout');
GNAssetHelper::cssFile('customize-public-profile');
GNAssetHelper::cssFile('popup-edit-lc');
GNAssetHelper::cssFile('uniform.default');
GNAssetHelper::cssFile('uniform-default-custom');
GNAssetHelper::cssFile('sync-your-photo');

GNAssetHelper::scriptFile('jquery.uniform.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('edit.profile', CClientScript::POS_END);

$arrClassModel = array();
$this->renderPartial('//common/user-related', compact('user')); 

	
$avatars = ZoneUserAvatar::model()->getAvatars(IDHelper::uuidFromBinary($user->id,true), 5);

$userProfile = $user->profile;
?>
<script>
	var currentUserHexId = "<?php echo currentUser()->hexID;?>";
	var optionsGender = null;
</script>

<div class="wd-container">
<div class="wd-center">
			<div class="wd-right-content">
				<!-- How You're Connected -->
				<?php $this->widget('application.modules.friends.components.widgets.ZoneFriendsHowConnected', array('user' => $user	)); ?>
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
<!-- header line -->
				<?php $this->renderPartial('application.modules.users.views.elements.user-interaction-status', array(	'user' => $user	));  ?>
				
				<div class="wd-headline-submit">
					<div class="wd-headtitle-left wd-headtitle-green bgop90">
						<h2 class="wd-tt-l1">Edit profile</h2>
					</div>
					<div class="wd-headline-main wd-headtitle-green">
						<div class="wd-headaction-buttons floatR">
							<input type="button" class="wd-form-bt-1 wd-save-button" value="Done edit" onclick="window.location.href='<?php echo GNRouter::createUrl('/profile');?>'">
							<!--<input type="submit" class="wd-form-bt-1 wd-cancel-button" value="Cancel">-->
						</div>
					</div>
				</div>
<!-- header line .end-->
<!-- left content -->
				<div class="wd-left-content wd-edit-profile-user-lc">
					<!-- person avatar -->
					<div class="wd-left-block">
						<div class="wd-topleft-person">
							<div class="wd-view-person wd-edit-block">
								<span class="wd-edit-button wd-icon-edit-28" id="frameinfo1"></span>
								<div class=" wd-view-person-edit " id="frameinfo2">
									<h2 class="wd_tt_1" id="textEditUserName"><?php echo $user->displayname;?></h2>
									<label id="locationAndStatus">
										<?php
										if(!empty($userProfile->status_text)){
										?>
										<p><strong><?php echo $userProfile->status_text;?> </strong></p>
										<?php
										}
										?>
										
										<?php
										if (!empty($userProfile->location)):
											
											$location = ZoneArticleNamespace::model()->nodeInfo($userProfile->location);
											// dump($userProfile->location);
											if(!empty($location)):
											
										?>
										<p class="wd-gray-cl-1 mt5">
											<?php echo $location['name'];?>
										</p>
										<?php
											else:
										?>
											<p class="wd-gray-cl-1 mt5">
												<?php echo $userProfile->location;?>
											</p>
										<?php
											endif;
										endif;
										?>
									</label>
									
								</div>

								<div class="wd-popup-edit-lc wd-view-person-edit-content" id="frameinfo3">
									<span class="wd-icon-edit-2"></span>
									
									<?php /** @var BootActiveForm $form */
									$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
										'id'=>'editProfile',
										'action'=>GNRouter::createUrl('/users/edit/info'),
										'htmlOptions'=>array('class'=>'well', 'enctype' => 'multipart/form-data'),
									));
									
									?>
									
									<fieldset class="wd-edit-form-lc">
										<div class="wd-input">
											<label for="name">Name</label>
											
											<?php echo $form->textFieldRow($user, 'firstname', array('class'=>'wd-input-2 mr10')); ?>
											<?php echo $form->textFieldRow($user, 'lastname', array('class'=>'wd-input-2')); ?>
											
											<!--<div class="wd-dis-form-toogle">
												<span class="wd_tt_toogle wd-former-name"><span class="wd-arrow"></span>Former Name</span>
												<div class="wd-dis-form-toogle-ct">
													<input class="wd-input-2" type="text" value="" name="fname" id="fname">
													<label class="wd-tt-f clear">Visible to</label>
													<label class="wd-input"><input type="radio" name="choice-show" value="my-connections"><span class="wd-lb">My Connections</span></label>
													<label class="wd-input"><input type="radio" name="choice-show" value="my-network"><span class="wd-lb">My Network</span></label>
													<label class="wd-input"><input type="radio" name="choice-show" value="Everyone"><span class="wd-lb">Everyone</span></label>
												</div>
											</div>-->
										</div>
										<span id="errorMessageFormEdit" style="color: red;font-size: 12px;margin-top: -10px;float: left;">
											
										</span>
										<div class="wd-input">
											<span for="professional">Your professional headline</span>
											
											<?php echo $form->textFieldRow($userProfile, 'status_text', array('class'=>'wd-input-1')); ?>
											<div class="wd-dis-form-toogle">
												<span class="wd_tt_toogle wd-blue-11">Show examples</span>
												<div class="wd-dis-form-toogle-ct">
													<p class="wd-ex">Experienced Transportation Executive, Web Designer and Information Architect, Visionary Entrepreneur and Investor</p>
												</div>
											</div>
										</div>
										
										<div class="wd-input ">
											<span for="country">Location</span>
											<?php 
												
												echo $form->dropDownListRow($userProfile, 'location', $locations,array(
													'class'=>'wd-input-1'
												)); 
											?>
											
										</div>
										
										<div class="wd-submit">
											<input type="button" value="Save" class="wd-form-bt-1 wd-save-button wd-close-edit mz-submit" id="btnSubmitProfile">
											<input type="reset"  style="display:none">
											<input type="button" value="Cancel" onclick="cancel('info');" class="wd-form-bt-1 wd-cancel-button wd-close-edit">
										</div>
									</fieldset>
									<?php $this->endWidget(); ?>
									
								</div>
							</div>
							
							<div class="wd-person-img wd-person-img-edit" >
								<a class="wd-main-image" href="javascript:void(0)" style="position:relative;" >
									<?php
									if (!empty($avatars)) {
										$mainAvatar = array_shift($avatars);
									}
									
									$this->widget('ext.imageSelect.ImageSelect',  array(
										'path'=>!empty($mainAvatar) ? ZoneRouter::CDNUrl("/upload/user-photos/".$user->hexID."/fill/193-193/" .$mainAvatar['photo']['image']) : ZoneRouter::CDNUrl("/upload/user-photos/".$user->hexID."/fill/193-193/"),
										'alt'=>$user->displayname,
										'text'=>'Update profile picture',
										'widthParent'=>'193',
										'heightParent'=>'193',
										'uploadUrl'=>GNRouter::createUrl('/users/edit/change-avatar'),
										'htmlOptions'=>array(
											'class'=>'bgop30 avatarChange'
										),
										'choose'=>'
										',
										'success'=>'
											/*
											var src = $(".avatarChange").attr("src");
											$("#div_image_select_"+imageSelectId+" img").attr("src", responseText.thumbnail_url);
											$.each($(".me"),function(x,y){
												var size = $(y).attr("size");
												$(y).attr("src",homeURL+responseText.thumbnail_url);
											});
											if($("#thumb-avatar li").length >=4) $("#thumb-avatar li:last").remove();
											
											$("#thumb-avatar").prepend("<li class=\'wd-mlb-img wd-first-elm\'><a href=\'#\' class=\'wd-thumb-img\'><img class=\'bgop30\' src="+src+"></a></li>");
											*/
											var currentLocation = window.location.href;
											window.location = currentLocation;
										'
									));
									?>
								</a>
								<ul class="wd-gallery-1" id="thumb-avatar">
									<?php 
									if(!empty($avatars)):
									foreach ($avatars as $key=>$item):?>
									<li class="wd-<?php echo ($key<=1) ? "mlb":"ml";?>-img <?php echo ($key==0) ? "wd-first-elm":"";?>">
										<a href="#" class="wd-thumb-img">
											<img class="bgop30" src="<?php echo ZoneRouter::CDNUrl("/upload/user-photos/".$user->hexID."/fill/91-91/" . $item['photo']['image'] . "&album_id={$user->hexID}")?>"/> 
										</a>
									</li>
									<?php endforeach;
									endif;
									?>
								</ul>
								
							</div>
						</div>
					</div>
<!-- person avatar .end-->
<!-- summary bgop30 -->
					
					<?php 
					echo $this->renderPartial('widgets.user.views.profile.edit-info',array(
						'sumary'=>$sumary,
						'acceptEdit'=>true,
						'token'=>$token,
						'months'=>$months,
						'days'=>$days,
						'years'=>$years,
						'propertiesInfomation'=>$propertiesInfomation,
						'constructsBasic'=>$constructsBasic,
						'user'=>$user
					));
					?>
					

					
<!-- summary .end -->
<!---Information -->
					<?php
					// dump($constructsBasic);
					?>
					
<!---Information .end -->
<!--Experience-->
					<?php
					// dump($constructsOther);
					
					echo $this->renderPartial('widgets.user.views.profile.other-properties',array(
						'constructsOther'=>$constructsOther,
						'acceptEdit'=>true,
						'token'=>$token,
						'months'=>$months,
						'days'=>$days,
						'years'=>$years,
						'locations'=>$locations,
						'results'=>$results,
					));
					
					
					?>
					

				</div>
<!-- left content .end -->
<!-- main content -->
				<div class="wd-main-content">
					<div class="wd-customiz-public-profile bbor-solid">
						<h2 class="wd_tt_n3">Customize Your Public Profile</h2>
						<p class="wd-customiz-intro">Control how you appear when people search for you on Google, Yahoo!, Bing, etc.</p>
						<div class="wd-customiz-content-form">
							<fieldset class="wd-customiz-form">
								<div class="wd-everyone-content wd-make-privacy-pro">
									<div class="wd-input wd-input-radio-cus">
										<div class="radio"><span><input type="radio" name="choice-show" value="no-one" class="wd-radio-cus"></span></div>
										<p class="wd-tt-1">Make my public profile visible to <span class="bold">no one</span></p>
									</div>
								</div>
								<div class="wd-everyone-content wd-make-public-pro">
									<div class="wd-input wd-input-radio-cus">
										<div class="radio"><span><input type="radio" name="choice-show" value="everyone" class="wd-radio-cus"></span></div>
										<p class="wd-tt-1">Make my public profile visible to <span class="bold">everyone</span></p>
									</div>
									<div class="wd-everyone-detail-content">
										<div class="wd-input wd-input-checkbox-cus">
											<div class="checker disabled"><span><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus wd-check-cus-dis" disabled=""></span></div>
											<div class="wd-input-right">
												<p class="wd-tt-2">Basics</p>
												<p class="wd-tt-4">Name, industry, location, number of recommendations</p>
											</div>
										</div>
										<div class="wd-input wd-input-checkbox-cus">
											<div class="checker"><span class="checked"><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus" checked="CHECKED"></span></div>
											<div class="wd-input-right">
												<p class="wd-tt-2">Picture</p>
											</div>
										</div>
										<div class="wd-input wd-input-checkbox-cus">
											<div class="checker"><span><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus"></span></div>
											<div class="wd-input-right">
												<p class="wd-tt-2">Headline </p>
											</div>
										</div>
										<div class="wd-input wd-input-parent wd-input-checkbox-cus">
											<div class="checker"><span><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus wd-check-cus-hc"></span></div>
											<div class="wd-input-right">
												<p class="wd-tt-2">Current Positions</p>
												<div class="wd-input wd-input-child wd-input-checkbox-cus">
													<div class="checker"><span><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus"></span></div>
													<div class="wd-input-right">
														<p class="wd-tt-3">Show detail</p>
													</div>
												</div>
											</div>
										</div>
										<div class="wd-input wd-input-checkbox-cus">
											<div class="checker"><span><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus"></span></div>
											<div class="wd-input-right">
												<p class="wd-tt-2">Languages</p>
											</div>
										</div>
										<div class="wd-input wd-input-checkbox-cus">
											<div class="checker"><span><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus"></span></div>
											<div class="wd-input-right">
												<p class="wd-tt-2">Skills</p>
											</div>
										</div>
										<div class="wd-input wd-input-parent wd-input-checkbox-cus">
											<div class="checker"><span><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus wd-check-cus-hc"></span></div>
											<div class="wd-input-right">
												<p class="wd-tt-2">Education</p>
												<div class="wd-input wd-input-child wd-input-checkbox-cus">
													<div class="checker"><span><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus"></span></div>
													<div class="wd-input-right">
														<p class="wd-tt-3">Show detail</p>
													</div>
												</div>
											</div>
										</div>
										<div class="wd-input wd-input-parent wd-input-checkbox-cus">
											<div class="checker"><span><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus wd-check-cus-hc"></span></div>
											<div class="wd-input-right">
												<p class="wd-tt-2">Additional Information</p>
												<div class="wd-input wd-input-child wd-input-checkbox-cus">
													<div class="checker"><span><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus"></span></div>
													<div class="wd-input-right">
														<p class="wd-tt-3">Show detail</p>
													</div>
												</div>
											</div>
										</div>
										<div class="wd-input wd-input-checkbox-cus">
											<div class="checker"><span><input type="checkbox" name="choice-show" value="everyone" class="wd-check-cus"></span></div>
											<div class="wd-input-right">
												<p class="wd-tt-2">Interested In...</p>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
					<div class="wd-sync-your-photo">
						<h3>Sync your photos facebook</h3>
						<p>Photo syncing lets you save the photos on account Facebook to YouLook profile</p>
						<p class="wd-img"><img src="<?php echo baseUrl();?>/img/front/img-sync-photo.png" alt=""></p>
						<p class="wd-icon-refresh"><img src="<?php echo baseUrl();?>/img/front/icon-refresh.png" alt=""></p>
						<!--div class="wd-get-started">
							<a href="#" class="wd-button">Get started</a>
						</div-->
						<div class="wd-syncing-photo js-syncing-photo" style="display:none">
							<p>Syncing 10 photos...<span>10%</span></p>
						</div>
						<div class="wd-successful-progress js-successful-progress" style="display:none">
							<p>Successful progress!</p>
							<a href="<?php echo GNRouter::createUrl('/userphotos') . '?uid='.currentUser()->hexID; ?>" class="wd-arrow-link">View my photos</a>
						</div>
						<div class="wd-last-update js-last-update-photo">
							<?php if (!empty($user->profile) && isset($user->profile->tableSchema->columns['lastsyncfbphotos']) && !empty($user->profile->lastsyncfbphotos)) : ?>
							<p>Last updated on <span class="timeago" data-title="<?php echo date(DATE_ISO8601, $user->profile->lastsyncfbphotos); ?>"><?php echo date('Y-m-d H:i:s', $user->profile->lastsyncfbphotos); ?></span></p>
							<?php endif; ?>
							<!--p><strong>30 photos has been sync</strong></p-->
							<a href="<?php echo GNRouter::createUrl('/facebook/checkPhotoPermission'); ?>" class="wd-button js-button-sync-photo">Sync Facebook photos</a>
						</div>
					</div>
				</div>
<!-- main content .end-->
			</div>
			<div class="clear"></div>
		</div>
</div>
<?php
/**
 * After get code then remove file CFormModel validate 
 **/

if(!empty($arrClassModel)){
	foreach($arrClassModel as $key=>$keyFile){
		if(file_exists(Yii::getPathOfAlias('rules')."/{$keyFile}.php")){
			@unlink(Yii::getPathOfAlias('rules')."/{$keyFile}.php");
		}
	}
}

?>
<script type="text/javascript">
var SYNC_URL = '<?php echo GNRouter::createUrl('/facebook/syncPhotos'); ?>';
var source = [];
var total = 0;
var currentDone = 0;
var i = 0;

/**
 * This method is used to migrate photos
 */
function migrate(id) {
	$.ajax({
		url : homeURL + '/facebook/getPhoto?id=' + id,
		success : function(res) {
			if (res.error) {
				// console.log(res);
			} else {
				currentDone++;
				var percent = Math.round(currentDone / total * 100);
				$('.js-syncing-photo p span').html(percent + '%');
				i++;
				if (typeof source[i] != "undefined") migrate(source[i].id);
				if (currentDone == total) {
					$('.js-syncing-photo').css('display', 'none');
					$('.js-successful-progress').css('display', 'block');
				}
			}
		} 
	});
}

/**
 * This method is used to sync facebook photos
 */
function syncPhotos()
{
	$('.js-last-update-photo').css('display', 'none');
	$.ajax({
		url: SYNC_URL,
		type: 'POST',
		dataType: "json",
		beforeSend: function() {
			$('.js-syncing-photo p').html('Contacting Facebook...');
			$('.js-syncing-photo').css('display', 'block');
		},
		success: function(response) {
			if (parseInt(response.done) < parseInt(response.total)) {
				var intSync = response.total - response.done;
				total = parseInt(response.total);
				source = response.source;
				currentDone = parseInt(response.done);
				$('.js-syncing-photo p').html('Syncing '+intSync+' photos...<span>0%</span>');
				if (typeof source[0] != "undefined") migrate(source[0].id);
				if (typeof source[1] != "undefined") migrate(source[1].id);
				if (typeof source[2] != "undefined") migrate(source[2].id);
				i = 2;
			} else {
				$('.js-syncing-photo').css('display', 'none');
				$('.js-successful-progress').css('display', 'block');
			}
		}
	});
}

$(window).ready(function(){
	$('.js-button-sync-photo').click(function(){
		var href = $(this).attr('href');
		var win = window.open(href, "Check Photo Permission", 'width=800, height=400');

		var pollTimer = window.setInterval(function() { 
			try {
				if (win.document.URL.indexOf(SYNC_URL) != -1) {
					var isAllow = win.document.URL.indexOf('error=access_denied') == -1;
					window.clearInterval(pollTimer);
					win.close();
					if (isAllow) syncPhotos();
				}
			} catch(e) {
			}
		}, 500);

		return false;
	});
});
$(document).ajaxSend(function() {
	$( ".mz-submit" ).attr('disabled','disabled');
});
$(document).ajaxSuccess(function() {
	$( ".mz-submit" ).removeAttr('disabled');
});
$(document).ajaxComplete(function() {
  
});
$(document).ajaxError(function() {
  $( ".mz-submit" ).removeAttr('disabled');
});
</script>