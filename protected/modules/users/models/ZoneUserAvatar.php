<?php

Yii::import('greennet.modules.gallery.models.GNGalleryItem');
Yii::import('greennet.modules.gallery.components.IPhotoItem');

class ZoneUserAvatar extends GNGalleryItem implements IPhotoItem {

	public $prefix = "avatar_";

	const DATA_STATUS_DELETED = 0;
	const DATA_STATUS_NORMAL = 1;

	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function getName() {
		return __CLASS__;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'zone_user_avatars';
	}

	public function behaviors() {
		$cacheBehavior = array(
			'cache'	=> array(
				'class'		=> 'greennet.components.cache.memcache.GNMemcacheBehavior',
				'prefix'	=> 'zonephoto_'
			)
		);
		
		return CMap::mergeArray(parent::behaviors(), $cacheBehavior);
	}

	public function getTotal($objectID = null) {
		return $this->countPhotos($objectID);
	}

	/**
	 * This is function used to get count avatar
	 * @see GNGalleryItem::countPhotos()
	 */
	public function countPhotos($strObjectID) {
		$cnt = 0;
		if (isset($strObjectID)) {
			$cnt = $this->count('object_id=:object_id and invalid=0 AND data_status = '.ZoneResourceImage::DATA_STATUS_NORMAL, array(':object_id' => "{$this->prefix}{$strObjectID}"));
		}
		return $cnt;
	}

	/**
	 * Override default $user->poster in GNGalleryItem
	 * @return GNUser object
	 */
	public function getPoster() {
		$id = str_replace($this->prefix, "", $this->object_id);
		return ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($id));
	}

	public function getPhotos($strObjectID, $limit = 5, $offset = 0) {
		$command = Yii::app()->db->createCommand()
		->select('HEX(photo.id) AS id')
		->from($this->tableName() . " AS photo")
		->where('photo.object_id = :object_id AND photo.invalid = 0 AND data_status = 1')
		->order('photo.score DESC, photo.created DESC, photo.microtime DESC')
		->offset($offset)
		->limit($limit);

		$command->bindValues(array(
			':object_id' => "{$this->prefix}{$strObjectID}"
		));

		$results = $command->queryAll();

		$photos = array();

		foreach ($results as $photo) {
			$this->deleteCache($photo['id']);
			$photo = $this->get($photo['id']);

			$strToken = md5(uniqid(32));

			$photo['photo']['created'] = date(DATE_ISO8601, strtotime($photo['photo']['created']));
			$photo['photo']['url'] = ZoneRouter::CDNUrl("/upload/gallery/fill/165-10000/{$photo['photo']['image']}?album_id={$photo['photo']['album_id']}");

			//$photo['like']['actionUnlike']	= ZoneRouter::createUrl('/photo/unlike');
			$photo['like']['token'] = $strToken;
			$photo['token'] = $strToken;

			$photos[] = $photo;
		}

		return $photos;
	}

	public function getAvatars($strObjectID, $limit = 5, $offset = 0) {
		$avatars = $this->getPhotos($strObjectID, $limit, $offset);

		return $avatars;
	}

	public function cleanUp($uploader = null) {
		$attributes = $this->attributes;
		$albumID = $this->albumID;

		$this->delete();

		// also remove all likes
		ZoneLike::model()->removeAllByObjectID($attributes['id']);

		// also remove all comment
		ZoneComment::model()->removeAllByObjectID($attributes['id']);

		// Also remove related activities
		// Remove physical image
		// Remove image from cloud
		$info['s3Path'] = "/upload/user-photos/{$albumID}/";
		$info['filename'] = $attributes['image'];

		// Remove facebook sync photo (huytbt added)
		GNSyncFacebookPhoto::model()->deleteAll('photo_id=:photo_id', array(
			':photo_id' => $attributes['id'],
		));

		$uploader->remove($info);

		/* Remove avatar - VuNDH add code */
		$user = currentUser();
		$profile = $user->profile;
		$profile->image = '';
		if ($profile->save()) {
			$user->updateState(true, false);
		}
	}

	/**
	 * This is function use to hide avatar
	 */
	public function hideImage() {
		$photoID = IDHelper::uuidFromBinary($this->id,true);
		$this->data_status = self::DATA_STATUS_DELETED;
		if ($this->cachable) {
			/*Delete cache*/
			$this->deleteCache($photoID);
		}
		if ($this->save()) {
			
			return true;
		}
		return false;
	}

	/**
	 * This is function use to get max score photo off profile
	 */
	public function getMaxScore($strObjectID) {
		$criteria = new CDbCriteria;
		$criteria->compare('data_status', self::DATA_STATUS_NORMAL);
		$criteria->compare('object_id', $strObjectID);
		$criteria->order = 'score DESC, created DESC, microtime DESC ';
		$photo = $this->model()->find($criteria);
		return $photo;
	}

}