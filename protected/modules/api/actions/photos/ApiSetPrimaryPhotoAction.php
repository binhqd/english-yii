<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiSetPrimaryPhotoAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id = null) {
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $this->set($id),
			'message' => UsersModule::t('The photo has been marked primary')
		));
	}

	function set($id) {
		$binID = IDHelper::uuidToBinary($id);
		$photo = ZoneResourceImage::model()->findByPk($binID);
		$isUser = false;
		if (!($photo && ($Node = ZoneInstance::initNode($photo->object_id)) &&
				$Node->getId() && !$Node->isUserNode())) {
			$photo = ZoneUserAvatar::model()->findByPk($binID);
			if (empty($photo)) {
				throw new Exception(UsersModule::t('The photo is not found or is not a profile image.'));
			}
			$isUser = true;
		}
		if (empty($photo)) {
			throw new Exception(UsersModule::t('The photo is not found.'));
		}
		$poster = $photo->poster;
		$CurrentUser = $this->controller->userInfo(null);
		if (empty($poster) || $poster->id != $CurrentUser->id) {
			throw new Exception(UsersModule::t('This photo does not belong to you.'), 403);
		}
		if (!empty($isUser)) {
			ZoneUserAvatar::model()->deleteCache($id);
			if (empty($CurrentUser->profile)) {
				throw new Exception(UsersModule::t('You need create profile.'));
			}
			$profile = $CurrentUser->profile;
			$profile->image = $photo->image;
			$photoMaxScore = ZoneUserAvatar::model()->getMaxScore($photo->object_id);
			$maxScore = 0;
			if (!empty($photoMaxScore)) {
				$maxScore = $photoMaxScore->score;
			}
			$photo->score = floatval(round($maxScore + 1, 4));
			if (!$photo->save() || !$profile->save()) {
				throw new Exception(UsersModule::t('The photo could not marked primary'));
			}
			ZoneUser::model()->deleteCache($id);
			$CurrentUser->updateState(true, false);
			$primary = $photo->get($id, $CurrentUser->hexID);
		} else {
			ZoneResourceImage::model()->deleteCache($id);
			$photoMaxScore = ZoneResourceImage::model()->getMaxScore($photo->object_id);
			$maxScore = 0;
			if (!empty($photoMaxScore)) {
				$maxScore = $photoMaxScore->score;
			}
			$photo->score = floatval(round($maxScore + 1, 4));
			if (!$photo->save()) {
				throw new Exception(UsersModule::t('The photo could not marked primary'));
			}
			$primary = $photo->get($id);
		}
		return $primary;
	}

}