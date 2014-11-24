<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiListPhotoByOwnerAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id = null, $albumInfo = false) {
		$UserInfo = $this->controller->userInfo($id);
		$count = $this->count($UserInfo->hexID);
		$Paginate = $this->controller->paginate($count);
		$data = $this->get($UserInfo->hexID, $albumInfo, $Paginate->limit, $Paginate->offset);
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $data,
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit,
			'total' => $count
		));
	}

	function get($id, $albumInfo = false, $limit = 10, $offset = 0) {
		$Command = Yii::app()->db->createCommand()
				->select('image.id AS id')
				->from(ZoneResourceImage::model()->tableName() . " AS image")
				->join(ZoneImagePoster::model()->tableName() . " AS poster", "image.id=poster.image_id")
				->where("image.invalid = 0 AND image.data_status=:dataStatus AND poster.holder_id=:holderID")
				->order('image.created DESC')
				->offset($offset)
				->limit($limit);
		$Command->bindValues(array(
			':holderID' => IDHelper::uuidToBinary($id),
			':dataStatus' => ZoneResourceImage::DATA_STATUS_NORMAL
		));

		$photos = array();
		$results = $Command->queryAll();
		
		$currentUserID = currentUser()->id;
		foreach ($results as $_photo) {
			$photoID = IDHelper::uuidFromBinary($_photo['id'], true);
			$photo = ZoneResourceImage::model()->get($photoID);
			//unset($photo['photo']['poster']);
			
			$photo['photo']['timestamp'] = strtotime($photo['photo']['created']);
			$photo['photo']['created'] = date(DATE_ISO8601, $photo['photo']['timestamp']);
			$photo['photo']['url'] = ZoneRouter::CDNUrl("/upload/gallery/thumbs/10000-650/{$photo['photo']['image']}?album_id={$photo['photo']['album_id']}");
			
			$photo['like'] = LikeObject::model()->getLikeInfo($_photo['id'], $currentUserID);
//			if ($comment) {
//				$photo['comment'] = array(
//					'total' => ZoneComment::model()->countComments($photoID),
//					'start' => 0
//				);
//			}
			if (!empty($albumInfo)) {
				$album = ZoneResourceAlbum::model()->getAlbum(IDHelper::uuidToBinary($photo['photo']['album_id']));
				if (empty($album) || empty($album['title'])) {
					$album['title'] = ZoneResourceAlbum::TITLE_DEFAULT;
				} else {
					$album = $album->toArray();
				}
				$photo['photo']['album'] = $album;
			}
			$photos[] = $photo;
		}
		return $photos;
	}

	public function count($id) {
		$Command = Yii::app()->db->createCommand()
				->select('count(*) AS count')
				->from(ZoneResourceImage::model()->tableName() . " AS image")
				->join(ZoneImagePoster::model()->tableName() . " AS poster", "image.id=poster.image_id")
				->where("image.invalid = 0 AND image.data_status=:dataStatus AND poster.holder_id=:holderID");
		$Command->bindValues(array(
			':holderID' => IDHelper::uuidToBinary($id),
			':dataStatus' => ZoneResourceImage::DATA_STATUS_NORMAL
		));
		return (int) $Command->queryScalar();
	}

}