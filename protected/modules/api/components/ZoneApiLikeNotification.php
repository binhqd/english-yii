<?php 
Yii::import("application.components.notification.renderer.JLNotificationRenderer");
class ZoneApiLikeNotification extends JLNotificationRenderer {
	public function render(&$data) {
		$notifier = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($data['notifier_id']));
		if (empty($notifier)) return null;
		$receive = null;
		if(!empty($data['receive_id'])){
			$receive = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($data['receive_id']));
		}
		
		if (empty($receive)) return null;
		$object = null;
		if(empty($data['type'])) return null;
		switch($data['type']){
			case "likeArticle":
				$object = ZoneArticle::model()->findByPk(IDHelper::uuidToBinary($data['object_id']));
				if (empty($object)) return null;
				$article_id = IDHelper::uuidFromBinary($object->id,true);
				$data['defaultLink'] = ZoneRouter::createUrl("/article?article_id=".$article_id);
				
				$format = '%s likes article %s';
				$userLink = "<a href='".ZoneRouter::createUrl("/profile/{$notifier->username}")."'>{$notifier->displayname}</a>";
				$article = "<a class='like-article-notification' href='".ZoneRouter::createUrl("/article?article_id={$article_id}")."'>{$object->title}</a>";
				
				// $strImage = "";
				if(!empty($object->image)){
					$strImage = ZoneRouter::CDNUrl("/upload/gallery/fill/50-50/{$object->image}");
				}
				
				$message = array(
					'strImage' => isset($strImage) ? $strImage: null,
					'message' => $notifier->displayname . ' like article ' . $object->title,
					'created' => $data['created'],
					'article_id' => $article_id,
				);
				
				// $message = sprintf($format, $userLink,$article);
				return $message;
				
			break;
			case "likeAlbum":
				$object = ZoneResourceAlbum::model()->findByPk(IDHelper::uuidToBinary($data['object_id']));
				if (empty($object)) return null;
				$album_id = IDHelper::uuidFromBinary($object->id,true);
				
				// $data['defaultLink'] = ZoneRouter::createUrl('/resource/album?album_id=' . $album_id);
				
				$images = ZoneResourceImage::model()->findByAttributes(array(
					'album_id'=>$object->id
				));
				
				// if(empty($images)) return null;
				// else{
				// 	$strImage = ZoneRouter::CDNUrl("/upload/gallery/fill/50-50/{$images->image}?album_id=".$album_id);
				// }

				// $photo_id = IDHelper::uuidFromBinary($images->id,true);
				// $format = '%s likes album %s';
				// $userLink = "<a href='".ZoneRouter::createUrl("/profile/{$notifier->username}")."'>{$notifier->displayname}</a>";
				// $album = "<a  href='".ZoneRouter::createUrl("/photos/viewPhoto?photo_id={$photo_id}&album_id={$album_id}")."' album_id='{$album_id}' photo_id='{$photo_id}' filename='{$images->image}'  class='lnkViewPhotoDetail like-album-notification'>{$object->title}</a>";
				
				$message = array(
					// 'strImage' => isset($strImage) ? $strImage: null,
					'images' => $images,
					'message' => $notifier->displayname . ' like album ' . $object->title,
					'created' => $data['created'],
					'album_id' => $album_id,
				);
				
				// $message = sprintf($format, $userLink,$album);
				return $message;
				
			break;
			case "likeImage":
				$object = ZoneResourceImage::model()->findByPk(IDHelper::uuidToBinary($data['object_id']));
				if (empty($object)) return null;
				
				$images = $object;
				$album_id = IDHelper::uuidFromBinary($object->album_id,true);
				$photo_id = IDHelper::uuidFromBinary($images->id,true);
				//$format = '%s likes photo for album %s';
				//$userLink = "<a href='".ZoneRouter::createUrl("/profile/{$notifier->username}")."'>{$notifier->displayname}</a>";
				//$album = "<a href='".ZoneRouter::createUrl("/photos/viewPhoto?photo_id={$photo_id}&album_id={$album_id}")."' album_id='{$album_id}' photo_id='{$photo_id}' filename='{$images->image}'  class='lnkViewPhotoDetail'>{$object->title}</a>";
				
				$message = array(
					'strImage' => isset($strImage) ? $strImage: null,
					'message' => $notifier->displayname . ' likes photo for album ' . $object->title,
					'created' => $data['created'],
					'album_id' => $album_id,
					'photo_id' => $photo_id,
				);
				
				//$message = sprintf($format, $userLink,$album);
				return $message;
				
			break;
			case "likeStatus":
				$object = ZoneStatus::model()->findByPk(IDHelper::uuidToBinary($data['object_id']));
				if (empty($object)) return null;
				$format = '%s likes  status: "'.JLStringHelper::char_limiter_word($object->title,100).'"';
				$userLink = "<a href='".ZoneRouter::createUrl("/profile/{$notifier->username}")."' class='like-status' status_id='".IDHelper::uuidFromBinary($object->id,true)."'>{$notifier->displayname}</a>";
				
				
				$message = '<div class="of_1">
						<p class="wd_tt_mn">'.$userLink.' like status "'.JLStringHelper::char_limiter_word($object->title,100).'"</p>
						<p class="wd-posttime"><span class="wd-icon-16 wd-icon-likes-ntf"></span><span class="timeago" data-title="'.$data['created'].'"></span></p>
					</div>';

				$message = array(
					'strImage' => isset($strImage) ? $strImage: null,
					'message' => $notifier->displayname . ' likes  status: "' . JLStringHelper::char_limiter_word($object->title,100) . '"',
					'created' => $data['created'],
				);
				
				// $message = sprintf($format, $userLink);
				return $message;
				
				
			break;
		}
		
		
		
		
	}
}