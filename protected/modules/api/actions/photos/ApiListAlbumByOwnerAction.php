<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiListAlbumByOwnerAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id = null, $type = 'all') {
		$UserInfo = $this->controller->userInfo($id);
		//$count = $this->count($UserInfo->hexID);
		$Paginate = $this->controller->paginate(0);
		switch ($type) {
			case 'contribute':
				break;
			case 'profile':
				break;
			case 'all':
			default:
				$type = 'all';
		}
		$data = $this->{$type}($UserInfo->hexID, $Paginate->limit, $Paginate->offset);
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $data,
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit,
			'type' => $type
		));
	}

	function profile($id, $limit = 10, $offset = 0) {
		$Command = Yii::app()->db->createCommand()
				->select('own_images.album_id as id')
				->from(ZoneResourceImage::model()->tableName() . " as own_images")
				->join(ZoneImagePoster::model()->tableName() . " as holder", "holder.image_id=own_images.id AND own_images.invalid=0 AND own_images.data_status=:dataStatus")
				->join(ZoneResourceAlbum::model()->tableName() . ' as own_albums', 'own_images.album_id=own_albums.id')
				->where('holder.holder_id=:ownerID AND own_images.object_id=:strOwnerID')
				->group('own_images.album_id')
				->order('own_images.created DESC,  own_images.microtime DESC')
				->offset($offset)
				->limit($limit);
		$Command->bindValues(array(
			':ownerID' => IDHelper::uuidToBinary($id),
			':dataStatus' => ZoneResourceImage::DATA_STATUS_NORMAL,
			':strOwnerID' => $id
		));
		$albums = array();

		$currentUserID = currentUser()->id;
		foreach ($Command->queryAll() as $val) {
			$albumID = IDHelper::uuidFromBinary($val['id'], true);
			$_info = ZoneResourceAlbum::model()->get($albumID);
			$items = ZoneResourceAlbum::model()->getImages($val['id'], 1, 0);
			if (empty($items)) {
				continue;
			}
			$info = array(
				'id' => $_info['id'],
				'title' => $_info['title'],
				'description' => (string) $_info['description'],
				'poster' => $_info['poster'],
				'items' => $items,
				'total' => intval($_info['image_count']),
				'timestamp' => strtotime($_info['created'])
			);
			$info['like'] = LikeObject::model()->getLikeInfo($val['id'], $currentUserID);
			$info['created'] = date(DATE_ISO8601, $info['timestamp']);
			$albums[] = $info;
		}
		return $albums;
	}

	function contribute($id, $limit = 10, $offset = 0) {
		$Command = Yii::app()->db->createCommand()
				->select('distinct own_images.id as id, own_images.object_id, own_images.album_id, count(*) as total')
				->from(ZoneResourceImage::model()->tableName() . " as own_images")
				->join(ZoneImagePoster::model()->tableName() . " as holder", "holder.image_id=own_images.id AND own_images.invalid = 0 AND own_images.data_status = :dataStatus")
				->where(' holder.holder_id=:ownerID AND own_images.object_id <> :strOwnerID AND own_images.album_id IS NOT NULL')
				->group('own_images.object_id')
				->order('own_images.created desc, own_images.microtime desc')
				->offset($offset)
				->limit($limit);
		$Command->bindValues(array(
			':ownerID' => IDHelper::uuidToBinary($id),
			':dataStatus' => ZoneResourceImage::DATA_STATUS_NORMAL,
			':strOwnerID' => $id
		));

		$results = $Command->queryAll();
		$albums = array();

		$currentUserID = currentUser()->id;
		foreach ($results as $item) {
			try {
				$Node = ZoneInstanceRender::get($item['object_id']);
			} catch (Exception $ex) {
				$album = ZoneResourceAlbum::model()->findByPk($item['album_id']);
				if (!empty($album)) {
					$album->setInvalid();
				}
				$photo = ZoneResourceImage::model()->findByPk($item['id']);
				$photo->invalid = 1;
				$photo->save();
				continue;
			}

			$photo = ZoneResourceImage::model()->get(IDHelper::uuidFromBinary($item['id'], true));
			$node = $Node->toArray();
			$album = array(
				'id' => $node['zone_id'],
				'title' => $node['name'],
				'description' => '',
				'node' => $node,
				'total' => intval($item['total'])
			);
			if (!empty($photo['photo'])) {
				$album['items'][] = $photo;
			} else {
				$album['items'] = array();
				/* Get like photo */
				$album['like']['count'] = 0;
			}
			/* Get like photo */
			$albumID = IDHelper::uuidToBinary($photo['photo']['album_id']);
			$album['like'] = LikeObject::model()->getLikeInfo($albumID, $currentUserID);
			$albums[] = $album;
		}
		return $albums;
	}

	function all($id, $limit = 10, $offset = 0) {
		$Command = Yii::app()->db->createCommand()
				->select('image.album_id as id,image.object_id')
				->from(ZoneResourceImage::model()->tableName() . " as image")
				->join(ZoneImagePoster::model()->tableName() . " as poster", "poster.image_id=image.id")
				->where('image.invalid=0 AND image.data_status=:dataStatus AND poster.holder_id=:ownerID')
				->group('image.album_id')
				->order('image.created DESC')
				->offset($offset)
				->limit($limit);
		$Command->bindValues(array(
			':ownerID' => IDHelper::uuidToBinary($id),
			':dataStatus' => ZoneResourceImage::DATA_STATUS_NORMAL
		));
		$results = $Command->queryAll();
		$currentUserID = currentUser()->id;
		$untitled = UsersModule::t('Untitled');
		$poster = ZoneUser::model()->get($id);

		$albums = array();
		foreach ($results as $val) {
			if (empty($val['id'])) {
				continue;
			}
			$albumID = IDHelper::uuidFromBinary($val['id'], true);
			try {
				$_info = ZoneResourceAlbum::model()->get($albumID);
			} catch (Exception $e) {
				if ($val['object_id'] != $id) {
					$node = ZoneInstance::initNode((string) $val['object_id']);
				}
			}
			if (empty($_info)) {
				$_info = array(
					'id' => $albumID,
					'description' => 'Unknow album',
					'title' => !empty($node) ? $node->node->name : $untitled,
					'poster' => $poster,
					'created' => time()
				);
			}
			$total = intval(ZoneResourceAlbum::countAlbumPhotos($val['id']));
			if (!$total) {
				continue;
			}
			$items = ZoneResourceAlbum::model()->getImages($val['id'], 1, 0);
			if (empty($items)) {
				continue;
			}
			$binID = IDHelper::uuidToBinary($_info['id']);
			$info = array(
				'id' => $_info['id'],
				'title' => $_info['title'],
				'description' => (string) $_info['description'],
				'poster' => $_info['poster'],
				'items' => $items,
				'timestamp' => strtotime($_info['created']),
				'total' => $total
			);
			unset($_info, $node);
			$info['created'] = date(DATE_ISO8601, $info['timestamp']);
			$info['like'] = LikeObject::model()->getLikeInfo($binID, $currentUserID);
			$albums[] = $info;
		}
		return $albums;
	}

}