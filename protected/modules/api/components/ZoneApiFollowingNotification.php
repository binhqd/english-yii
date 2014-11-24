<?php 
Yii::import("application.components.notification.renderer.JLNotificationRenderer");
class ZoneApiFollowingNotification extends JLNotificationRenderer
{
	public function render(&$data) {
		$notifier = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($data['notifier_id']));
		if (empty($notifier)) return null;
		$node = $this->_getNodeInfo($data['object_id']);
		if(empty($data['type'])) return null;
		switch ($data['type']) {
			case "followNode":
				if(empty($node)) return null;
				$data['defaultLink'] = GNRouter::createUrl("/zone/pages/detail/id/".$node['zone_id']);
				
				//$image = isset($node['image'])? ZoneRouter::CDNUrl("/upload/gallery/fill/50-50/{$node['image']}").'?album_id='.$node['album_id'] : null;
				$image = ZoneResourceImage::model()->getPhotos($node['zone_id'],1);

				/*
				$message = $image.'<div class="of_1">
					<p class="wd_tt_mn"><span>'.$notifier->displayname.'</span>  following topic <span>'.$node['name'].'</span></p>
					<p class="wd-posttime"><span class="wd-icon-16 wd-icon-following-ntf"></span><span class="timeago" data-title="'.$data['created'].'"></span></p>
				</div>';
				*/
				$message = array(
					'image' =>isset($image)?$image:null,
					"cdn" 	=>ZoneRouter::CDNUrl("/"),
					'message' => $notifier->displayname . 'following topic' . $node['name'],
					'created' => $data['created']
				);

				return $message;
				// return "<a href='".GNRouter::createUrl("/profile/" . $notifier->username)."'>{$notifier->displayname}</a> follow <a href='".GNRouter::createUrl("/zone/pages/detail", array('id'=>$node['zone_id']))."'>{$node['name']}</a>";
				break;
		}
	}

	/**
	 * This method is used to get node information
	 * @param String $strNodeId IS of node
	 */
	private function _getNodeInfo($strNodeId)
	{
		// $image = null;
		// $album_id = null;
		// $resourceImage = ZoneResourceImage::getNamespaceImage($strNodeId);
		// if (!empty($resourceImage)){
		// 	$image = $resourceImage->image;
		// 	$album_id = $resourceImage->albumID;
		// }
		$node = (ZoneInstance::initNode($strNodeId) != null ) ? ZoneInstance::initNode($strNodeId)->toArray() : null;
		
		if (empty($node)) return null;
		return array(
			'zone_id'	=> $node['zone_id'],
			'name'		=> $node['name'],
			//'image'		=> $image,
			//'album_id'		=> $album_id,
		);
	}
}