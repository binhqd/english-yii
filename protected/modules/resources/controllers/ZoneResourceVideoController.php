<?php

/**
 * ZoneResourceVideoController
 *
 * @author Chu Tieu
 * @version 1.0
 */
class ZoneResourceVideoController extends ZoneController {

	private $_uploadPath	= 'upload/videos/';
	public $layout = "//layouts/master/myzone_v2";
	/**
	 * This method is used to allow action
	 * @return string
	 */

	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions() {
		return '*';
	}

	public function actionUploadVideo() {
		if(currentUser()->id==-1){
			ajaxOut(array(
				'error' => true,
				'message' => 'Please login.'
			));
		}
		
		set_time_limit(600);
		if (!currentUser()->isGuest) {
			$model = new ZoneResourceVideo();
			
			if (isset($_POST['ZoneResourceVideo'])) {
				$model->attributes = $_POST['ZoneResourceVideo'];
				
				$model->owner_id = currentUser()->id;
				$model->data_status = ZoneResourceVideo::DATA_STATUS_DELETED;
				$model->created = date('Y-m-d h-i-s');
				
				$objectID = @$_POST['ZoneResourceVideo']['object_id'];

				$model->object_id = IDHelper::uuidToBinary($objectID);
				
				$basePath = $this->_uploadPath . $objectID . '/';
				$uploadPath = dirname(Yii::app()->request->scriptFile) . '/' . $basePath;
				if (!file_exists($uploadPath)) {
					@mkdir($uploadPath, 0755, true);
				}

				$fileid = md5(uniqid());
				$ext = pathinfo($_FILES['ZoneResourceVideo']['name']['video'], PATHINFO_EXTENSION);
				$filepath = VideoConvertor::runtimeDir() . $fileid . '.' . $ext;
				copy($_FILES['ZoneResourceVideo']['tmp_name']['video'], $filepath);
				
				if (file_exists($filepath)) {
					list($snapAt, $duration) = VideoConvertor::getSnapAt($filepath);
					$thumbPath = VideoConvertor::getThumbnail($filepath, $snapAt);
					ImageCrawler::pushToS3($thumbPath, $basePath . $fileid . '.jpg' );
					copy($thumbPath, $uploadPath . $fileid . '.jpg');
					@unlink($thumbPath);

					$model->video = $fileid . '.' . $ext;
					$model->length = $duration;
					$model->thumbnail = $fileid . '.jpg';

					$model->save();
				} else {
					ajaxOut(array(
						'error' => true,
						'message' => 'Could not saved file.'
					));
				}

				$attr = $model->toArray();
				ajaxOut($attr, false);
				@unlink($_FILES['ZoneResourceVideo']['tmp_name']['video']);
				exit();
			}
		} else {
			ajaxOut(array(
				'error' => true,
				'message' => 'please login.'
			));
		}
	}

	public function actionSaveVideo() {
		if(currentUser()->id==-1){
			ajaxOut(array(
				'error' => true,
				'message' => 'Please login.'
			));
		}
		
		set_time_limit(600);
		if (!empty($_POST['ZoneResourceVideo'])) {
			$data = $_POST['ZoneResourceVideo'];

			$binVideoID = IDHelper::uuidToBinary(@$data['id']);
			$video = ZoneResourceVideo::model()->findByPk($binVideoID);
			if (empty($video)) {
				$out = array(
					'error' => true,
					'message' => "Invalid video ID"
				);
				ajaxOut($out);
			}

			$video->title = $data['title'];
			$video->description = $data['description'];
			if(!empty($data['type'])){
				$video->type = $data['type'];
			} else{
				$video->type = ZoneResourceVideo::TYPE_FULL;
			}
			$video->data_status = ZoneResourceVideo::DATA_STATUS_NORMAL;

			if ($video->save()) {
				$out = array(
					'error' => false,
					'message' => "Video uploaded has been saved successful",
					'result' => array(
						'videos' => array(
							// chu tieu 
							$video->get(IDHelper::uuidFromBinary($video->id, true))
						)
					)
				);
				$out['result']['videos']['timeIso'] = date(DATE_ISO8601, strtotime($out['result']['videos']['created']));
				$out['result']['videos']['timeInt'] = strtotime($out['result']['videos']['created']);
				
				ajaxOut($out, false);

				/**
				 * index video for search (landing page)
				 * @author huytbt
				 */
				Yii::import('application.modules.landingpage.models.*');
				try {
					$index = ZoneSearchVideo::model()->indexSearch($video->id, $video->title, strtotime($video->created), $video->views, $video->object_id);
				} catch (Exception $ex) {
					Yii::log($ex->getMessage(), 'error', 'Search: index failure video (id:'.IDHelper::uuidFromBinary($video->id,true).')');
				}
				/* end (index) */
				$fileid = pathinfo($video->video, PATHINFO_FILENAME);
				$uploadPath = dirname(Yii::app()->request->scriptFile) . '/'
						. $this->_uploadPath . IDHelper::uuidFromBinary($video->object_id , true) . '/';

				$filepath = VideoConvertor::runtimeDir() . $video->video;
				VideoConvertor::process($filepath, $uploadPath . $fileid . '.flv');
				@unlink($filepath);
				
				$video->video = $fileid . '.flv';
				$video->is_converted = true;
				
				$video->save();
				
				
			} else {
				$out = array(
					'error' => true,
					'message' => "Can't save video link"
				);
			}
		}
	}
	
	/**
	 * @author: Chu Tieu
	 */
	public function actionPostVideosYoutube() {
		if(currentUser()->id==-1){
			ajaxOut(array(
				'error' => true,
				'message' => 'Please login.'
			));
		}
		
		if (!empty($_POST['ZoneResourceVideo'])) {
		
			$datas = $_POST['ZoneResourceVideo'];
			$videoInfos = array();
			
			if(!empty($datas['youtubeIds'])) {
				$activities = array();
				
				foreach($datas['youtubeIds'] as $youtubeId){
					if(!empty($youtubeId)){
						Yii::import('application.modules.resources.components.YoutubeParser');
						$parser = new YoutubeParser();
						
						$parse = $parser->parse("http://www.youtube.com/watch?v=".$youtubeId);
						
						$video = new ZoneResourceVideo();
						$video->title = $parse['title'];
						$video->description = CHtml::encode($parse['description']);
						
						
						$video->type = ZoneResourceVideo::TYPE_FULL;
						$video->youtube_id = $youtubeId;
						$video->data_status = ZoneResourceVideo::DATA_STATUS_NORMAL;

						$video->length = $parse['media']['length'];
						$video->owner_id = currentUser()->id;
						$video->url = "http://www.youtube.com/watch?v=" . $youtubeId;
						$video->created = date("Y-m-d H:i:s");
						$video->object_id = IDHelper::uuidToBinary($datas['object_id']);
						$video->is_converted = ZoneResourceVideo::CONVERTED;

						$thumbnail = '';
						if (count($parse['thumbnails'])) {
							$fileName = md5(uniqid()) . '.jpg';
							$filePath = VideoConvertor::runtimeDir() . $fileName;
							$thumbnail = current($parse['thumbnails']);
							file_put_contents($filePath, file_get_contents($thumbnail['url']));

							$uploadPath = $this->_uploadPath . $datas['object_id'] . '/';
							ImageCrawler::pushToS3($filePath, $uploadPath . $fileName);
							@unlink($filePath);
							$video->thumbnail = $fileName;
						}

						// get thumbnail

						if ($video->save()) {
							$videoInfo = $video->get(IDHelper::uuidFromBinary($video->id, true));
							
							$videoInfo['video']['timeIso'] = date(DATE_ISO8601, strtotime($videoInfo['video']['created']));
							$videoInfo['video']['timeInt'] = strtotime($videoInfo['video']['created']);
							$videoInfo = $videoInfo['video'];
							
							$videoInfos[] = $videoInfo;
							
							/**
							 * index video for search (landing page)
							 * @author huytbt
							 */
							Yii::import('application.modules.landingpage.models.*');
							try {
								$index = ZoneSearchVideo::model()->indexSearch($video->id, $video->title, strtotime($video->created), $video->views, $video->object_id);
							} catch (Exception $ex) {
								Yii::log($ex->getMessage(), 'error', 'Search: index failure video (id:'.IDHelper::uuidFromBinary($video->id,true).')');
							}
							/* end (index) */
							// ======================================================
							//               Send Notification
							// ======================================================
							Yii::import('application.components.notification.JLNotificationWriter');
							Yii::import('application.components.notification.ZoneStickerNotificationDocument');
							
							$binVideoID = IDHelper::uuidToBinary($videoInfo['id']);
							
							// Save activity on own timeline
							$attrActivity = ZoneVideoActivity::model()->saveActivity(currentUser()->id, currentUser()->id, $binVideoID, ZoneActivity::TYPE_POST);
							
							$activities[] = ZoneVideoActivity::model()->get(IDHelper::uuidFromBinary($attrActivity['id'], true));
						} else {
							$out = array(
								'error' => true,
								'message' => "Can't save video link"
							);
							ajaxOut($out);
						}
					}
				}
				
				// Xu ly activity
				
				$out = array(
					'error'		=> false,
					'message'	=> 'Videos has been saved successful.',
					'videos'	=> $videoInfos,
					'activities'	=> $activities
				);
				ajaxOut($out, false);
				
				if(!empty($videoInfos)){
					Yii::import('application.components.notification.JLNotificationWriter');
					Yii::import('application.components.notification.ZoneStickerNotificationDocument');
					
					$isUser = ZoneUser::model()->isUser(IDHelper::uuidToBinary($datas['object_id']));
					
					$currentUser = currentUser();
					$currentUserInfo = $currentUser->get($currentUser->hexID);
					
					$currentUser->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
					$friendIDs = $currentUser->friends();
					
					$strReceiverID = $datas['object_id'];
					foreach($videoInfos as $videoInfo) {
						// ======================================================
						//               Send Notification
						// ======================================================
						$binVideoID = IDHelper::uuidToBinary($videoInfo['id']);
						
						// 1. If album is posted on own timeline
						if ($isUser) {
							if ($datas['object_id'] == $currentUser->hexID) {
								// TODO: Implement on activites
								/*
								 * Save Activities:
								 * - Activity on own timeline : Above
								 * - Activities on friends' timeline
								 */
								ZoneVideoActivity::model()->pushActivities($currentUser->hexID, $videoInfo['id'], array(
									'owner'			=> false,
									'friends'		=> array($currentUser->hexID),
								), ZoneActivity::TYPE_POST);
								/*
								 * Sidebar notification:
								 * - Notify to friends
								 */
// 								foreach ($friendIDs as $friendInfo) {
// 									$data = array(
// 										'namespace'		=> 'zone-sticker',
// 										'data'			=> array(
// 											'object_type'	=> 'Video',
// 											'type'		=> 'self-posting',
// 											'user'		=> $currentUserInfo,
// 											'video'		=> $videoInfo,
// 											'object'	=> null //
// 										),
// 										'userID'		=> $friendInfo['user_id']
// 									);
									
// 									JLNotificationWriter::push($data);
// 									JLNotificationWriter::savePushData($friendInfo['user_id'], $data);
// 								}
							} else {
								// Save activity on own timeline
								ZoneVideoActivity::model()->pushActivities($currentUser->hexID, $videoInfo['id'], array(
									'owner'			=> false,
									'particulars'	=> array($strReceiverID),
									'friends'		=> array($strReceiverID),
								), ZoneActivity::TYPE_POST);
							}
						} else {
							$strNodeID = $datas['object_id'];
							$binNodeID = IDHelper::uuidToBinary($strNodeID);

							$node = ZoneInstanceRender::get($strNodeID);
							$nodeInfo = $node->toArray();
							/*
							 * Save Activities:
							* - Activity on own timeline : Above
							* - Activities on node' timeline
							* - Activities on friend's timeline
							* - Activities on follower's timeline
							*/
							/* - Activities on node' timeline */
							ZoneVideoActivity::model()->pushActivities($currentUser->hexID, $videoInfo['id'], array(
								'owner'			=> false,
								'friends'		=> array($currentUser->hexID),
								'particulars'	=> array($strReceiverID),
								'followers'		=> array($strReceiverID),
								'categories'	=> array($strReceiverID)
							), ZoneActivity::TYPE_POST);
							
							/* - Activities on friend's timeline */
// 							foreach ($friendIDs as $friendInfo) {
// 								$binFriendID = IDHelper::uuidToBinary($friendInfo['user_id']);
								
// 								$data = array(
// 									'namespace'		=> 'zone-sticker',
// 									'data'			=> array(
// 										'object_type'	=> 'Video',
// 										'type'		=> 'self-posting',
// 										'user'		=> $currentUserInfo,
// 										'video'		=> $videoInfo,
// 										'object'	=> null //
// 									),
// 									'userID'		=> $friendInfo['user_id']
// 								);
								
// 								JLNotificationWriter::push($data);
// 								JLNotificationWriter::savePushData($friendInfo['user_id'], $data);
// 							}

							/* - Activities on follower's timeline */
							Yii::import('application.modules.followings.models.ZoneFollowing');
							$followers = ZoneFollowing::model()->followers($binNodeID);
							foreach ($followers as $follower) {
								$followerID = $follower['user_id'];
								$binFollowerID = IDHelper::uuidToBinary($followerID);

								if ($binFollowerID != currentUser()->id) {
									// Save activities
									
									// Send notification to followers
									$data = array(
										'namespace'		=> 'zone-sticker',
										'data'			=> array(
											'object_type'	=> 'Video',
											'type'		=> 'self-posting',
											'user'		=> $currentUserInfo,
											'video'		=> $videoInfo,
											'object'	=> $nodeInfo //
										),
										'userID'		=> $followerID
									);
									JLNotificationWriter::push($data);
									JLNotificationWriter::savePushData($followerID, $data);
								}
							}
						}
					}
				}
			} else {
				$out = array(
					'error'		=> true,
					'message'	=> "You must select at least one video!"
				);
				ajaxOut($out, false);
			}
		}
	}
	
	public function actionPostVideo() {
		if(currentUser()->id==-1){
			ajaxOut(array(
				'error' => true,
				'message' => 'Please login.'
			));
		}
		
		$id = Yii::app()->request->getParam('id', null);
		$objectId = Yii::app()->request->getParam('object_id', null);
		$urlYoutube = "http://www.youtube.com/watch?v=" . $id;
		
		Yii::import('application.modules.resources.components.YoutubeParser');
		
		$parser = new YoutubeParser();
		$parse = $parser->parse($urlYoutube);
		
		if (empty($parse)) {
			ajaxOut(array(
				'error' => true,
				'message' => "Invalid url"
			));
		}

		$video = new ZoneResourceVideo();
		
		$video->title = $parse['title'];
		$video->description = CHtml::encode($parse['description']);
		
		$video->type = ZoneResourceVideo::TYPE_FULL;
		
		$video->data_status = ZoneResourceVideo::DATA_STATUS_NORMAL;
		$video->youtube_id = $id;
		$video->length = $parse['media']['length'];
		$video->owner_id = currentUser()->id;
		$video->url = $urlYoutube;
		$video->created = date("Y-m-d H:i:s");
		$video->object_id = IDHelper::uuidToBinary($objectId);
		$video->is_converted = ZoneResourceVideo::CONVERTED;

		$thumbnail = '';
		if (count($parse['thumbnails'])) {
			$fileName = md5(uniqid()) . '.jpg';
			$filePath = VideoConvertor::runtimeDir() . $fileName;
			$thumbnail = current($parse['thumbnails']);
			file_put_contents($filePath, file_get_contents($thumbnail['url']));

			$uploadPath = $this->_uploadPath . $objectId . '/';
			ImageCrawler::pushToS3($filePath, $uploadPath . $fileName);
			@unlink($filePath);
			$video->thumbnail = $fileName;
		}

		// get thumbnail

		if ($video->save()) {
			$videoInfo = $video->get(IDHelper::uuidFromBinary($video->id, true));
			$videoInfo = $videoInfo['video'];
			$out = array(
				'error' => false,
				'message' => "Video link has been saved successful",
				'result' => array(
					'videos' => array(
						$videoInfo
					)
				)
			);
			ajaxOut($out, false);
			
			/**
			 * index video for search (landing page)
			 * @author huytbt
			 */
			Yii::import('application.modules.landingpage.models.*');
			try {
				$index = ZoneSearchVideo::model()->indexSearch($video->id, $video->title, strtotime($video->created), $video->views, $video->object_id);
			} catch (Exception $ex) {
				Yii::log($ex->getMessage(), 'error', 'Search: index failure video (id:'.IDHelper::uuidFromBinary($video->id,true).')');
			}
			/* end (index) */
			
			// ======================================================
			//               Send Notification
			// ======================================================
			Yii::import('application.components.notification.JLNotificationWriter');
			Yii::import('application.components.notification.ZoneStickerNotificationDocument');
			
			$isUser = ZoneUser::model()->isUser(IDHelper::uuidToBinary($objectId));
			
			$binVideoID = $video->id;
			$currentUser = currentUser();
			$currentUserInfo = $currentUser->get($currentUser->hexID);
			// 1. If album is posted on own timeline
			
			$currentUser->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
			$friendIDs = $currentUser->friends();
			
			// Save activity on own timeline
			ZoneVideoActivity::model()->saveActivity(currentUser()->id, currentUser()->id, $binVideoID, ZoneActivity::TYPE_POST);
			
			if ($isUser) {
				if ($objectId == currentUser()->hexID) {
					// TODO: Implement on activites
					/*
					 * Save Activities:
					 * - Activity on own timeline : Above
					 * - Activities on friends' timeline
					 */
					
					/* - Activities on friend's timeline */
					foreach ($friendIDs as $friendInfo) {
						$binFriendID = IDHelper::uuidToBinary($friendInfo['user_id']);
						ZoneVideoActivity::model()->saveActivity($binFriendID, currentUser()->id, $binVideoID, ZoneActivity::TYPE_POST);
					}
					
					/*
					 * Sidebar notification:
					 * - Notify to friends
					 */
					foreach ($friendIDs as $friendInfo) {
						$data = array(
							'namespace'		=> 'zone-sticker',
							'data'			=> array(
								'object_type'	=> 'Video',
								'type'		=> 'self-posting',
								'user'		=> $currentUserInfo,
								'video'		=> $videoInfo,
								'object'	=> null //
							),
							'userID'		=> $friendInfo['user_id']
						);
						
						JLNotificationWriter::push($data);
						JLNotificationWriter::savePushData($friendInfo['user_id'], $data);
					}
				} else {
					// Save activity on own timeline
					ZoneVideoActivity::model()->saveActivity(IDHelper::uuidToBinary($objectId), currentUser()->id, $binVideoID, ZoneActivity::TYPE_POST);
					
				}
			} else {
				$strNodeID = $objectId;
				$binNodeID = IDHelper::uuidToBinary($strNodeID);
				
				$node = ZoneInstanceRender::get($strNodeID);
				$nodeInfo = $node->toArray();
				/*
				 * Save Activities:
				* - Activity on own timeline : Above
				* - Activities on node' timeline
				* - Activities on friend's timeline
				* - Activities on follower's timeline
				*/
				
				/* - Activities on node' timeline */
				ZoneVideoActivity::model()->saveActivity($binNodeID, currentUser()->id, $binVideoID, ZoneActivity::TYPE_POST);
				
				/* - Activities on friend's timeline */
				foreach ($friendIDs as $friendInfo) {
					$binFriendID = IDHelper::uuidToBinary($friendInfo['user_id']);
					ZoneVideoActivity::model()->saveActivity($binFriendID, currentUser()->id, $binVideoID, ZoneActivity::TYPE_POST);
				}
				
				/* - Activities on follower's timeline */
				Yii::import('application.modules.followings.models.ZoneFollowing');
				$followers = ZoneFollowing::model()->followers($binNodeID);
				foreach ($followers as $follower) {
						
					$followerID = $follower['user_id'];
					$binFollowerID = IDHelper::uuidToBinary($followerID);
						
					if ($binFollowerID != currentUser()->id) {
						// Save activities
						$activity = ZoneVideoActivity::model()->saveActivity($binFollowerID, currentUser()->id, $binVideoID, ZoneActivity::TYPE_POST);
						// Send notification to followers
						
						$data = array(
							'namespace'		=> 'zone-sticker',
							'data'			=> array(
								'object_type'	=> 'Video',
								'type'		=> 'self-posting',
								'user'		=> $currentUserInfo,
								'video'		=> $videoInfo,
								'object'	=> $nodeInfo //
							),
							'userID'		=> $followerID
						);
						
						JLNotificationWriter::push($data);
						JLNotificationWriter::savePushData($followerID, $data);
					}
				}
				// Activities on friends' timeline
				// 						foreach ($friendIDs as $friendInfo) {
				// 							ZoneAlbumActivity::model()->saveActivity(IDHelper::uuidToBinary($friendInfo['user_id']), $binUserID, $binObjectID, ZoneActivity::TYPE_POST);
				// 						}
			}
			
		} else {
			$out = array(
				'error' => true,
				'message' => "Can't save video"
			);
			ajaxOut($out);
		}
	}
	public function actionPostVideoLink() {
		if (!empty($_POST['ZoneResourceVideo'])) {
			$data = $_POST['ZoneResourceVideo'];

			Yii::import('application.modules.resources.components.YoutubeParser');
			$parser = new YoutubeParser();

			$parse = $parser->parse($data['url']);

			if (empty($parse)) {
				ajaxOut(array(
					'error' => true,
					'message' => "Invalid url"
				));
			}

			$video = new ZoneResourceVideo();
			$video->title = $data['title'];
			$video->description = CHtml::encode($data['description']);
			
			if(!empty($data['type'])){
				$video->type = $data['type'];
			} else {
				$video->type = ZoneResourceVideo::TYPE_FULL;
			}
			
			$video->data_status = ZoneResourceVideo::DATA_STATUS_NORMAL;

			$video->length = $parse['media']['length'];
			$video->owner_id = currentUser()->id;
			$video->url = $data['url'];
			$video->created = date("Y-m-d H:i:s");
			$video->object_id = IDHelper::uuidToBinary($data['object_id']);
			$video->is_converted = ZoneResourceVideo::CONVERTED;

			$thumbnail = '';
			if (count($parse['thumbnails'])) {
				$fileName = md5(uniqid()) . '.jpg';
				$filePath = VideoConvertor::runtimeDir() . $fileName;
				$thumbnail = current($parse['thumbnails']);
				file_put_contents($filePath, file_get_contents($thumbnail['url']));

				$uploadPath = $this->_uploadPath . $data['object_id'] . '/';
				ImageCrawler::pushToS3($filePath, $uploadPath . $fileName);
				@unlink($filePath);
				$video->thumbnail = $fileName;
			}

			// get thumbnail

			if ($video->save()) {
				$videoInfo = $video->get(IDHelper::uuidFromBinary($video->id, true));
				$videoInfo = $videoInfo['video'];
				$out = array(
					'error' => false,
					'message' => "Video link has been saved successful",
					'result' => array(
						'videos' => array(
							$videoInfo
						)
					)
				);
				ajaxOut($out, false);
				
				/**
				 * index video for search (landing page)
				 * @author huytbt
				 */
				Yii::import('application.modules.landingpage.models.*');
				try {
					$index = ZoneSearchVideo::model()->indexSearch($video->id, $video->title, strtotime($video->created), $video->views, $video->object_id);
				} catch (Exception $ex) {
					Yii::log($ex->getMessage(), 'error', 'Search: index failure video (id:'.IDHelper::uuidFromBinary($video->id,true).')');
				}
				/* end (index) */
				
				// ======================================================
				//               Send Notification
				// ======================================================
				Yii::import('application.components.notification.JLNotificationWriter');
				Yii::import('application.components.notification.ZoneStickerNotificationDocument');
				
				$isUser = ZoneUser::model()->isUser(IDHelper::uuidToBinary($data['object_id']));
				
				$binVideoID = $video->id;
				$strVideoID = IDHelper::uuidFromBinary($binVideoID, true);
				
				$currentUser = currentUser();
				$currentUserInfo = $currentUser->get($currentUser->hexID);
				// 1. If album is posted on own timeline
				
				$currentUser->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
				$friendIDs = $currentUser->friends();
				
				// Save activity on own timeline
				ZoneVideoActivity::model()->saveActivity(currentUser()->id, currentUser()->id, $binVideoID, ZoneActivity::TYPE_POST);
				$strReceiverID = $data['object_id'];
				
				if ($isUser) {
					if ($data['object_id'] == currentUser()->hexID) {
						// TODO: Implement on activites
						/*
						 * Save Activities:
						 * - Activity on own timeline : Above
						 * - Activities on friends' timeline
						 */
						ZoneVideoActivity::model()->pushActivities($currentUser->hexID, $strVideoID, array(
							'owner'			=> true,
							'friends'		=> array($currentUser->hexID),
						), ZoneActivity::TYPE_POST);
						/*
						 * Sidebar notification:
						 * - Notify to friends
						 */
// 						foreach ($friendIDs as $friendInfo) {
// 							$data = array(
// 								'namespace'		=> 'zone-sticker',
// 								'data'			=> array(
// 									'object_type'	=> 'Video',
// 									'type'		=> 'self-posting',
// 									'user'		=> $currentUserInfo,
// 									'video'		=> $videoInfo,
// 									'object'	=> null //
// 								),
// 								'userID'		=> $friendInfo['user_id']
// 							);
							
// 							JLNotificationWriter::push($data);
// 							JLNotificationWriter::savePushData($friendInfo['user_id'], $data);
// 						}
					} else {
						// Save activity on own timeline
						ZoneVideoActivity::model()->pushActivities($currentUser->hexID, $strVideoID, array(
							'owner'			=> true,
							'particulars'	=> array($strReceiverID),
							'friends'		=> array($strReceiverID),
						), ZoneActivity::TYPE_POST);
					}
				} else {
					$strNodeID = $data['object_id'];
					$binNodeID = IDHelper::uuidToBinary($strNodeID);
					
					$node = ZoneInstanceRender::get($strNodeID);
					$nodeInfo = $node->toArray();
					/*
					 * Save Activities:
					* - Activity on own timeline : Above
					* - Activities on node' timeline
					* - Activities on friend's timeline
					* - Activities on follower's timeline
					*/
					ZoneVideoActivity::model()->pushActivities($currentUser->hexID, $strVideoID, array(
						'owner'			=> true,
						'friends'		=> array($currentUser->hexID),
						'particulars'	=> array($strNodeID),
						'followers'		=> array($strNodeID),
						'categories'	=> array($strNodeID)
					), ZoneActivity::TYPE_POST);
					
					/* - Activities on follower's timeline */
					Yii::import('application.modules.followings.models.ZoneFollowing');
					$followers = ZoneFollowing::model()->followers($binNodeID);
					foreach ($followers as $follower) {
							
						$followerID = $follower['user_id'];
						$binFollowerID = IDHelper::uuidToBinary($followerID);
							
						if ($binFollowerID != currentUser()->id) {
							// Save activities
							// Send notification to followers
							
							$data = array(
								'namespace'		=> 'zone-sticker',
								'data'			=> array(
									'object_type'	=> 'Video',
									'type'		=> 'self-posting',
									'user'		=> $currentUserInfo,
									'video'		=> $videoInfo,
									'object'	=> $nodeInfo //
								),
								'userID'		=> $followerID
							);
							
							JLNotificationWriter::push($data);
							JLNotificationWriter::savePushData($followerID, $data);
						}
					}
				}
				
			} else {
				$out = array(
					'error' => true,
					'message' => "Can't save video link"
				);
				ajaxOut($out);
			}
		}
	}

	public function actionFetchUrl() {
		if (isset($_POST['url'])) {
			$url = $_POST['url'];

			if (filter_var($url, FILTER_VALIDATE_URL) === false) {
				$out = array(
					'error' => true,
					'message' => "Invalid link"
				);
				ajaxOut($out);
			} else {
				Yii::import('application.modules.resources.components.YoutubeParser');
				$parser = new YoutubeParser();

				$out = $parser->parse($url);
				if($out){
					$out = array(
						'error' => false,
						'result' => $out,
						'message' => "Fetch URL successful"
					);
				} else {
					$out = array(
						'error' => true,
						'message' => "Invalid link"
					);
				}
				ajaxOut($out);
				//$video->type = ZoneResourceVideo::TYPE_OTHER;
			}
		} else {
			$out = array(
				'error' => true,
				'message' => "Invalid Request"
			);
			ajaxOut($out);
		}
	}
	/**
	 * Search youtube
	 * @author: Chu Tieu
	 */
	public function actionSearch($keyword=null){
		if ($keyword) {
			$keyword = trim($keyword);
		}
		$q = urlencode($keyword);
		$q = str_replace('+', ' ', $q);
		
		$view = "application.modules.resources.views.videos.search-youtube";
		
		$this->render($view, array(
			'keyword'	=> $keyword,
			'q'			=> $q
		));
	}
	public function actionDetail() {

		$this->layout = '//../themes/movie/views/layouts/video-detail';
		$id		= Yii::app()->request->getParam('id', null);
		$yid		= Yii::app()->request->getParam('yid', null);
		
		if(!empty($id)){
			// get video info
			$video = ZoneResourceVideo::model()->get($id);
			$videoDetail = ZoneResourceVideo::model()->findByPk(IDHelper::uuidToBinary($id));
			
			if(empty($video) || empty($videoDetail) ){
				throw new CHttpException(Yii::t('yii', "Sorry, this page isn't available"));
			}
			
			$video['video']['timeIso'] = date(DATE_ISO8601, strtotime($video['video']['created']));
			$video['video']['timeInt'] = strtotime($video['video']['created']);
			$this->pageTitle = "Youlook - Video: {$video['video']['title']}";
			
			// check is user profile
			if(!empty($videoDetail->VideoPoster->username)){
				if(!empty($user) && $user!=$videoDetail->VideoPoster->username){
					throw new CHttpException(Yii::t('yii', "Sorry, this page isn't available"));
				}
			} else {
				$videoDetail->delete();
			}
			// if video is youtube
			$isYoutube = true;
			if (empty($video['video']['url'])) {
				$isYoutube = false;
			}
			
			// check is not converted.
			if($videoDetail->is_converted && $videoDetail->data_status != ZoneResourceVideo::DATA_STATUS_DELETED){
				$views = (int)$videoDetail->views+1;
				$videoDetail->views = $views;
				$videoDetail->save();
			}
			
			$node = ZoneInstanceRender::get($video['video']['object_id']);
			if ($node->hasType('/film/film')) {
				$nodeType = 'movie';
			} else if ($node->hasType('/people/person')) {
				$nodeType = 'person';
			} else {
				$nodeType = 'node';
			}

			$owner = ZoneNodeRender::owner($node['zone_id']);
			
			$node = CJSON::encode($node);
			$node = CJSON::decode($node, true);
			
			
			
			/**
			 * set information for SEO
			 */
			$metadata = array(
				'title'			=> "Youlook - ",
				'description'	=> '',
				'image'			=> ''
			);
			
			/**
			 * Preload data for putting in page metadata
			*/
			
			
			$metadata['title'] = empty($video['video']['title']) ? 'Youlook' : "Youlook - {$video['video']['title']}";
			$metadata['description'] = JLStringHelper::word_limiter(preg_replace("/<br\W*?\/>/", "\n", $video['video']['description']), 160);
			
			Yii::app()->clientScript->registerMetaTag($metadata['title'], 'title');
			Yii::app()->clientScript->registerMetaTag($metadata['description'], 'description');
			
			Yii::app()->clientScript->registerMetaTag($metadata['title'], NULL, NULL, array('property'=> 'og:title'));
			Yii::app()->clientScript->registerMetaTag(ZoneRouter::createAbsoluteUrl("/upload/videos/fill/241-137/{$video['video']['thumbnail']}?album_id={$video['video']['object_id']}"), NULL, NULL, array('property'=> 'og:image'));
			Yii::app()->clientScript->registerMetaTag($metadata['description'], NULL, NULL, array('property'=> 'og:description'));
			
			$this->pageTitle = $metadata['title'];
			
			
// 			br2nl
			// $viewDetail = 'application.modules.resources.views.videos.video-detail';
			// if(!empty($node['isUserNode']))
			$viewDetail = 'application.themes.movie.views.default.video-detail';
			
			$this->render($viewDetail, compact('video', 'isYoutube', 'node', 'owner', 'nodeType'));
			
		} else if(!empty($yid)){
			
			$startIndex = Yii::app()->request->getParam('start-index', 1);
			$maxResults = Yii::app()->request->getParam('max-results', 10);
			
			$modelVideo = ZoneResourceVideo::model()->findByAttributes(array('owner_id'=>currentUser()->id,'youtube_id'=>$yid, 'data_status'=>ZoneResourceVideo::DATA_STATUS_NORMAL));
			if(!empty($modelVideo)){
				$this->redirect("/video/detail?id=" . IDHelper::uuidFromBinary($modelVideo->id, true));
			}
			
			$url = "https://gdata.youtube.com/feeds/api/videos/".$yid."?v=2&alt=jsonc";
			$videoDetail = @file_get_contents($url);
			if(!$videoDetail){
				throw new CHttpException(Yii::t('Youlook', "Sorry, this page isn't available"));
			}
			$videoDetail = json_decode($videoDetail,true);
			$view = 'application.themes.movie.views.default.youtube-detail';
			$this->render($view, array(
				'videoDetail'	=> $videoDetail
			));
		} else {
			throw new CHttpException(404, Yii::t('Youlook', "Sorry, this page isn't available"));
		}
	}
	
	// ==========
	public function actionTestUpload() {
		$this->render('application.modules.resources.views.videos.test-upload');
	}
	
	/**
	 * This method is used to hide video's ID
	 * @param String $id
	 */
	public function actionHideVideo($binID=null) {
		if(currentUser()->id==-1){
			ajaxOut(array(
				'error' => true,
				'message' => 'Please login.'
			));
		}
		if (!empty($binID)) {

			$modelVideo = ZoneResourceVideo::model()->hideById($binID);
			
			if ($modelVideo) {
				if(Yii::app()->request->isAjaxRequest){
					ajaxOut(array(
						'error' => false,
						'message' => 'This object has been deleted.'
					));
				}
			} else
				if(Yii::app()->request->isAjaxRequest){
					ajaxOut(array(
						'error' => true,
						'message' => 'This object has not been deleted.'
					));
				}
		}
	}
	
	/**
	 * This method is used to restore video's ID
	 * @param String $id
	 */
	public function actionRestoreVideo($binID=null) {
		if(currentUser()->id==-1){
			ajaxOut(array(
				'error' => true,
				'message' => 'Please login.'
			));
		}
		if (!empty($binID)) {
			$modelVideo = ZoneResourceVideo::model()->restoreById($binID);
				
			if ($modelVideo) {
				if(Yii::app()->request->isAjaxRequest){
					ajaxOut(array(
						'error' => false,
						'message' => 'This object has been restore.'
					));
				}
			} else
				if(Yii::app()->request->isAjaxRequest){
					ajaxOut(array(
					'error' => true,
					'message' => 'This object has not been restore.'
				));
			}
		}
	}
	
	/**
	 * This method is used to hide video's ID
	 * @param String $id
	 */
	public function actionHideByCondition($ownerId=null, $formDate=null, $toDate=null) {
		if(currentUser()->id==-1){
			ajaxOut(array(
				'error' => true,
				'message' => 'Please login.'
			));
		}
		if (!empty($ownerId) && !empty($formDate)) {
			
			$modelVideo = ZoneResourceVideo::model()->deleteByCondition($ownerId, $formDate, $toDate);
			
			if ($modelVideo) {
				ajaxOut(array(
					'error' => false,
					'message' => 'This object has not been deleted.'
				));
			}
			else
				ajaxOut(array(
					'error' => true,
					'message' => 'This object has been deleted.'
				));
		}
	}
	public function actionHideVideosByObject($objectID = null){
		//ZoneResourceVideo::model()->hideByObjectID($objectID);
	}
}