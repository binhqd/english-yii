<?php
GNAssetHelper::init(array(
	'image' => 'img',
	'css' => 'css',
	'script' => 'js',
));
GNAssetHelper::setBase('myzone_v1');

GNAssetHelper::cssFile('type-mainPoster');
GNAssetHelper::cssFile('type-combined');
GNAssetHelper::cssFile('type-information-node');
GNAssetHelper::cssFile('list-info-as-2');
GNAssetHelper::cssFile('list-info-as-3');
GNAssetHelper::cssFile('list-6');
GNAssetHelper::cssFile('user-welcome');
GNAssetHelper::cssFile('type-main-top-content');

GNAssetHelper::cssFile('right-chat');

GNAssetHelper::cssFile('common-user-node');

GNAssetHelper::scriptFile('jquery.easing.1.3.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.mousewheel.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.touchSwipe.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.carouFredSel-6.2.0-packed', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.jscrollpane.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.tipsy', CClientScript::POS_END);
GNAssetHelper::scriptFile('common-user-welcome', CClientScript::POS_END);
GNAssetHelper::scriptFile('common-scroll-sidebar', CClientScript::POS_END);
?>
<div class="wd-container wd-container-welcome">
	<div class="wd-center">
		<div class="wd-container-wr">
			<div class="wd-left-content wd-auto-fixed">
				<div class="wd-type-combined">
					<div id="cInfo"></div>
					<?php $this->widget('GNTemplateEngine', array(
						'data'	=> array(123), // Dữ liệu có sẵn
						'template'	=> array(
							'id'	=> 'infoTmpl', // ID của template, có thể đặt tên bất kỳ, miễn sao đừng trùng
							'path'	=> 'application.modules.users.views.welcome._infoTmpl', // Đường dẫn đến template
						),
						'container'	=> array(
							'selector'	=> '#cInfo', // selector của container chứa dữ liệu sau khi render
							'type'		=> GNTemplateEngine::ADD_APPEND // Hiện tại hỗ trợ 2 kiểu là ADD_APPEND và ADD_PREPEND
						),
						'scriptPos'	=> GNTemplateEngine::POS_IMME, // Hiện tại hỗ trợ 2 kiểu là POS_IMME (render ngay lập tức) và POS_READY
						'callbacks'	=> array(
							'afterRender'	=> '',
						),
					)); ?>
					<!-- Favorites -->
					<div class="wd-type-combined-item">
						<h2 class="wd-tt-7">Favorites</h2>
						<ul class="wd-list-info-as-3">
							<li class="wd-active">
								<a href="#" class="wd-prlink">
									<span class="wd-icon-16 wd-icon-welcome"></span>
									<span class="wd-text-prlink">Welcome</span>
								</a>
							</li>
							<li>
								<a href="#" class="wd-prlink">
									<span class="wd-icon-16 wd-icon-profile"></span>
									<span class="wd-text-prlink">My Profile</span>
								</a>
							</li>
							<li>
								<a href="#" class="wd-prlink">
									<span class="wd-icon-16 wd-icon-followings"></span>
									<span class="wd-text-prlink">Followings</span>
								</a>
							</li>
							<li>
								<a href="#" class="wd-prlink">
									<span class="wd-icon-16 wd-icon-articles"></span>
									<span class="wd-text-prlink">Articles</span>
								</a>
							</li>
							<li>
								<a href="#" class="wd-prlink">
									<span class="wd-icon-16 wd-icon-photos"></span>
									<span class="wd-text-prlink">Photos</span>
								</a>
							</li>
							<li>
								<a href="#" class="wd-prlink">
									<span class="wd-icon-16 wd-icon-addvideo"></span>
									<span class="wd-text-prlink">Video</span>
								</a>
							</li>
						</ul>
					</div>
					<!-- Favorites.end  -->
					<!-- Topics -->
					<div class="wd-type-combined-item">
						<h2 class="wd-tt-7">Topics</h2>
						<ul class="wd-list-info-as-3">
							<li>
								<a href="javascript:void(0)" class="wd-prlink create-new-topic-handler">
									<span class="wd-icon-16 wd-icon-create-new-topic"></span>
									<span class="wd-text-prlink">Create a new Topic</span>
								</a>
							</li>
							<li>
								<a href="#" class="wd-prlink">
									<span class="wd-icon-16 wd-icon-most-popular"></span>
									<span class="wd-text-prlink">Most popular</span>
								</a>
							</li>
							<li>
								<a href="#" class="wd-prlink">
									<span class="wd-icon-16 wd-icon-find-follow"></span>
									<span class="wd-text-prlink">Find and Follow</span>
								</a>
							</li>
						</ul>
					</div>
					<!-- Topics .end -->
					<!-- friend requests -->
					<div id="cPendingFriends"></div>
					<?php $this->widget('GNTemplateEngine', array(
						'data'	=> array(
							'url'	=> ZoneRouter::createUrl("/welcome/pendingFriends"),
							'type'	=> 'ajax',
							'responseData'	=> 'res'
						), // Dữ liệu request ajax
						'template'	=> array(
							'id'	=> 'pendingFriendsTmpl', // ID của template, có thể đặt tên bất kỳ, miễn sao đừng trùng
							'path'	=> 'application.modules.users.views.welcome._pendingFriendsTmpl', // Đường dẫn đến template
						),
						'container'	=> array(
							'selector'	=> '#cPendingFriends', // selector của container chứa dữ liệu sau khi render
							'type'		=> GNTemplateEngine::ADD_APPEND // Hiện tại hỗ trợ 2 kiểu là ADD_APPEND và ADD_PREPEND
						),
						'scriptPos'	=> GNTemplateEngine::POS_IMME, // Hiện tại hỗ trợ 2 kiểu là POS_IMME (render ngay lập tức) và POS_READY
						'callbacks'	=> array(
							'afterRender'	=> '
								$.Friends.initLinks(rendered.find(".js-friend-request"));
							',
						),
					)); ?>
					<!-- friend requests .end -->
				</div>
			</div>
			<div class="wd-main-content-wr">
				<div class="wd-new-user-node-content">
					<p class="wd-text-welcome">Welcome to YouLook, Matt.</p>
					<!-- update profile -->
					<div class="wd-type-update-profile">
						<div class="wd-user-profile-content">
							<h3 class="wd-tt-node"><span class="bg-number bg-number-green">1</span>Update your profile so friends can find you</h3>
							<fieldset>
								<div id='formFieldContainer'></div>
								<div class="wd-submit">
									<input type="submit" value="Save Profile Info" />
								</div>
							</fieldset>
							<?php $this->widget('GNTemplateEngine', array(
								'data'	=> $form['userinfo'],
								'template'	=> array(
									'id'	=> 'profileFormTmpl',
									'path'	=> 'application.modules.users.views.welcome._profileFormTmpl'
								),
								'container'	=> array(
									'selector'	=> '#formFieldContainer',
									'type'		=> GNTemplateEngine::ADD_APPEND
								),
								'scriptPos'	=> GNTemplateEngine::POS_IMME,
								'callbacks'	=> array(
									'afterRender'	=> ""
								)
							)); ?>
						</div>
					</div>
					<!-- update profile .end -->
					<!-- upload image profile -->
					<div class="wd-type-upload-image-profile">
						<div class="wd-user-profile-content">
							<h3 class="wd-tt-node"><span class="bg-number bg-number-purple">2</span>Upload a profile picture</h3>
							<div class="wd-upload-image">
								<img class="wd-avatar-user" src="<?php echo baseUrl(); ?>img/front/avatar-default-193.jpg" alt="avatar" />
								<div class="wd-syns-picture">
									<a href="#">Sync Photos</a>
									<p>From your Facebook</p>
									<p class="wd-line-middle"><span>OR</span></p>
									<a href="#">Upload Photos</a>
									<p>From your computer</p>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
					<!-- upload image profile .end -->
					<!-- find topic -->
					<div class="wd-type-find-topic">
						<div class="wd-user-profile-content">
							<h3 class="wd-tt-node"><span class="bg-number bg-number-purple">3</span>Find Topic You May Like</h3>
							<div class="wd-type-tabs">
								<ul class="wd-type-information-node-tabs">
									<li class="wd-active"><a href="#wd-tab1">People</a></li>
									<li><a href="#wd-tab2">Movies</a></li>
									<li><a href="#wd-tab3">Culture</a></li>
								</ul>
								<div class="wd-tabcontainer">
									<div id="wd-tab1" class="wd-type-information-node-tabs-pannel">
										<div class="wd-type-categories">
											<div class="control-div">
												<span class="wd-prev-type">prev</span>
												<span class="wd-next-type">next</span>
											</div>
											<ul class="wd-node-categories">
												<li>
													<a href="#" class="wd-image"><img src="<?php echo baseUrl(); ?>img/thumb/img-user-welcome-01.jpg" alt="Tom Cruise" height="174" width="174"/></a>
													<h4 class="wd-name"><a href="#">Tom Cruise</a></h4>
													<p>1.556 Followers</p>
													<a href="#" class="wd-gray-bt wd-follow-bt-3">Follow</a>
												</li>
												<li>
													<a href="#" class="wd-image"><img src="<?php echo baseUrl(); ?>img/thumb/img-user-welcome-02.jpg" alt="Tom Cruise" height="174" width="174"/></a>
													<h4 class="wd-name"><a href="#">Cameron Diaz</a></h4>
													<p>896 Follower</p>
													<a href="#" class="wd-gray-bt wd-follow-bt-3">Unfollow</a>
												</li>
												<li>
													<a href="#" class="wd-image"><img src="<?php echo baseUrl(); ?>img/thumb/img-user-welcome-03.jpg" alt="Tom Cruise" height="174" width="174"/></a>
													<h4 class="wd-name"><a href="#">Leonardo DiCaprio</a></h4>
													<p>546 Follower</p>
													<a href="#" class="wd-gray-bt wd-follow-bt-3">Follow</a>
												</li>
												<li>
													<a href="#" class="wd-image"><img src="<?php echo baseUrl(); ?>img/thumb/img-user-welcome-01.jpg" alt="Tom Cruise" height="174" width="174"/></a>
													<h4 class="wd-name"><a href="#">Tom Cruise</a></h4>
													<p>1.556 Followers</p>
													<a href="#" class="wd-gray-bt wd-follow-bt-3">Follow</a>
												</li>
												<li>
													<a href="#" class="wd-image"><img src="<?php echo baseUrl(); ?>img/thumb/img-user-welcome-02.jpg" alt="Tom Cruise" height="174" width="174"/></a>
													<h4 class="wd-name"><a href="#">Cameron Diaz</a></h4>
													<p>896 Follower</p>
													<a href="#" class="wd-gray-bt wd-follow-bt-3">Unfollow</a>
												</li>
												<li>
													<a href="#" class="wd-image"><img src="<?php echo baseUrl(); ?>img/thumb/img-user-welcome-03.jpg" alt="Tom Cruise" height="174" width="174"/></a>
													<h4 class="wd-name"><a href="#">Leonardo DiCaprio</a></h4>
													<p>546 Follower</p>
													<a href="#" class="wd-gray-bt wd-follow-bt-3">Follow</a>
												</li>
												<li>
													<a href="#" class="wd-image"><img src="<?php echo baseUrl(); ?>img/thumb/img-user-welcome-01.jpg" alt="Tom Cruise" height="174" width="174"/></a>
													<h4 class="wd-name"><a href="#">Tom Cruise</a></h4>
													<p>1.556 Followers</p>
													<a href="#" class="wd-gray-bt wd-follow-bt-3">Follow</a>
												</li>
												<li>
													<a href="#" class="wd-image"><img src="<?php echo baseUrl(); ?>img/thumb/img-user-welcome-02.jpg" alt="Tom Cruise" height="174" width="174"/></a>
													<h4 class="wd-name"><a href="#">Cameron Diaz</a></h4>
													<p>896 Follower</p>
													<a href="#" class="wd-gray-bt wd-follow-bt-3">Unfollow</a>
												</li>
												<li>
													<a href="#" class="wd-image"><img src="<?php echo baseUrl(); ?>img/thumb/img-user-welcome-03.jpg" alt="Tom Cruise" height="174" width="174"/></a>
													<h4 class="wd-name"><a href="#">Leonardo DiCaprio</a></h4>
													<p>546 Follower</p>
													<a href="#" class="wd-gray-bt wd-follow-bt-3">Follow</a>
												</li>
											</ul>
										</div>
									</div>
									<div id="wd-tab2" class="wd-type-information-node-tabs-pannel">
										
									</div>
									<div id="wd-tab3" class="wd-type-information-node-tabs-pannel">

									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- find topic .end -->
					<!-- end people -->
					<div class="wd-type-add-people">
						<div class="wd-user-profile-content">
							<h3 class="wd-tt-node"><span class="bg-number bg-number-purple">4</span>Add people you know</h3>
							<div class="wd-add-people-content">
								<ul class="wd-list-6">
									<li>
										<a href="#" class="wd-gray-bt wd-add-action-bt-2">Friend<span class="wd-arrow"></span></a>
										<a class="wd-avatar" href="#"><img alt="" src="<?php echo baseUrl(); ?>img/thumb/photo-75x75-1.jpg" height="75" width="75"></a>
										<div class="wd-right-list-content mt20">
											<h3 class="wd-tt"><a href="#" class="wd-text-b">Helen Vieth</a></h3>
											<p class="wd-desc"><a href="#" class="wd-text-g">Design Manager at DNT Company</a></p>
										</div>
									</li>
									<li>
										<a href="#" class="wd-gray-bt wd-add-action-bt-2">Friend<span class="wd-arrow"></span></a>
										<a class="wd-avatar" href="#"><img alt="" src="<?php echo baseUrl(); ?>img/thumb/photo-75x75-2.jpg" height="75" width="75"></a>
										<div class="wd-right-list-content mt20">
											<h3 class="wd-tt"><a href="#" class="wd-text-b">Shani Zelinger</a></h3>
											<p class="wd-desc"><a href="#" class="wd-text-g">Works at Accounting</a></p>
										</div>
									</li>
									<li>
										<a href="#" class="wd-gray-bt wd-add-action-bt-2">Friend<span class="wd-arrow"></span></a>
										<a class="wd-avatar" href="#"><img alt="" src="<?php echo baseUrl(); ?>img/thumb/photo-75x75-3.jpg" height="75" width="75"></a>
										<div class="wd-right-list-content mt20">
											<h3 class="wd-tt"><a href="#" class="wd-text-b">Janne Ericsson</a></h3>
											<p class="wd-desc"><a href="#" class="wd-text-g">Works at MemoryPhoto</a></p>
										</div>
									</li>
									<li>
										<a href="#" class="wd-gray-bt wd-add-action-bt-2">Friend<span class="wd-arrow"></span></a>
										<a class="wd-avatar" href="#"><img alt="" src="<?php echo baseUrl(); ?>img/thumb/photo-75x75-4.jpg" height="75" width="75"></a>
										<div class="wd-right-list-content mt20">
											<h3 class="wd-tt"><a href="#" class="wd-text-b">Jacob Torell</a></h3>
											<p class="wd-desc"><a href="#" class="wd-text-g">Da Nang, Vietnam</a></p>
										</div>
									</li>
								</ul>
								<a class="wd-list-stream-seamore">Show more photos</a>
							</div>
						</div>
					</div>
					<!-- end people .end -->
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php $this->renderPartial('application.modules.zone.views.common.create_topic_popup'); ?>