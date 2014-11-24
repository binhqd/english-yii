<?php

class ImageReceiverPhoto
{
	
	public static function get($strObjectId = null){
		$data = array(
			'type'=>'node'
		);
		try{
			$objNode = ZoneInstanceRender::get($strObjectId);
			if(!empty($objNode)){
				$resourceImage = ZoneResourceImage ::getNamespaceImage($objNode->zone_id);
				$data['image'] = null;
				$data['album_id'] = null;
				if (!empty($resourceImage)){
					$data['image'] = $resourceImage['photo']['image'];
					$data['album_id'] = $resourceImage['photo']['album_id'];
				}
				$data['id'] = $objNode->zone_id;
				$data['displayname'] = $objNode->node->name;
				$data['label'] = ZoneInstance::getNotableLabel($objNode->zone_id) ? ZoneInstance::getNotableLabel($objNode->zone_id) : "";
			}else{
				
				$data['image'] = null;
				$data['album_id'] = null;
				$data['id'] = -1;
				$data['displayname'] = null;
				$data['label'] = null;
				$data['type'] = "none";
			}
		}catch(Exception $e){
			$data['image'] = null;
			$data['album_id'] = null;
			$data['id'] = -1;
			$data['displayname'] = null;
			$data['label'] = null;
			$data['type'] = "none";
		}
		return $data;
	}
	
}
