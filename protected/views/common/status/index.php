<?php
if(!empty($status->author->user) && !empty($status->namespace)){
	$user = $status->author->user;
	
	$receiver = GNUser::model()->getUserInfo($status->namespace->holder_id);
	
	$hexID = IDHelper::uuidFromBinary($user->id);
	$strStatusId = IDHelper::uuidFromBinary($status->id);
	$token = md5(uniqid(32));
	
	$countComment = ZoneComment::model()->countComments($strStatusId);
?>
<li class="wd-stream-story <?php echo $token;?>" id="article-item">
	<div class="wd-story-content  pullViewAllComment<?php echo $strStatusId;?>">
		<div class="wd-head-storycontent">
			<?php if($user->id == currentUser()->id):?>
			<div class="wd-setting-streamstory wd_parenttoggle">
				<span class="wd-icon-16 wd-icon-setting-stream-story wd_toggle_bt wd-tooltip-hover" title="More functions..."></span>
				<div class="wd-setting-stream-content wd_toggle" style="display: none;z-index:10">
					<span class="wd-uparrow-1"></span>
					<ul>
						<li>
							<?php
								echo CHtml::link('Delete',Yii::app()->createUrl('/activities/admin/delete',array('id'=>IDHelper::uuidFromBinary($activity->id,true))),array(
									'class'=>'deletePost',
									'item'=>$token
								));
							?>
						</li>
					</ul>
				</div>
			</div>
			<?php endif;?>
			<a href="<?php echo ZoneRouter::createUrl('/profile/'.$user->username);?>" class="wd-avatar">
				<img src="<?php echo ZoneRouter::CDNUrl('/')."/upload/user-photos/{$hexID}/fill/40-40/{$user->profile->image}";;?>" alt="<?php echo $user->displayname;?>" >
			</a>
			<div class="wd-head-storyinnercontent">
				<h3 class="wd_tt_n1">
					<?php
					echo CHtml::link($user->displayname,ZoneRouter::createUrl('/profile/'.$user->username),array('class'=>'wd-name'));
					
					if(currentUser()->id == $ownerWall->id){
						if($receiver->id != $user->id){
							echo " sent a message for ";
							echo CHtml::link($receiver->displayname,ZoneRouter::createUrl('/profile/'.$receiver->username));
						}
					}else{
						if($receiver->id != $user->id){
							echo " sent a message for ";
							echo CHtml::link($receiver->displayname,ZoneRouter::createUrl('/profile/'.$receiver->username));
						}else echo " sent a message";
					}
					
					
					

					?>
				</h3>
				
				<p class="wd-date-post timeago" data-title="<?php echo  date(DATE_ISO8601,strtotime($status->created));?>"></p>
			</div>
			<span class="wd-arrow-down"></span>
			<div class="clear"></div>
		</div>
		<div class="wd-addnew-content bbor-solid-1" >
			<p class="wd-disc" content="<?php echo ($status->type == ZoneStatus::MESSAGE) ? trim(@$status->content) : "";  ?>" style="white-space: pre-line;">
				<?php
				if($status->type == ZoneStatus::MESSAGE)
					$message = JLStringHelper::char_limiter_word(trim(@$status->content),1000);
				else
					$message = JLStringHelper::char_limiter_word(trim(@$status->title),1000);
					
				$threeWord = substr($message, -3);
				$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
				if(preg_match($reg_exUrl, $message, $url)) {
					if(!empty($url[0])){
						echo str_replace($url[0],'<a href="'.$url[0].'">'.$url[0].'</a>',$message);
					}
					
					// echo preg_replace($reg_exUrl, "<a href="{$url[0]}">{$url[0]}</a> ", $message);
				} else {
					// if no urls in the text just return the text
					echo "<label class='emoticon'>".$message."</label>";

				}
				// echo trim($message);
				if($threeWord == "30;"){
					echo CHtml::link('see more','javascript:void(0)',array('class'=>'',
						'onClick'=>"$(this).parent().html($(this).parent().attr('content'));"
					));
				}
				
				
				?>
			</p>
			<?php if($status->type == ZoneStatus::LINK):
				$content = @CJSON::decode($status->content);
				// dump($content);
				$this->renderPartial('application.views.common.status._item_post_link',array(
					'content'=>$content,
					'close'=>false
				));
			endif;?>
		</div>
		<div class="wd-action-storycontent">
			<?php
			
			$this->widget('widgets.like.Like', array(
				'objectId'=>$strStatusId,
				'actionLike'=> GNRouter::createUrl('like/liked/liked'),
				'actionUnlike'=> GNRouter::createUrl('like/liked/like'),
				'modelObject'	=> 'application.modules.like.models.LikeObject',
				'modelStatistic'	=> 'application.modules.like.models.LikeStatistic',
				'classUnlike'=>'wd-like-bt',
				'classLike'=>'wd-like-bt wd-liked-bt',
				
			));

			?>
			
			<?php	Yii::app()->controller->renderPartial('//common/activity/_viewAllComment',array('activityID'=>$strStatusId,'countComment'=>$countComment,'limit'=>3)) ?>
			<div class="clear"></div>
		</div>
		<?php
		
		$this->widget('widgets.comment.Comment', array(
			'objectId'=>$strStatusId,
			'viewMore'=>false,
			'countComment'=>$countComment,
			'loadJsTimeAgo'=>false,
			'limit'=>3,
			'viewItemPath'=>'widgets.comment.views.item'
		));
		?>
	</div>
</li>
<?php
}
?>