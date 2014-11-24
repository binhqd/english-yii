<?php 
Yii::import("application.components.notification.renderer.JLNotificationRenderer");
class ZoneApiAlbumNotification extends JLNotificationRenderer {
	public function render(&$data) {
		$notifier = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($data['notifier_id']));
		if (empty($notifier)) return null;
		
		$album = ZoneResourceAlbum::model()->find('id=:id', array(
			':id'	=> IDHelper::uuidToBinary($data['album_id'])
		));
		if(empty($album)) return null;
		//$owner = $album->owner;
		$albumNamespace = $album->AlbumNamespace;
		
		$strNodeId = "";
		$node = array();
		
		if (!empty($albumNamespace)) {
			$strNodeId = IDHelper::uuidFromBinary($albumNamespace->holder_id, true);
			$node = (ZoneInstance::initNode($strNodeId) != null ) ? ZoneInstance::initNode($strNodeId)->node->getProperties() : null;
		}
		
		$format = "";
		$message = "dummy";
		
		// $image = "";
		// $imageObj = ZoneResourceImage::getNamespaceImage($strNodeId);
		// if (!empty($imageObj)) {
		// 	$image = $imageObj->image;
		// }
		$image = ZoneResourceImage::model()->getPhotos($strNodeId,1);
		
		$this->_otherInfo = array(
			'node'	=> array(
				'id'	=> $strNodeId,
				'image'	=> $image
			)
		);
		if(empty($data['type'])) return null;
		switch ($data['type']) {
			case "postAlbum":
				$data['defaultLink'] = ZoneRouter::createUrl('/resource/album?album_id=' . $data['album_id']);
				$format = '%s created new album %s for %s';
				$userLink = "<a href='".ZoneRouter::createUrl("/profile/{$notifier->username}")."'>{$notifier->displayname}</a>";
				$albumLink = "<a href='".ZoneRouter::createUrl("/resource/album?album_id=" . $data['album_id'])."'>{$album->title}</a>";
				$nodeLink = "<a href='".ZoneRouter::createUrl("/zone/pages/detail?id={$node['zone_id']}")."'>{$node['name']}</a>";
				
				//$images = ZoneResourceImage::model()->findAllByAttributes(array('album_id'=>IDHelper::uuidToBinary($data['album_id'])));
				//$strImage = "";
				//if(!empty($images)){
					//$strImage = '<span class="floatR"><span class="wd-fr-albimg"></span><span class="wd-fr-img"><img src="'.ZoneRouter::CDNUrl("/upload/gallery/fill/50-50/{$images[0]->image}?album_id=".$data['album_id']).'" width="47" height="47" class="wd-jr" alt=""></span></span>';
				//}
				
				// $message = $strImage.'<div class="of_1">
				// 		<p class="wd_tt_mn"><span>'.$notifier->displayname.'</span> added '.count($images).' new photos.</p>
				// 		<p class="wd-posttime"><span class="wd-icon-16 wd-icon-addphoto-ntf"></span></p>
				// 	</div>';
				$message = array(
					'message' => $notifier->displayname . ' added '. count($image) . ' new photos.',
					'album_id' =>$data['album_id'],
					'album_title' => $album->title
				);
				
				// $message = sprintf($format, $userLink, $albumLink, $nodeLink, date("Y-m-d H:i:s"));
				
				return $message;
				break;

		}
	}
}