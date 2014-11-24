<?php if($countFollowers > 0) :?>
<li class="wd-stream-story" id="article-item">
	<div class="wd-story-content">
		<div class="wd-head-storycontent">
			<a href="<?php echo  ZoneRouter::createUrl('/profile/'.$user->username);?>"
				class="wd-avatar">
				<img src="<?php echo ZoneRouter::CDNUrl('/');?>/upload/user-photos/<?php echo $user->hexID ;?>/fill/40-40/<?php echo !empty($user->profile) ? $user->profile->image : '';?>?album_id=<?php echo $user->hexID;?>" alt="<?php echo $user->displayname;?>" height="40" width="40" />
			</a>
			<div class="wd-head-storyinnercontent">
				<h3 class="wd_tt_n1">
					<?php
						$title = '';
						$countFollowersNotCurrentUser = $countFollowers;
						foreach ($followers as $u) {
							if ($u['user_id'] == $user->hexID) {
								$countFollowersNotCurrentUser--;
								continue;
							}
							$userInfo = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($u['user_id']));
							$title .= '<span class="wd-tooltip-html">'.$userInfo->displayname.'</span>';
						}
						if ($countFollowersNotCurrentUser > count($followers))
							$title .= '<span class="wd-tooltip-html">and '.($countFollowersNotCurrentUser - count($followers)).' more...</span>';
					?>
					<a href="<?php echo ZoneRouter::createUrl('/profile/'.$user->username);?>" class="wd-name">
						<?php echo $user->displayname;?>
					</a>
					<span class="_parent-js-follower-count" data-token="object_<?php echo $node['zone_id']; ?>" style="display:<?php echo ($countFollowersNotCurrentUser <= 0) ? "none": "inline-block";?>">
					and 
					<a class="js-follow-person-parent wd-tooltip-hover-html" href="javascript:void(0)" title='<?php echo $title?>' data-token="object_<?php echo $node['zone_id']; ?>">
						<span class="_js-follower-count"><?php echo $countFollowersNotCurrentUser; ?></span>
						<span class="js-follow-person"><?php echo Yii::t('Follow', 'Person|People', ($countFollowersNotCurrentUser));?></span>
					</a>
					</span>
					<span class="js-be" data-token="object_<?php echo $node['zone_id'];?>"><?php echo $countFollowersNotCurrentUser == 1 ? ' is ':' are ' ?></span>following topic: <a href="<?php echo GNRouter::createUrl('/zone/pages/detail', array('id'=>$node['zone_id'])); ?>"><?php echo $node['name']; ?></a>
				</h3>
				<p class="wd-date-post timeago" data-title="<?php echo date(DATE_ISO8601, strtotime($this->activity->created));?>"></p>
			</div>
			<span class="wd-arrow-down"></span>
			<div class="clear"></div>
		</div>
		<div class="wd-addnew-content">
			
			<a class="wd-addnew-image" href="<?php echo GNRouter::createUrl('/zone/pages/detail/', array('id'=>$node['zone_id'])); ?>">
				<img src="<?php echo ZoneRouter::CDNUrl('/');?>/upload/gallery/fill/120-120/<?php echo $node['image']; ?>?album_id=<?php echo $node['album_id']; ?>" alt="<?php echo $node['name']; ?>" height="120" width="120"/>
			</a>
			<div class="wd-addnew-text">
				<div class="wd-nameposter">
					<h3 class="wd_tt_n4"><a href="<?php echo GNRouter::createUrl('/zone/pages/detail/', array('id'=>$node['zone_id'])); ?>" class="wd-name"><?php echo $node['name']; ?></a> </h3>
					<p class="wd-user-occupation"><?php echo @$node['label'];?></p>
					<p class="wd-user-post">created by <a href="<?php echo GNRouter::createUrl('/profile/'.$owner->username); ?>"><?php echo $owner->displayname; ?></a></p>
				</div>
				<div class="wd-disc">
					<p><?php echo GNStringHelper::word_limiter($node['description'], 20); ?></p>
					
				</div>
			</div>
			<?php
			GNAssetHelper::init(array(
				'image' => 'img',
				'css' => 'css',
				'script' => 'js'
			));

			
			?>
			<div class="wd-follow-node-act">
				<?php if ($isFollowing) : ?>
					<a href="javascript:void(0)" class="wd-follow-bt-2 wd-tooltip-hover js-following-request" data-action="unfollow" data-token="object_<?php echo $node['zone_id']; ?>" title="Unfollow <?php echo $node['name']; ?>">Unfollow</a>
				<?php else : ?>
					<a href="javascript:void(0)" class="wd-follow-bt-2 wd-tooltip-hover js-following-request" data-action="follow" data-token="object_<?php echo $node['zone_id']; ?>" title="Follow <?php echo $node['name']; ?>">Follow</a>
				<?php endif; ?>
				<span class="wd-valueactical wd-valueactical-follow"><span class="wd-dot-1"></span><span class="wd-test">
					<a href="<?php echo GNRouter::createUrl('/followings/list/followers', array('token'=>'object_'.$node['zone_id'])); ?>" >
						<span class="js-follower-count" data-token="object_<?php echo $node['zone_id']; ?>"><?php echo $countFollowers; ?></span>
						<span class="js-follower-count-text" data-token="object_<?php echo $node['zone_id']; ?>"><?php echo ($countFollowers == 1) ? "Follower" : "Followers"?></span>
					</a> this topic</span>
				</span>
			</div>
		</div>

	</div>
</li>
<?php endif;?>