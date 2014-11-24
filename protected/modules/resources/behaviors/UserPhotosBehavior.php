<?php
/**
 * GNUserFollowingBehavior - This behavior is used to support an object user. When an user object has attached this behavior it'll have all methods of this behavior.
 * @author binhqd <binhqd@gmail.com>
 * @version 1.0
 * @created 2013-10-07 9:27 AM
 */
class UserPhotosBehavior extends CActiveRecordBehavior
{
	public function getPhotos($limit = 6, $offset = 0, $order = 'created desc, microtime desc') {
		$_defaultOptions = array(
			'order'	=> 'created desc, microtime desc'
		);
		
		$options = CMap::mergeArray($_defaultOptions, array('order' => $order));
		
		$user = $this->owner;
		
		$command = Yii::app()->db->createCommand()
		->select('image.id')
		->from(ZoneResourceImage::model()->tableName() . ' as image')
		->join(ZoneImagePoster::model()->tableName()." as image_mapped", "image_mapped.holder_id=:user_id and invalid=0 and image_mapped.image_id=image.id and data_status=".ZoneResourceImage::DATA_STATUS_NORMAL)
		->where('image.album_id != ""')
		->order($options['order'])
		->limit($limit)
		->offset($offset);
		
		$command->bindValue(':user_id', $user->id);
		
		$results = $command->queryAll();
		
		$photos = array();
		foreach ($results as $item) {
			$photos[] = ZoneResourceImage::model()->get(IDHelper::uuidFromBinary($item['id']));
		}
		
		return $photos;
	}
	
	/**
	 * count all photos of users, include profile photos & contribute photos
	 * @return Ambigous <string, mixed, unknown>
	 */
	public function countPhotosOfUser() {
		$user = $this->owner;
		
		// contributed photos by current user
		$contributedPhotosCommand = Yii::app()->db->createCommand()
		->select('count(*)')
		->from(ZoneResourceImage::model()->tableName() . ' as image')
		->join(ZoneImagePoster::model()->tableName()." as poster_mapped", "poster_mapped.holder_id=:poster_id and poster_mapped.image_id = image.id and invalid=0 and data_status=".ZoneResourceImage::DATA_STATUS_NORMAL)
		->where('image.album_id != ""')
		;
		
		$contributedPhotosCommand->bindValue(':poster_id', $user->id);
		$total = $contributedPhotosCommand->queryScalar();
		
		// profile photos of current users
		$countUserAvatars = Yii::app()->db->createCommand()
		->select('count(*)')
		->from(ZoneUserAvatar::model()->tableName() . ' as avatar')
		->where('avatar.object_id=:object_id AND data_status = '.ZoneResourceImage::DATA_STATUS_NORMAL, array(':object_id'=> ZoneUserAvatar::model()->prefix . strtolower($user->hexID)))
		->queryScalar();
		
		return $total + $countUserAvatars;
	}
	
	/**
	 * This behavior is used to get albums of a user
	 * @param int $limit
	 * @param int $offset
	 */
	public function getAlbums($limit = 10, $offset = 0) {
		$user = $this->owner;
		
		
	}
}