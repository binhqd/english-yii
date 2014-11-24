<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiListPhotoByAlbumAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id) {
		$count = $this->count($id);
		$Paginate = $this->controller->paginate($count);
		$data = $this->get($id, $Paginate->limit, $Paginate->offset);
		if (empty($data)) {
			throw new Exception(UsersModule::t('The album id {id} is not found.', array(
				'{id}' => $id
			)));
		}
		try {
			$album = ZoneResourceAlbum::get($id);
		} catch (Exception $e) {
			$album = array(
				'id' => $id,
				'title' => UsersModule::t('Untitled'),
				'description' => 'Unknow album',
				'created' => time(),
				'image_count' => $count,
				'thumbnail' => null,
				'invalid' => '0',
				'data_status' => '1',
				'poster' => $data[0]['photo']['poster'],
				'countAlbumPhotos' => '4'
			);
		}
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'album' => $album,
			'data' => $data,
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit,
			'total' => $count
		));
	}

	function get($id, $limit = 10, $offset = 0) {
		$Command = Yii::app()->db->createCommand()
				->select('id')
				->from(ZoneResourceImage::model()->tableName())
				->where("album_id=:albumID and invalid=0")
				->where("album_id=:albumID and invalid=0 and data_status=:dataStatus")
				->limit($limit)
				->offset($offset);

		$Command->bindValues(array(
			':albumID' => IDHelper::uuidToBinary($id),
			':dataStatus' => ZoneResourceImage::DATA_STATUS_NORMAL
		));
		$results = $Command->queryAll();
		$photos = array();

		$currentUserID = currentUser()->id;
		foreach ($results as $item) {
			$photoID = IDHelper::uuidFromBinary($item['id'], true);
			$photo = ZoneResourceImage::model()->get($photoID);
			$photo['photo']['timestamp'] = strtotime($photo['photo']['created']);
			$photo['photo']['created'] = date(DATE_ISO8601, $photo['photo']['timestamp']);

			$photo['like'] = LikeObject::model()->getLikeInfo($item['id'], $currentUserID);

			$photos[] = $photo;
		}
		return $photos;
	}

	public function count($id) {
		$Command = Yii::app()->db->createCommand()
				->select('count(*) as count')
				->from(ZoneResourceImage::model()->tableName())
				->where("album_id=:albumID and invalid=0 and data_status=:dataStatus");
		$Command->bindValues(array(
			':albumID' => IDHelper::uuidToBinary($id),
			':dataStatus' => ZoneResourceImage::DATA_STATUS_NORMAL
		));
		return (int) $Command->queryScalar();
	}

}