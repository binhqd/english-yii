<?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));

GNAssetHelper::setBase('application.modules.friends.assets');
GNAssetHelper::scriptFile('script.friends', CClientScript::POS_BEGIN);

GNAssetHelper::setBase('myzone_v1');
GNAssetHelper::cssFile('viewall-content-2col', CClientScript::POS_HEAD);
GNAssetHelper::cssFile('streamstory-followes-composer', CClientScript::POS_HEAD);
GNAssetHelper::cssFile('list-followers', CClientScript::POS_HEAD);
GNAssetHelper::scriptFile('jquery.wookmark.min', CClientScript::POS_END);

GNAssetHelper::scriptFile('common-object-node-list-followes', CClientScript::POS_END);
GNAssetHelper::scriptFile('imagesloaded.pkgd.min', CClientScript::POS_END);
?>

<?php $this->renderPartial('//common/user-related', compact('user'))?>

<div class="wd-container">
	<div class="wd-center wd-center-content-layout3">
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
			<!-- header line -->
			<?php $this->renderPartial('application.modules.users.views.elements.user-interaction-status', array(
				'user' => $user,
			)); ?>
			<!-- header line .end-->
			<!-- main content -->
			<div class="wd-main-content">
				<div class="wd-viewallobjn-topsearch-form">
					<?php if (!currentUser()->isGuest && currentUser()->id == $user->id) : ?>
					<div class="wd-search-activities wd_parenttoggle floatL">
						<a href="#" class="wd-activities-bt wd_toggle_bt">Friends<span class="wd-arrow"></span></a>
						<div class="wd-search-activities-toggle wd_toggle">
							<div class="wd-search-activities-content">
								<ul>
									<li><a href="<?php echo GNRouter::createUrl('/friends/list/pendings'); ?>">Pendings</a></li>
									<li><a href="<?php echo GNRouter::createUrl('/friends/list/ignorances'); ?>">Ignorances</a></li>
									<li><a href="<?php echo GNRouter::createUrl('/friends/list/find'); ?>">Find People</a></li>
								</ul>
							</div>
						</div>
					</div>
					<?php endif; ?>
					<fieldset class="wd-topsearch-pagelet-form floatR">
						<div class="wd-input-search">
							<input type="text" placeholder="Search..." class="wd-text-search"/>
							<input type="submit" value="" class="wd-submit"/>
						</div>
					</fieldset>
					<div class="clear"></div>
				</div>
				<div class="wd-pagelet-stream-wiew">
					<?php
					if(!empty($zoneRelatedRequest)){
						echo '<ul class="wd-streamstory-lo2">';
						foreach($zoneRelatedRequest as $key=>$value){
							$users = GNUser::model()->getUserInfo(IDHelper::uuidToBinary($value->user_id));
							$strToken = md5(uniqid(32));
					?>
						<li class="wd-streamstory-lo2-item ml0" id="<?php echo $strToken;?>">
							<div class="wd-streamstory-followes-composer">
								<div class="wd-streamstory-followes-content">
									<div class="wd-makerpage-objectnode-content">
										<a href="#" class="wd-avatar"><img width="54" height="54" alt="" src="<?php echo GNRouter::createUrl("/");?>/upload/user-photos/<?php echo $value->user_id;?>/fill/54-54/<?php echo $users->profile->image;?>"></a>
										<div class="wd-makerpage-objectnode-info">
											<h4>
												<a href="<?php echo GNRouter::createUrl("/profile/{$users->username}");?>">
													<?php echo $users->displayname;?>
												</a>
											</h4>
											<?php if(!empty($users->profile->status_text)){ 
												echo "<p><span>{$users->profile->status_text}</span></p>";
											}
											if(!empty($users->profile->location)){ 
												echo "<p>{$users->profile->location}</p>";
											} ?>
											<div class="<?php echo ($value->active == 0) ? "wd-bottom-user-status" : "wd-bottom-user-status wd-bottom-user-status-friend";?>">
												<!--<ul class="wd-user-interaction-status">
													<li><a href="#"><span class="wd-value">236</span><span class="wd-tt">followings</span></a></li>
													<li><a href="#"><span class="wd-value">26</span><span class="wd-tt">contributaions</span></a></li>
												</ul>-->
												<div class="wd-friend-action">
													
													<a href="javascript:void(0)" token="<?php echo $strToken;?>" strId="<?php echo $value->id;?>" class="wd-friend-status wd-friend-rqbt wd_toggle_bt request-related"><span class="wd-icon"></span><span class="wd-value">Request Related</span></a>
												</div>
											</div>
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</li>

					<?php
						}
						echo '</ul>';
					}
					?>
				</div>
				<!--<div class="wd-list-stream-loading"><img alt="loading" src="/myzone_v1/img/front/ajax-loader.gif"><span>Loading more...</span></div>-->
			</div>
			<!-- main content .end-->
		</div>
		<div class="clear"></div>
	</div>
</div>
<script>
$().ready(function(e){
	$('.request-related').on('click',function(e){
		
		var _this = $(this);
		$("#"+_this.attr("token")).fadeOut(500);
		$.ajax({
			url:homeURL+"/users/family/approved",
			data:{id:_this.attr("strId")},
			success:function(res){
				_this.remove();
			}
		});
	});
});
</script>