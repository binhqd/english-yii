<?php $this->renderPartial('//common/user-related', compact('user'))?>
<div class="wd-container">
	<div class="wd-center">
		<div class="wd-right-content">
			<!-- How You're Connected -->
			<?php $this->renderPartial('application.modules.users.views.elements.how-you-connected')?>
			<!-- How You're Connected .end -->
			<!-- People’s you may know -->
			<?php $this->renderPartial('application.modules.users.views.elements.people-you-may-know')?>

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
			<?php $this->renderPartial('application.modules.users.views.elements.user-interaction-status')?>
			<!-- header line .end-->
			<!-- left content -->
			<div class="wd-left-content">
				<!-- person avatar -->
				<div class="wd-left-block">
					<div class="wd-topleft-person">
						<div class="wd-orange-actmore-bt wd_parenttoggle">
							<span class="wd-addfriend-bt">Add friends</span><span
								class="wd-dropdow-slmbt wd_toggle_bt"><span class="wd-arrow"></span>
							</span>
							<div class="wd-actmore-user-toggle wd_toggle">
								<div class="wd-scroll-1">
									<div class="content">
										<span class="wd-uparrow-1"></span>
										<ul class="bbor-solid-2">
											<li><a href="#">Follow</a></li>
											<li><a href="#">Send message</a></li>
											<li><a href="#">Recommend</a></li>
										</ul>
										<ul>
											<li><a href="#">Block</a></li>
											<li><a href="#">Report...</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="wd-view-person">
							<h2 class="wd_tt_1">
								<?php echo $user->screenName?>
							</h2>
							<p>
								<strong>Founder and CEO of <a href="#">Green Net</a>
								</strong>
							</p>
							<?php
									if (!empty($profile->location)):?>
							<p class="wd-gray-cl-1 mt5">
								<?php echo $profile->location?>
							</p>
							<?php endif;?>
						</div>
						<?php $this->widget('application.modules.users.widgets.ProfileAvatarsWidget', array(
							'user'	=> $user
						))?>
					</div>
				</div>
				<!-- person avatar .end-->
				<!-- mutual friend -->
				<div class="wd-left-block">
					<div class="wd-mutual-friends">
						<p class="wd-intro">
							To see what she shares with friends, <a href="#">send him a
								friend request</a>.
						</p>
						<ul class="wd-mutual-friend-list-1">
							<li><a href="#"><img src="/myzone_v1/img/thumb/img-thumb-6.jpg"
									alt="Phill M." height="34" width="34" /> </a></li>
							<li><a href="#"><img src="/myzone_v1/img/thumb/img-thumb-7.jpg"
									alt="Phill M." height="34" width="34" /> </a></li>
							<li><a href="#"><img src="/myzone_v1/img/thumb/img-thumb-8.jpg"
									alt="Phill M." height="34" width="34" /> </a></li>
							<li><a href="#"><img src="/myzone_v1/img/thumb/img-thumb-9.jpg"
									alt="Phill M." height="34" width="34" /> </a></li>
							<li><a href="#"><img src="/myzone_v1/img/thumb/img-thumb-7.jpg"
									alt="Phill M." height="34" width="34" /> </a></li>
						</ul>
						<p class="wd-count-mf">
							<a href="#">12 Mutual Friends</a>
						</p>
					</div>
				</div>
				<!-- mutual friend .end -->
				<!-- summary -->
				<div class="wd-left-block">
					<h2 class="wd_tt_2">Summary</h2>
					<p>Ability to use UML tools for managing Requirements, Use cases,
						Activity diagrams, Interface diagrams & flows, Database diagram,
						Class Diagram, Sequence Diagram...</p>
				</div>
				<!-- summary .end -->
				<!---Information -->
				<div class="wd-left-block">
					<?php $this->widget('application.modules.users.widgets.ZoneUserInformationWidget', array(
						'user'	=> $user
					))?>
				</div>
				<!---Information .end -->
				<!--Experience-->
				<div class="wd-left-block">
					<h2 class="wd_tt_2">Experience</h2>
					<ul class="wd-list-2">
						<li>
							<h3 class="wd_tts_1">Director</h3>
							<p>
								<a href="#">Green Net</a>
							</p>
							<p class="wd-gray-cl">February 2008 - Present (5 years 4 months)
								Vietnam</p>
							<p>Founder and CEO of Green Net</p>
						</li>
					</ul>
				</div>
				<!--- Experience .end -->
				<!--- Education -->
				<div class="wd-left-block">
					<h2 class="wd_tt_2">Education</h2>
					<ul class="wd-list-2">
						<li>
							<h3 class="wd_tts_1">
								<a href="#">Ho Chi Minh Polytechnic University</a>
							</h3>
							<p>IT Engineer, Algorithm, Programming skills</p>
							<p class="wd-gray-cl">1996 - 2001</p>
						</li>
						<li>
							<h3 class="wd_tts_1">
								<a href="#">Danang Polytechnic University</a>
							</h3>
							<p>Programming skills</p>
							<p class="wd-gray-cl">2001 - 2005</p>
						</li>
					</ul>
				</div>
				<!--- Education .end -->
				<!--- Follows  -->
				<div class="wd-left-block">
					<h2 class="wd_tt_2">Follows</h2>
					<div class="wd-follow-block bbor-solid-1">
						<h3 class="wd_tts_2">Persons</h3>
						<ul class="wd-list-3">
							<li class="pl0"><a href="#" class="wd-image"><img
									src="/myzone_v1/img/thumb/image-1.jpg" alt="" height="91"
									width="91" /> </a> <a href="#" class="wd-name">Al Pacino</a> <a
								href="#" class="wd-more">Acto</a>
							</li>
							<li><a href="#" class="wd-image"><img
									src="/myzone_v1/img/thumb/image-2.jpg" alt="" height="91"
									width="91" /> </a> <a href="#" class="wd-name">Morgan Freeman</a>
								<a href="#" class="wd-more">Acto</a>
							</li>
							<li><a href="#" class="wd-image"><img
									src="/myzone_v1/img/thumb/image-3.jpg" alt="" height="91"
									width="91" /> </a> <a href="#" class="wd-name">Barack Obama</a>
								<a href="#" class="wd-more">U.S. President</a>
							</li>
							<li><a href="#" class="wd-image"><img
									src="/myzone_v1/img/thumb/image-4.jpg" alt="" height="91"
									width="91" /> </a> <a href="#" class="wd-name">Abraham Lincoln</a>
								<a href="#" class="wd-more">U.S. President</a>
							</li>
						</ul>
					</div>
					<div class="wd-follow-block">
						<h3 class="wd_tts_2">Movies</h3>
						<ul class="wd-list-3">
							<li class="pl0"><a href="#" class="wd-image"><img
									src="/myzone_v1/img/thumb/image-5.jpg" alt="" height="91"
									width="91" /> </a> <a href="#" class="wd-name">Flight</a> <a
								href="#" class="wd-more">2012 Film</a>
							</li>
							<li><a href="#" class="wd-image"><img
									src="/myzone_v1/img/thumb/image-6.jpg" alt="" height="91"
									width="91" /> </a> <a href="#" class="wd-name">Training Day</a>
								<a href="#" class="wd-more">2001Film</a>
							</li>
							<li><a href="#" class="wd-image"><img
									src="/myzone_v1/img/thumb/image-7.jpg" alt="" height="91"
									width="91" /> </a> <a href="#" class="wd-name">The Book of Eli</a>
								<a href="#" class="wd-more">2010 Film</a>
							</li>
							<li><a href="#" class="wd-image"><img
									src="/myzone_v1/img/thumb/image-8.jpg" alt="" height="91"
									width="91" /> </a> <a href="#" class="wd-name">American
									Gangster</a> <a href="#" class="wd-more">2007 Film</a>
							</li>
						</ul>
					</div>
				</div>
				<!--- Follows .end -->
			</div>
			<!-- left content .end -->
			<!-- main content -->
			<div class="wd-main-content">
				<!-- top-search -->
				<div class="wd-top-search-pagelet">
					<div class="wd-search-activities wd_parenttoggle">
						<a href="#" class="wd-activities-bt wd_toggle_bt">All Activities<span
							class="wd-arrow"></span>
						</a>
						<div class="wd-search-activities-toggle wd_toggle">
							<div class="wd-search-activities-content">
								<ul>
									<li><a href="#">Share Activities</a></li>
									<li><a href="#">Comment Activities</a></li>
									<li><a href="#">Post Activities</a></li>
									<li><a href="#">Create Activities</a></li>
								</ul>
							</div>
						</div>
					</div>
					<fieldset class="wd-topsearch-pagelet-form">
						<div class="wd-input-search">
							<input type="text" placeholder="Search..." class="wd-text-search" />
							<input type="submit" value="" class="wd-submit" />
						</div>
					</fieldset>
					<div class="clear"></div>
				</div>
				<!-- top-search .end -->
				<!-- pagelet-stream -->
				<div class="wd-pagelet-stream">
					<ul class="wd-list-stream">
						<!--- share actical -->
						<li class="wd-stream-story">
							<div class="wd-story-content">
								<div class="wd-head-storycontent">
									<a href="#" class="wd-share-bt wd-shared-bt">Share</a> <a
										href="#" class="wd-avatar"><img
										src="/myzone_v1/img/thumb/img-thumb-6.jpg" alt="Peter H."
										height="34" width="34" /> </a>
									<div class="wd-head-storyinnercontent">
										<h3 class="wd_tt_n1">
											<a href="#" class="wd-name">Peter H.</a> shared a <a href="#">article</a>.
										</h3>
										<p class="wd-date-post">10 minutes ago</p>
									</div>
									<span class="wd-arrow-down"></span>
									<div class="clear"></div>
								</div>
								<div class="wd-share-content bbor-solid-1">
									<p class="wd-disc">Hey Lawerence, about 5 months ago I
										cross-linked a couple of my articles to each other, just to
										give my readers info.</p>
									<div class="wd-sharearticle-content">
										<a class="wd-shareimage" href="#"><img
											src="/myzone_v1/img/thumb/images-7.jpg" alt="Ana T."
											height="54" width="54" /> </a>
										<div class="wd-sharearticle-text">
											<h3 class="wd-tt">
												<a href="#">Move Over, Mount Vernon. John Adams Slept Here</a>
											</h3>
											<p class="wd-poster">
												Written by <a href="#">Ana T.</a>
											</p>
											<p>Reference site about Lorem Ipsum, giving information on
												its origins...</p>
										</div>
									</div>
								</div>
								<div class="wd-action-storycontent bbor-solid-1">
									<div class="wd-pp-like-content">
										<a href="#" class="wd-like-bt"></a><span><a href="#">42 People</a>
											like this.</span>
									</div>
								</div>
								<div class="wd-comment-box">
									<span class="wd-arrow-up"></span>
									<div class="wd-content-box">
										<a class="wd-thumb" href="#"><img class="avatar" width="34"
											height="34" alt="You"
											src="/myzone_v1/img/thumb/img-thumb-2.jpg"> </a>
										<div class="wd-right-box">
											<div class="wd-inputbox">
												<textarea class="wd-font-11" cols="97" rows="2"></textarea>
											</div>
										</div>
									</div>
									<div class="wd-content-box">
										<a href="#" class="wd-thumb"><img class="avatar" width="34"
											height="34" alt="You"
											src="/myzone_v1/img/thumb/img-thumb-3.jpg"> </a>
										<div class="wd-right-box">
											<p class="wd-commentpost">
												<a href="#"><strong>Juneambrose</strong> </a> Nam commodo
												posuere sapien, eu sollicitudin ligula rutrum luctus.
											</p>
											<p class="wd-date-post">
												<label>09 May at 19:51</label>
											</p>
										</div>
									</div>
									<div class="wd-content-box">
										<a href="#" class="wd-thumb"><img class="avatar" width="34"
											height="34" alt="You"
											src="/myzone_v1/img/thumb/img-thumb-4.jpg"> </a>
										<div class="wd-right-box">
											<p class="wd-commentpost">
												<a href="#"><strong>Juneambrose</strong> </a> Nam commodo
												posuere sapien, eu sollicitudin ligula rutrum luctus.
											</p>
											<p class="wd-date-post">
												<label>09 May at 19:51</label>
											</p>
										</div>
									</div>
									<div class="wd-content-box">
										<a href="#" class="wd-thumb"><img class="avatar" width="34"
											height="34" alt="You"
											src="/myzone_v1/img/thumb/img-thumb-5.jpg"> </a>
										<div class="wd-right-box">
											<p class="wd-commentpost">
												<a href="#"><strong>Juneambrose</strong> </a> Nam commodo
												posuere sapien, eu sollicitudin ligula rutrum luctus.
											</p>
											<p class="wd-date-post">
												<label>09 May at 19:51</label>
											</p>
										</div>
									</div>
									<div class="wd-content-box wd-viewall-box">
										<a href="#">View 5 more comments...</a>
									</div>
								</div>
							</div>
						</li>
						<!--- share actical .end -->
						<!--- like image -->
						<li class="wd-stream-story">
							<div class="wd-story-content">
								<div class="wd-head-storycontent">
									<a href="#" class="wd-share-bt">Share</a> <a href="#"
										class="wd-avatar"><img
										src="/myzone_v1/img/thumb/img-thumb-6.jpg" alt="Peter H."
										height="34" width="34" /> </a>
									<div class="wd-head-storyinnercontent">
										<h3 class="wd_tt_n1">
											<a href="#" class="wd-name">Peter H.</a> liked a <a href="#">James
												A.</a> image.
										</h3>
										<p class="wd-date-post">10 minutes ago</p>
									</div>
									<span class="wd-arrow-down"></span>
									<div class="clear"></div>
								</div>
								<div class="wd-like-content bbor-solid-1">
									<div class="wd-likearticle-content">
										<a class="wd-likeimage" href="#"><img
											src="/myzone_v1/img/thumb/image-like.jpg" alt="Ana T."
											height="189" width="178" /> </a>
										<div class="wd-likearticle-text">
											<a href="#" class="wd-avatarposter"><img
												src="/myzone_v1/img/thumb/img-thumb-6.jpg" alt="Peter H."
												height="34" width="34" /> </a>
											<div class="wd-nameposter">
												<h3 class="wd_tt_n1">
													<a href="#" class="wd-name">James A.</a>
												</h3>
												<p class="wd-date-post">10 minutes ago</p>
											</div>
											<div class="wd-disc">
												<p>The Sussex county 1st team vs Kent Great Victory over
													Kent Next Match Sussex Vs Surrey 1st Team League Match
													Royal Ashdown Forest.
												
												
												<p>
											
											</div>
										</div>
									</div>
								</div>
								<div class="wd-action-storycontent bbor-solid-1">
									<div class="wd-pp-like-content">
										<a href="#" class="wd-like-bt wd-liked-bt"></a><span>You <span
											class="wd-gray-cl-1">and </span><a href="#">42 People</a>
											like this.
										</span>
									</div>
								</div>
								<div class="wd-comment-box">
									<span class="wd-arrow-up"></span>
									<div class="wd-content-box bdbno">
										<a class="wd-thumb" href="#"><img class="avatar" width="34"
											height="34" alt="You"
											src="/myzone_v1/img/thumb/img-thumb-2.jpg"> </a>
										<div class="wd-right-box">
											<div class="wd-inputbox">
												<textarea class="wd-font-11" cols="97" rows="2"></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</li>
						<!--- like image .end -->
						<!--- share topic -->
						<li class="wd-stream-story">
							<div class="wd-story-content">
								<div class="wd-head-storycontent">
									<a href="#" class="wd-share-bt wd-shared-bt">Share</a> <a
										href="#" class="wd-avatar"><img
										src="/myzone_v1/img/thumb/img-thumb-6.jpg" alt="Peter H."
										height="34" width="34" /> </a>
									<div class="wd-head-storyinnercontent">
										<h3 class="wd_tt_n1">
											<a href="#" class="wd-name">Peter H.</a> shared a <a href="#">topic</a>.
										</h3>
										<p class="wd-date-post">10 minutes ago</p>
									</div>
									<span class="wd-arrow-down"></span>
									<div class="clear"></div>
								</div>
								<div class="wd-share-content bbor-solid-1">
									<p class="wd-disc">Presents the life and work of actor Denzel
										Washington.</p>
									<div class="wd-sharetopic-content">
										<div class="wd-stream-gallery mb10">
											<ul class="wd-stream-gallery-51">
												<li class="wd-element-1"><a href="#" class="wd-thumb-img"><img
														src="/myzone_v1/img/thumb/stream-gallery-1.jpg"
														height="193" width="193" alt="" /> </a>
												</li>
												<li class="wd-element-2 mb11 ml10"><a href="#"
													class="wd-thumb-img"><img
														src="/myzone_v1/img/thumb/stream-gallery-2.jpg"
														height="91" width="91" alt="" /> </a>
												</li>
												<li class="wd-element-2 mb11 ml10"><a href="#"
													class="wd-thumb-img"><img
														src="/myzone_v1/img/thumb/stream-gallery-3.jpg"
														height="91" width="91" alt="" /> </a>
												</li>
												<li class="wd-element-2 mb11 ml10"><a href="#"
													class="wd-thumb-img"><img
														src="/myzone_v1/img/thumb/stream-gallery-4.jpg"
														height="91" width="91" alt="" /> </a>
												</li>
												<li class="wd-element-2 mb11 ml10"><a href="#"
													class="wd-thumb-img"><img
														src="/myzone_v1/img/thumb/stream-gallery-5.jpg"
														height="91" width="91" alt="" /> </a>
												</li>
												<li class="wd-element-2 ml10"><a href="#"
													class="wd-thumb-img"><img
														src="/myzone_v1/img/thumb/stream-gallery-3.jpg"
														height="91" width="91" alt="" /> </a>
												</li>
												<li class="wd-element-2 ml10"><a href="#"
													class="wd-thumb-img"><img
														src="/myzone_v1/img/thumb/stream-gallery-4.jpg"
														height="91" width="91" alt="" /> </a>
												</li>
												<li class="wd-element-2 ml10"><a href="#"
													class="wd-thumb-img"><img
														src="/myzone_v1/img/thumb/stream-gallery-3.jpg"
														height="91" width="91" alt="" /> </a>
												</li>
												<li class="wd-element-2 ml10"><a href="#"
													class="wd-thumb-img"><img
														src="/myzone_v1/img/thumb/stream-gallery-4.jpg"
														height="91" width="91" alt="" /> </a>
												</li>
											</ul>
										</div>
										<div class="wd-sharetopic-text">
											<h3 class="wd_tt_st_1">
												<a href="#" class="wd-name">Denzel Washington</a> (Actor)
											</h3>
											<p class="wd-poster">
												Written by <a href="#">Ana T.</a>
											</p>
											<p>Reference site about Lorem Ipsum, giving information on
												its origins...</p>
										</div>
									</div>
								</div>
								<div class="wd-action-storycontent bbor-solid-1">
									<div class="wd-pp-like-content">
										<a href="#" class="wd-like-bt"></a><span><a href="#">42 People</a>
											like this.</span>
									</div>
								</div>
								<div class="wd-comment-box">
									<span class="wd-arrow-up"></span>
									<div class="wd-content-box">
										<a class="wd-thumb" href="#"><img class="avatar" width="34"
											height="34" alt="You"
											src="/myzone_v1/img/thumb/img-thumb-2.jpg"> </a>
										<div class="wd-right-box">
											<div class="wd-inputbox">
												<textarea class="wd-font-11" cols="97" rows="2"></textarea>
											</div>
										</div>
									</div>
									<div class="wd-content-box bdbno">
										<a href="#" class="wd-thumb"><img class="avatar" width="34"
											height="34" alt="You"
											src="/myzone_v1/img/thumb/img-thumb-3.jpg"> </a>
										<div class="wd-right-box">
											<p class="wd-commentpost">
												<a href="#"><strong>Juneambrose</strong> </a> Nam commodo
												posuere sapien, eu sollicitudin ligula rutrum luctus.
											</p>
											<p class="wd-date-post">
												<label>09 May at 19:51</label>
											</p>
										</div>
									</div>
								</div>
							</div>
						</li>
						<!--- share topic .end -->
						<!--- add new image in topic -->
						<li class="wd-stream-story">
							<div class="wd-story-content">
								<div class="wd-head-storycontent">
									<a href="#" class="wd-share-bt wd-shared-bt">Share</a> <a
										href="#" class="wd-avatar"><img
										src="/myzone_v1/img/thumb/img-thumb-6.jpg" alt="Peter H."
										height="34" width="34" /> </a>
									<div class="wd-head-storyinnercontent">
										<h3 class="wd_tt_n1">
											<a href="#" class="wd-name">Peter H.</a> added new <a
												href="#">3 images for Tom Cruise</a> topic.
										</h3>
										<p class="wd-date-post">10 minutes ago</p>
									</div>
									<span class="wd-arrow-down"></span>
									<div class="clear"></div>
								</div>
								<div class="wd-addnew-content bbor-solid-1">
									<p class="wd-disc">Hey Lawerence, about 5 months ago I
										cross-linked a couple of my articles to each other, just to
										give my readers info.</p>
									<div class="wd-stream-gallery mt10">
										<ul class="wd-stream-gallery-51">
											<li class="wd-element-4"><a href="#" class="wd-thumb-img"><img
													src="/myzone_v1/img/thumb/stream-gallery-7.jpg"
													height="178" width="189" alt="" /> </a>
											</li>
											<li class="wd-element-4 ml10"><a href="#"
												class="wd-thumb-img"><img
													src="/myzone_v1/img/thumb/stream-gallery-8.jpg"
													height="178" width="189" alt="" /> </a>
											</li>
											<li class="wd-element-5 ml10"><a href="#"
												class="wd-thumb-img"><img
													src="/myzone_v1/img/thumb/stream-gallery-9.jpg"
													height="178" width="121" alt="" /> </a>
											</li>
										</ul>
									</div>
								</div>
								<div class="wd-action-storycontent bbor-solid-1">
									<div class="wd-pp-like-content">
										<a href="#" class="wd-like-bt"></a><span><a href="#">42 People</a>
											like this.</span>
									</div>
								</div>
								<div class="wd-comment-box">
									<span class="wd-arrow-up"></span>
									<div class="wd-content-box bdbno">
										<a class="wd-thumb" href="#"><img class="avatar" width="34"
											height="34" alt="You"
											src="/myzone_v1/img/thumb/img-thumb-2.jpg"> </a>
										<div class="wd-right-box">
											<div class="wd-inputbox">
												<textarea class="wd-font-11" cols="97" rows="2"></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</li>
						<!--- add new image in topic .end -->
					</ul>
				</div>
				<div class="wd-list-stream-loading">
					<img src="/myzone_v1/img/front/ajax-loader.gif" alt="loading" /><span>Loading
						more...</span>
				</div>
				<!-- pagelet-stream .end -->
			</div>
			<!-- main content .end-->
		</div>
	</div>
</div>
