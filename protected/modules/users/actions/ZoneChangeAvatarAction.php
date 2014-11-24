<?php
Yii::import('greennet.modules.gallery.actions.GNUploadGalleryItemAction');
class ZoneChangeAvatarAction extends GNUploadGalleryItemAction {
	public $return = true;
	/**
	 * The main action that handles the file upload request.
	 */
	public function run() {
		$this->uploadPath = 'upload/user-photos/' . currentUser()->hexID . "/";
		$this->uploader = array(
			'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
			'uploadPath'	=> 'upload/user-photos/' . currentUser()->hexID . "/",
		);
		
		$_FILES['ZoneUserAvatar'] = array();
		if (!empty($_FILES['image'])) {
			foreach ($_FILES['image'] as $key => $item) {
				$_FILES['ZoneUserAvatar'][$key]['image'] = $item;
			}
		}
		
		$return = parent::run();
		
		$fileid = $return['fileid'];
		if (empty($fileid)) {
			$out = array(
				'error'		=> true,
				'message'	=> "Can't upload avatar"
			);
			jsonOut($out);
		}
		
		$model = ZoneUserAvatar::model()->findByPk(IDHelper::uuidToBinary($fileid));
		
		if (empty($model)) {
			$out = array(
				'error'		=> true,
				'message'	=> "Error while saving image information"
			);
			jsonOut($out);
		}
		
		$model->object_id = ZoneUserAvatar::model()->prefix . currentUser()->hexID;
		//$model->score = 500;
		/*Get photo have score max*/
		$photoMaxScore = $model->getMaxScore($model->object_id);
		$maxScore = 0;
		if(!empty($photoMaxScore))
			$maxScore = $photoMaxScore->score;
		$model->score = $maxScore + 1;

		$profile = currentUser()->profile;
		$profile->image = $return['name'];

		if($model->save() && $profile->save()) {
			// Move to S3
			$out = array(
				'error'		=> false,
				'message'	=> "Your avatar has been changed successful",
				'data'		=> array(
					'image'	=> $profile->image,
					'count'	=> ZoneUser::model()->countPhotos(currentUser()->id),
					'photo'	=> ZoneUserAvatar::model()->get(IDHelper::uuidFromBinary($model->id)) /*VuNDH add code*/
				)
			);
			jsonOut($out, false);

			// Save activity
			Yii::import('application.modules.activities.models.*');
			ZoneImageActivity::model()->pushActivities(currentUser()->hexID, $photo['photo']['id'], array(
				'owner'			=> false,
				'friends'		=> array(currentUser()->hexID)
			), ZoneActivity::TYPE_POST);
			
			$config	= array(
				'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
				'uploadPath'	=> 'upload/user-photos/' . currentUser()->hexID,
				'storageEngines'	=> array(
					's3'	=> array(
						'class'			=> 'greennet.components.GNUploader.components.engines.s3.GNS3Engine',
						'serverInfo'	=> array(
							'accessKey'	=> Yii::app()->params['AWS']['S3']['upload']['accessKey'],
							'secretKey'	=> Yii::app()->params['AWS']['S3']['upload']['secretKey'],
							'bucket'	=> 'static.youlook.net'
						)
					)
				)
			);
			
			$webroot = Yii::getPathOfAlias('jlwebroot');
			$filePath = "{$webroot}/upload/user-photos/" . currentUser()->hexID . "/{$return['name']}";
			$s3Uploader = Yii::createComponent($config);
			$s3Uploader->store($filePath, array('s3path' => "upload/user-photos/" . currentUser()->hexID));
			
			// Send notification
			Yii::import('application.components.notification.JLNotificationWriter');
			$currentUser = currentUser();
			$currentUser->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
			$friends = $currentUser->friends('', '', 1000, 0);
			$currentUser->detachBehavior('UserFriend');
			if(!empty($friends)){
				foreach($friends as $key=>$friend){
					if(currentUser()->hexID != $friend['user_id']){
						$user_id = $friend['user_id'];
							
						$data = array(
							'notifier_id'	=> currentUser()->hexID,
							'receive_id'=>$user_id,
							'filename'=>$model->image,
							'object_id'	=> IDHelper::uuidFromBinary($model->id,true),
							'type'			=> 'changeAvatar'
						);
				
						JLNotificationWriter::send(
						$user_id,
						"application.components.notification.renderer.ZoneChangeAvatarNotification",
						$data
						);
					}
				}
			}
			
		} else {
			$errors  = $model->getErrors();
			list ($field, $_errors) = each ($errors);
			if ($this->isJsonRequest) {
				jsonOut(array(
					'error'			=> true,
					'type'			=> 'error',
					'message'		=> $_errors[0],
				));
			} else {
				Yii::log($_errors[0], CLogger::LEVEL_ERROR, "Save resource image");
				//throw new Exception($_errors[0]);
			}
		}
	}
}