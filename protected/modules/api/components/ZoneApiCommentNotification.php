<?php 
Yii::import("application.components.notification.renderer.JLNotificationRenderer");

class ZoneApiCommentNotification extends JLNotificationRenderer {
	public function render(&$data) {
		$notifier = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($data['notifier_id']));
		if (empty($notifier)) return null;
		
		$receive = null;
		
		if(!empty($data['receive_id'])){
			$receive = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($data['receive_id']));
		}
		
		if (empty($receive)) return null;
		// dump($data);
		$object = null;
		if(empty($data['type'])) return null;
		switch($data['type']){
			case "commentAlbum":
				$object = ZoneResourceAlbum::model()->findByPk(IDHelper::uuidToBinary($data['object_id']));
				if (empty($object)) return null;
				//$album_id = IDHelper::uuidFromBinary($object->id,true);
				//$data['defaultLink'] = 1;//ZoneRouter::createUrl('/resource/album?album_id=' . $album_id.'&anchor='.$data['comment_id']);
			
				$images = ZoneResourceImage::model()->findByAttributes(array(
					'album_id'=>$object->id
				));

				$strImage = "";
				// if(empty($images)) return null;
				// else{
				// 	//$strImage = '<span class="floatR"><span class="wd-fr-albimg"></span><span class="wd-fr-img"><img src="'.ZoneRouter::CDNUrl("/upload/gallery/fill/50-50/{$images->image}?album_id=".$album_id).'" width="47" height="47" class="wd-jr" alt=""></span></span>';
				// 	$strImage = ZoneRouter::CDNUrl("/upload/gallery/fill/50-50/{$images->image}?album_id=".$album_id);
				// }
				//$photo_id = IDHelper::uuidFromBinary($images->id,true);
				
				if(empty($data['created'])) $data['created'] = date(DATE_ISO8601, strtotime(date("Y-m-d")));
				$message = array(
					// 'strimage'  => $strImage,
					'images' 	=> $images,
					"cdn" 	=>ZoneRouter::CDNUrl("/"),
					'message' 	=> $notifier->displayname . 'commented on ' .$object->title . 'album',
					'created' 		=> $data['created']
				);

				/*
				$message = $strImage.'<div class="of_1">
					<p class="wd_tt_mn"><span>'.$notifier->displayname.'</span>  commented on <span>'.$object->title.'</span> album.</p>
					<p class="wd-posttime"><span class="wd-icon-16 wd-icon-commented-ntf"></span><span class="timeago" data-title="'.$data['created'].'"></span></p>
				</div>';
				*/
				return $message;
			break;
			case "commentStatus":
				
				$author = null;
				if(!empty($data['author_id'])){
					$author = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($data['author_id']));
				}
				
				if (empty($author)) return null;
				$object = ZoneStatus::model()->findByPk(IDHelper::uuidToBinary($data['object_id']));
				if (empty($object)) return null;
				$data['defaultLink'] = ZoneRouter::createUrl('/status/view?id='.$data['object_id']);

				if(empty($data['content'])){
					$format = $notifier->displayname.' commented on '.$author->displayname.' status.';
				}else{
					$format = $notifier->displayname.' commented on '.$author->displayname.' status "'.JLStringHelper::char_limiter_word($data['content'],150).'"';
				}
				$created = (empty($data['created'])) ? date(DATE_ISO8601, time()) : $data['created'];
				
				/*
				$message = '<a href="#" class="comment-status" style="display:none" status_id="'.IDHelper::uuidFromBinary($object->id,true).'" comment_id="'.$data['comment_id'].'">'.IDHelper::uuidFromBinary($object->id,true).'</a><div class="of_1">'.$format.'
					<p class="wd-posttime"><span class="wd-icon-16 wd-icon-commented-ntf"></span><span class="timeago" data-title="'.$created.'"></span></p>
				</div>';
				*/
				$message = array(
					'status_id' 	=> IDHelper::uuidFromBinary($object->id,true),
					'comment_id' 	=> $data['comment_id'],
					'message' 		=> $format,
					'created' 		=> $created
				);

				return $message;
			break;
			/*VuNDH add code*/
			case "commentArticle":
				$object = ZoneArticle::model()->findByPk(IDHelper::uuidToBinary($data['object_id']));
				if (empty($object)) return null;
				$data['defaultLink'] = ZoneRouter::createUrl('/article?article_id='.$data['object_id']);
				if(empty($data['content'])){
					$format = $notifier->displayname.' commented on article.';
				}else{
					$format = $notifier->displayname.' commented on '.$object->title.' article "'.JLStringHelper::char_limiter_word($data['content'],150).'"';
				}
				$created = (empty($data['created'])) ? date(DATE_ISO8601, time()) : $data['created'];
				/*
				$message = '<a href="#" class="comment-article" style="display:none" article_id="'.IDHelper::uuidFromBinary($object->id,true).'" comment_id="'.$data['comment_id'].'">'.IDHelper::uuidFromBinary($object->id,true).'</a><div class="of_1">'.$format.'
					<p class="wd-posttime"><span class="wd-icon-16 wd-icon-commented-ntf"></span><span class="timeago" data-title="'.$created.'"></span></p>
				</div>';
				*/
				$message = array(
					'article_id' 	=> IDHelper::uuidFromBinary($object->id,true),
					'comment_id' 	=> $data['comment_id'],
					'message' 		=> $format,
					'created' 		=> $created
				);

				return $message;
				break;
		}

	}

}