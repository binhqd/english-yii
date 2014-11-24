<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiListAlbumByNodeAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id = null) {
		$Paginate = $this->controller->paginate(0);
		$data = $this->get($id, $Paginate->limit, $Paginate->offset);
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit) + $data);
	}

	function get($id, $limit = 10, $offset = 0) {
		$criteria = new CDbCriteria();
		$criteria->order = 't.created desc, t.microtime desc';
		$criteria->group = 't.album_id';
		$criteria->select = 't.album_id AS id';
		$criteria->compare('t.object_id', $id);
		$criteria->compare('t.data_status', ZoneArticle::DATA_STATUS_NORMAL);
		$criteria->addCondition('t.album_id IS NOT NULL');

		// get total
		$total = intval(ZoneResourceImage::model()->count($criteria));

		$criteria->limit = $limit;
		$criteria->offset = $offset;

		// find all
		$albums = ZoneResourceImage::model()->findAll($criteria);
		$result = array();

		$userID = currentUser()->id;
//		$defaultAlbum = array(
//			'id' => 0,
//			'title' => UsersModule::t('Untitled'),
//			'description' => '',
//			'poster' => ZoneUser::model()->get(ZoneBaseContainer::SYSTEM_USERID),
//			'image_count' => 0,
//			'created' => date(DATE_ISO8601, strtotime('- 30 day'))
//		);
		foreach ($albums as $item) {
			$albumID = IDHelper::uuidFromBinary($item->id, true);
			try {
				$_info = ZoneResourceAlbum::model()->get($albumID);
			} catch (Exception $e) {
//				$_info = $defaultAlbum;
//				$_info['id'] = $albumID;
//				$_info['image_count'] = ZoneResourceAlbum::countAlbumPhotos($item->id);
				continue;
			}
			$items = ZoneResourceAlbum::model()->getImages($item->id, 1, 0);
			if (empty($items)) {
				continue;
			}
			$info = array(
				'id' => $albumID,
				'title' => $_info['title'],
				'description' => (string) $_info['description'],
				'poster' => $_info['poster'],
				'items' => $items,
				'total' => intval($_info['image_count']),
				'timestamp' => strtotime($_info['created'])
			);
			$info['like'] = LikeObject::model()->getLikeInfo($item->id, $userID);
			$info['created'] = date(DATE_ISO8601, $info['timestamp']);
			$result[] = $info;
		}
		return array(
			'total' => $total,
			'data' => $result
		);
	}

}