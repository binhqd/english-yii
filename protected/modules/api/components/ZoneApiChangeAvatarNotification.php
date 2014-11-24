<?php 
Yii::import("application.components.notification.renderer.JLNotificationRenderer");
class ZoneApiChangeAvatarNotification extends JLNotificationRenderer {
	public function render(&$data) {
		$notifier = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($data['notifier_id']));
		if (empty($notifier)) return null;
		$receive = null;
		if(!empty($data['receive_id'])){
			$receive = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($data['receive_id']));
		}
		
		if (empty($receive)) return null;
		if(empty($data['filename'])) return null;
		$object = null;
		if(empty($data['type'])) return null;
		switch($data['type']){
			
			case "changeAvatar":
				// $receive_id = $data['receive_id'];
				// $object_id = $data['object_id'];
				// $object = ZoneUserAvatar::model()->findByPk(IDHelper::uuidToBinary($data['object_id']));
				// if (empty($object)) return null;
				// $format = '%s changed his %s';
				// $userLink = "<a href='".ZoneRouter::createUrl("/profile/{$notifier->username}")."' >{$notifier->displayname}</a>";
				// $linkProfile = "<a href='".ZoneRouter::createUrl("/photos/viewPhoto?photo_id={$object_id}&album_id={$receive_id}")."&type=user' photo_id='".$object_id."' album_id='".$receive_id."' filename='".$data['filename']."' class='wd-thumb-img lnkViewPhotoDetail' type='user'>profile picture</a>";
				
				$message = array(
					'message' => $notifier->displayname . ' changed his avatar'
				);

				// $message = '<span class="floatR"><span class="wd-fr-albimg"></span><span class="wd-fr-img"><img src="'.ZoneRouter::CDNUrl("/upload/user-photos/{$notifier->hexID}/fill/47-47/{$notifier->profile->image}").'" width="47" height="47" class="wd-jr" alt=""></span></span>
				// 		<div class="of_1">
				// 			<p class="wd_tt_mn"><span>'.$userLink.'</span> changed his '.$linkProfile.'</p>
				// 			<p class="wd-posttime"><span class="wd-icon-16 wd-icon-addphoto-ntf"></span><span class="timeago" data-title="'.$data['created'].'"></span></p>
				// 		</div>';
				
				// $message = sprintf($format, $userLink,$linkProfile);
				return $message;
				
				
			break;
		}
		
		
		
		
	}
}