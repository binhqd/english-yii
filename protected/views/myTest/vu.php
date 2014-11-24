<?php 
$user = currentUser();

Yii::import('application.modules.users.models.ZoneUserAvatar');

$avatars = ZoneUserAvatar::getAvatars("avatar_" . $user->hexID, 4);
//$this->render('application.modules.users.views.profile.other-view', compact('user', 'profile', 'node', 'avatars'));

?>
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
							<h2 class="wd_tt_1"><?php echo $user->screenName?></h2>
							<p>
								<strong>Founder and CEO of <a href="#">Green Net</a>
								</strong>
							</p>
							<?php
							if (!empty($profile->location)):?>
							<p class="wd-gray-cl-1 mt5"><?php echo $profile->location?></p>
							<?php endif;?>
						</div>
						<div class="wd-person-img">
							<a class="wd-main-image" href="#">
								<img src="<?php echo GNRouter::createUrl("/upload/user-photos/".$user->hexID."/fill/193-193/" . $user->profile->image)?>" alt="<?php echo $user->username?>"/>
							</a>
							<ul class="wd-gallery-1">
								<?php foreach ($avatars as $item):?>
								<li class="wd-mlb-img wd-first-elm">
									<a href="#" class="wd-thumb-img">
										<img src="<?php echo GNRouter::createUrl("/upload/user-photos/".$user->hexID."/fill/91-91/" . $item->image)?>"/> 
									</a>
								</li>
								<?php endforeach;?>
							</ul>
						</div>
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
					<h2 class="wd_tt_2">Information</h2>
					<ul class="wd-information-list">
						<li><label>Lives in:</label><span><a href="#">Danang</a>, <a
								href="#">Vietnam</a> </span></li>
						<li><label>From:</label><span><a href="#">Tamky</a>, <a href="#">Quangnam</a>,
								<a href="#">Vietnam</a> </span></li>
						<li><label>Email:</label><span><a href="mailto:peter262@gmail.com">peter262@gmail.com</a>
						</span>
						</li>
						<li><label>Phone:</label><span>(+84) 511 231 565</span></li>
						<li><label>Mobile:</label><span>(+84) 9568 685 231</span></li>
						<li><label>Skype:</label><span>peter262</span></li>
						<li><label>Website URL:</label><span><a href="peterblogs.net">peterblogs.net</a>
						</span></li>
						<li><label>Blog URL:</label><span><a href="peter262.blogs.com">peter262.blogs.com</a>
						</span></li>
					</ul>
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
						<a href="#" class="wd-activities-bt wd_toggle_bt">All activities<span
							class="wd-arrow"></span>
						</a>
						<div class="wd-search-activities-toggle wd_toggle">
							<div class="wd-search-activities-content">
								<ul>
									<li><a href="#">Share activities</a></li>
									<li><a href="#">Comment activities</a></li>
									<li><a href="#">Post activities</a></li>
									<li><a href="#">Create activities</a></li>
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
										<a href="#" class="wd-like-bt"></a><span><a href="#">42 people</a>like this.</span>
									</div>
								</div>
								<!-- begin comments -->
								<div class="wd-comment-box">
									<span class="wd-arrow-up"></span>
									<?php $this->widget('greennet.modules.comment.widgets.GNAddCommentWidget', array(
										'model'			=> 'ZoneComment',		// your model
										'objectId'		=> '51777d8e34d8432da664142ccbdd56cb',					// object binary IDHelper::uuidFromBinary('[id]')
										'addCommentUrl'	=> GNRouter::createUrl('core/addComment'),			// add
// 										'spanNumber'	=> 'span6',	// span of bootstrap (width), default span4
// 										'profile'		=> '#',
// 										'avatar'		=> '#',
// 										'likeComment'	=> true,
// 										'cssAddForm'	=> '',
										'heightTextarea'=> 37,
										));
									?>
									<?php 
										$this->widget('greennet.modules.comment.widgets.GNListCommentWidget', array (
											'model'					=> 'ZoneComment',			// your model
											'objectId'				=> '51777d8e34d8432da664142ccbdd56cb',						// object binary IDHelper::uuidFromBinary('[id]')
											'deleteCommentUrl'		=> GNRouter::createUrl('core/deleteComment'),		// action delete
											'viewMoreUrl'			=> GNRouter::createUrl('core/listComments'),			// action list
// 											'cssListForm'			=> '',
// 											'preLoads'				=> 5,		// default 5
// 											'limit'					=> 10,		// default 50
// 											'numberOfWord'			=> 364,	// default 200 word
// 											'listCommentsTemplate'	=> 'application.views.destination.comment-template',		// Path to your template
// 											'spanNumber'			=> 'span6',	// span of bootstrap (width), default span4
// 											'listFormCss'			=> 'list-form',// name css in folder webroot/assets/css
// 											'readMoreText'			=> 'Read more',	// default read more
// 											'readLessText'			=> 'Read less',	// default read less
// 											'readLessShow'			=> 'true',		// default true
// 											'likeComment'			=> true,
// 											'modelRating'			=> 'ZoneRating',
// 											'modelRatingObject'		=> 'ZoneRatingObject',
// 											'modelRatingStatistic'	=> 'ZoneRatingStatistic',
// 											'modelRatingValue'		=> 'ZoneRatingValue',
										));
									?>
									<div class="wd-content-box wd-viewall-box">
										<a id="show-comments-51777d8e34d8432da664142ccbdd56cb" >view more</a>
										<span id="total-show-51777d8e34d8432da664142ccbdd56cb" style="display:none"></span>
										<span style="display:none" name='total-comments-51777d8e34d8432da664142ccbdd56cb' id="total-comments-51777d8e34d8432da664142ccbdd56cb"></span>
									</div>
								</div>
								<!-- end comments -->
							</div>
						</li>
						
						<!--- add new image in topic .end -->
					</ul>
				</div>
				<!-- pagelet-stream .end -->
			</div>
			<!-- main content .end-->
		</div>
	</div>
</div>
