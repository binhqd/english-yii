<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiCreateVideoFromYoutubeAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run() {
		if (empty($_POST['objectId']) || empty($_POST['youtubeId'])) {
			throw new Exception(UsersModule::t('Your posted data is invalid.'), 400);
		}
		extract($_POST);
		set_time_limit(1000);

		$Node = ZoneInstanceRender::get($objectId);
		$video = $this->save($Node, $youtubeId);

		$data = $video->attributes;
		$data['id'] = IDHelper::uuidFromBinary($video->id, true);
		$data['object_id'] = $objectId;

		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $data), false);
		$this->notify($Node, $video);
	}

	public function getVideoByYoutubeId($youtubeID) {
		$modelVideo = ZoneResourceVideo::model()->findByAttributes(array(
			'owner_id' => currentUser()->id,
			'youtube_id' => $youtubeID,
			'data_status' => ZoneResourceVideo::DATA_STATUS_NORMAL));
		if (empty($modelVideo)) {
			return null;
		}
		$data = $modelVideo->toArray();
		return $data;
	}

	/**
	 * This action is used to get list of videos of an user
	 */
	public function save(ZoneInstance $Node, $youtubeID) {
		if (!@preg_match('/[a-zA-Z0-9\-_]{5}+/', $youtubeID)) {
			throw new Exception(UsersModule::t('The id is invalid.'));
		}
		$binOjectID = IDHelper::uuidToBinary($Node->zone_id);
		if ($this->getVideoByYoutubeId($youtubeID)) {
			throw new Exception(UsersModule::t('The video is exists in your account.', array(
				'{id}' => $youtubeID
			)));
		}

		Yii::import('application.modules.resources.components.YoutubeParser');
		Yii::import('application.modules.landingpage.models.*');

		$url = sprintf('http://gdata.youtube.com/feeds/api/videos/%s?v=2&prettyprint=true&alt=jsonc', $youtubeID);
		list(, $response) = InstanceCrawler::transport($url);
		$response = json_decode($response, true);
		if (is_array($response) && !empty($response['data'])) {
			$ytVideo = $response['data'];
		} else {
			throw new Exception(UsersModule::t('The video {id} is not found.', array(
				'{id}' => $youtubeID
			)));
		}

		$video = new ZoneResourceVideo();
		$video->title = $ytVideo['title'];
		$video->description = CHtml::encode($ytVideo['description']);

		$video->type = ZoneResourceVideo::TYPE_FULL;
		$video->youtube_id = $youtubeID;
		$video->data_status = ZoneResourceVideo::DATA_STATUS_NORMAL;

		$video->length = $ytVideo['duration'];
		$video->views = $ytVideo['viewCount'];
		$video->owner_id = $this->controller->userInfo(null)->id;
		$video->url = "http://www.youtube.com/watch?v=" . $youtubeID;
		$video->created = date("Y-m-d H:i:s");
		$video->object_id = $binOjectID;
		$video->is_converted = ZoneResourceVideo::CONVERTED;
		$video->thumbnail = md5(uniqid()) . '.jpg';
		// save
		if (!$video->save()) {
			throw new Exception(UsersModule::t('The video could not be saved.', array(
				'{id}' => $youtubeID
			)));
		}

		if ($video->thumbnail) {
			$filePath = VideoConvertor::runtimeDir() . $video->thumbnail;
			file_put_contents($filePath, file_get_contents($ytVideo['thumbnail']['hqDefault']));
			$uploadPath = 'upload/videos/' . $Node->zone_id . '/';
			ImageCrawler::pushToS3($filePath, $uploadPath . $video->thumbnail);
			@unlink($filePath);
		}

		$videoID = IDHelper::uuidFromBinary($video->id, true);
		try {
			ZoneSearchVideo::model()->indexSearch($video->id
					, $video->title, strtotime($video->created)
					, $video->views, $video->object_id);
		} catch (Exception $ex) {
			Yii::log($ex->getMessage(), 'error', 'Search: index failure video (id:' . $videoID . ')');
		}

		return $video;
	}

	// ======================================================
	//               Send Notification
	// ======================================================
	public function notify(ZoneInstance $Node, ZoneResourceVideo $video) {
		Yii::import('application.components.notification.JLNotificationWriter');
		Yii::import('application.components.notification.ZoneStickerNotificationDocument');
		Yii::import('application.modules.activities.models.*');

		$videoID = IDHelper::uuidFromBinary($video->id, true);
		$binObjectId = IDHelper::uuidToBinary($Node->zone_id);

		$videoInfo = $video->get($videoID);
		$videoInfo['video']['timeIso'] = date(DATE_ISO8601, strtotime($videoInfo['video']['created']));
		$videoInfo['video']['timeInt'] = strtotime($videoInfo['video']['created']);

		$CurrentUser = $this->controller->userInfo(null);
		$userInfo = $CurrentUser->get($CurrentUser->hexID);
		// 1. If album is posted on own timeline
		$friendIDs = $CurrentUser->friends();
		// Save activity on own timeline
		ZoneVideoActivity::model()->saveActivity($CurrentUser->id
				, $CurrentUser->id, $video->id, ZoneActivity::TYPE_POST);
		if ($Node->isUserNode()) {
			if ($binObjectId == $CurrentUser->id) {
				// TODO: Implement on activites
				/*
				 * Save Activities:
				 * - Activity on own timeline : Above
				 * - Activities on friends' timeline
				 */
				/* - Activities on friend's timeline */
				foreach ($friendIDs as $friendInfo) {
					$binFriendID = IDHelper::uuidToBinary($friendInfo['user_id']);
					ZoneVideoActivity::model()->saveActivity($binFriendID
							, $CurrentUser->id, $video->id, ZoneActivity::TYPE_POST);
				}
				/*
				 * Sidebar notification:
				 * - Notify to friends
				 */
				foreach ($friendIDs as $friendInfo) {
					$data = array(
						'namespace' => 'zone-sticker',
						'data' => array(
							'object_type' => 'Video',
							'type' => 'self-posting',
							'user' => $userInfo,
							'video' => $videoInfo['video'],
							'object' => null //
						),
						'userID' => $friendInfo['user_id']
					);
					JLNotificationWriter::push($data);
					JLNotificationWriter::savePushData($friendInfo['user_id'], $data);
				}
			} else {
				// Save activity on own timeline
				ZoneVideoActivity::model()->saveActivity($binObjectId
						, $CurrentUser->id, $video->id, ZoneActivity::TYPE_POST);
			}
		} else {
			$nodeInfo = $Node->toArray();
			/*
			 * Save Activities:
			 * - Activity on own timeline : Above
			 * - Activities on node' timeline
			 * - Activities on friend's timeline
			 * - Activities on follower's timeline
			 */
			/* - Activities on node' timeline */
			ZoneVideoActivity::model()->saveActivity($binObjectId
					, $CurrentUser->id, $video->id, ZoneActivity::TYPE_POST);
			/* - Activities on friend's timeline */
			foreach ($friendIDs as $friendInfo) {
				$binFriendID = IDHelper::uuidToBinary($friendInfo['user_id']);
				ZoneVideoActivity::model()->saveActivity($binFriendID
						, $CurrentUser->id, $video->id, ZoneActivity::TYPE_POST);
			}
			/* - Activities on follower's timeline */
			Yii::import('application.modules.followings.models.ZoneFollowing');
			$followers = ZoneFollowing::model()->followers($binObjectId);
			foreach ($followers as $follower) {
				$binFollowerID = IDHelper::uuidToBinary($follower['user_id']);
				if ($binFollowerID != $CurrentUser->id) {
					// Save activities
					ZoneVideoActivity::model()->saveActivity($binFollowerID
							, $CurrentUser->id, $video->id, ZoneActivity::TYPE_POST);
					// Send notification to followers
					$data = array(
						'namespace' => 'zone-sticker',
						'data' => array(
							'object_type' => 'Video',
							'type' => 'self-posting',
							'user' => $userInfo,
							'video' => $videoInfo['video'],
							'object' => $nodeInfo //
						),
						'userID' => $follower['user_id']
					);
					JLNotificationWriter::push($data);
					JLNotificationWriter::savePushData($follower['user_id'], $data);
				}
			}
		}
	}

}