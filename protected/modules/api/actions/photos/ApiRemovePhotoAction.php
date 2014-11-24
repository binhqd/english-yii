<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiRemovePhotoAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id = null) {
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $this->remove($id)
		));
	}

	function remove($id) {
		$binID = IDHelper::uuidToBinary($id);
		$type = 'photo';
		$photo = ZoneResourceImage::model()->findByPk($binID);
		if (!$photo) {
			$photo = ZoneUserAvatar::model()->findByPk($binID);
			$type = 'avatar';
		}
		if (!$photo) {
			throw new Exception(UsersModule::t('The photo is not found.'));
		}
		$poster = $photo->poster;
		if (!$poster || $poster->id != currentUser()->id) {
			throw new Exception(UsersModule::t('This article does not belong to you.'), 403);
		}
		return $this->{'_remove' . ucfirst($type)}($photo);
	}

	protected function _removePhoto(ZoneResourceImage $photo) {
		if (!$photo->hideImage()) {
			throw new Exception(UsersModule::t('The photo has not been removed.'));
		}
		if ($photo->album_id) {
			/* Get album of photo */
			$album = ZoneResourceAlbum::model()->findByPk($photo->album_id);
			if (!empty($album)) {
				$photosOfAlbum = ZoneResourceImage::model()->getImagesFromAlbum($photo->album_id, -1, 0);
				if (count($photosOfAlbum) == 0) {
					$album->hideAlbumById($photo->album_id);
				}
			}
		}
		/* Get photo has score max */
		$photoMaxScore = ZoneResourceImage::model()->getMaxScore($photo->object_id);
		ZoneResourceImage::model()->deleteCache(IDHelper::uuidFromBinary($photo->id, true));

		$data = null;
		if ($photoMaxScore) {
			$data = $photoMaxScore->get(IDHelper::uuidFromBinary($photoMaxScore->id, true));
		}
		return $data;
	}

	protected function _removeAvatar(ZoneUserAvatar $photo) {
		if (!$photo->hideImage()) {
			throw new Exception(UsersModule::t('The photo has not been removed.'));
		}
		// Set facebook photo to not done (for sync again)
		$facebookPhotos = GNSyncFacebookPhoto::model()->findAllByAttributes(array(
			'photo_id' => $photo->id,
		));
		foreach ($facebookPhotos as $fphoto) {
			$fphoto->done = 0;
			$fphoto->save();
		}
		/* Get photo has score max */
		$photoMaxScore = ZoneUserAvatar::model()->getMaxScore($photo->object_id);
		$user = GNUserProfile:: model()->findByAttributes(array('user_id' => $photo->poster->id));
		if (!empty($photoMaxScore)) {
			if (empty($user)) {
				throw new Exception(UsersModule::t('User is invalid.'));
			}
			$user->image = $photoMaxScore->image;
		} elseif (!empty($user)) {
			$user->image = 'user-thumb-default-male.png';
		}
		if (!empty($user) && !$user->save()) {
			throw new Exception(UsersModule::t('The profile photo could not be updated.'));
		}
		$data = null;
		if ($photoMaxScore) {
			$data = $photoMaxScore->get(IDHelper::uuidFromBinary($photoMaxScore->id, true));
		}
		return $data;
	}

}