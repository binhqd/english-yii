<?php
Yii::import('greennet.modules.registration.interfaces.IOAuthRegistrationController');
class GNFacebookController extends GNController implements IOAuthRegistrationController {
	public $mailViewPath = 'greennet.modules.social.views.mail';
	protected $_connector;
	
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}
	
	/**
	 * This method is used to initialize Facebook connector
	 *
	 * @see GNController::init()
	 */
	public function init() {
		Yii::app()->session->open();
		$config = array(
			'class' => 'GNFacebookConnector'
		);
		
		$config = CMap::mergeArray($config, Yii::app()->params['OAuth']['Facebook']);
	
		$this->_connector = Yii::createComponent($config);
		$this->_connector->init();
	}
	
	public function actionIndex() {
		if (!$this->_connector->isConnected) {
			$params = array(
				'redirect_uri'	=> GNRouter::createAbsoluteUrl('/facebook/connect'),
				'scope'	=> 'email,user_birthday'
			);
			$facebookAuthUrl	= $this->_connector->getAuthUrl($params);
			
			$this->redirect($facebookAuthUrl);
		} else {
			$userInfo = $this->_connector->userInfo;
			if (!isset($userInfo['email']) || empty($userInfo['email'])) {
				// Set flash
				Yii::app()->user->setFlash('error', Yii::t("greennet", "We couldn't get your email address from Facebook response. Make sure your email address in your Facebook account is valid or being verified by Facebook."));
				
				// redirect to registration page
				$this->redirect('/users/registration');
				Yii::app()->end();
			}
			$user = GNUser::model()->findByEmail($userInfo['email']);
			if (!empty($user)) {
				// Force user to login
				$user->forceLogin();
				
				// Set success flash
				$msg = Yii::t("greennet", "You are now successful login");
				Yii::app()->jlbd->dialog->notify(array(
					'type'		=>	'success',
					'autoHide'	=>	true,
					'message'	=>	$msg,
				));
				$this->redirect('/profile');
			} else {
				throw new Exception(sprintf(Yii::t("greennet", "This email (%s) doesn't exist in our database"), $userInfo['email']));
			}
			
		}
	}
	
	/**
	 * This action is used to handle event after successful login with Facebook
	 */
	public function actionConnect() {
		watch($this->_connector->isConnected);
		if (!isset($_GET['code'])) {
			if (!$this->isJsonRequest) {
				Yii::app()->jlbd->dialog->notify(array(
					'error'	=> true,
					'type' => 'error',
					'autoHide' => true,
					'message' => Yii::t("greennet", 'Could not login with Facebook. Because parameter is invalid.'),
				));
				$this->redirect('/');
			} else {
				ajaxOut(array(
					'error'		=> true,
					'message'	=> Yii::t("greennet", 'Could not login with Facebook. Because parameter is invalid.'),
				));
			}
			
			exit;
		}
		
		if ($this->_connector->isConnected) {
			// get user info
			$userInfo = $this->_connector->userInfo;
			// TODO: skip if this facebook has been mapped
			
			if (!isset($userInfo['email']) || empty($userInfo['email'])) {
				// Set flash
				Yii::app()->user->setFlash('error', "We couldn't get your email address from Facebook response. Make sure your email address in your Facebook account is valid or being verified by Facebook.");
				// redirect to registration page
				$this->redirect('/users/registration');
				Yii::app()->end();
			}
			
			// Check if user with this email has registered or not
			$user = GNUser::model()->findByEmail($userInfo['email']);
			
			// If user with that email not exist, create new user
			if (empty($user)) {
				$password = uniqid();
				$strSalt = GNUser::createSalt();
				
				Yii::import('greennet.helpers.Sluggable');
				
				// create new user
				$firstname = Sluggable::convertToLatin($userInfo['first_name']);
				$lastname = Sluggable::convertToLatin($userInfo['last_name']);

				$arrInfo = array(
					'firstname'	=> $firstname,
					'lastname'	=> $lastname,
					'email'		=> $userInfo['email'],
					'created'	=> time(),
					'saltkey'	=> $strSalt,
					'password'	=> GNUser::encryptPassword($password, $strSalt),
					'displayname'=> GNUser::createDisplayName($firstname, $lastname),
					'username'	=> GNUser::createUsername($firstname, $lastname, isset($userInfo['email']) ? $userInfo['email'] : null),
				);

				// Create user
				$user = GNUser::model()->createUser($arrInfo);

				$arrProfileInfo = array();
				// Get avatar
				if (isset($userInfo['picture']['data']['url']))
				// HuyTBT removed for hot fix
				try {
					if (isset($userInfo['picture']['data']['url'])) {
						$downloadAvatar = $this->_downloadAvatar($userInfo['picture']['data']['url'], $user->hexID);
					}
				} catch (Exception $ex) {}
				if (isset($downloadAvatar) && !empty($downloadAvatar))
					$arrProfileInfo['image'] = $downloadAvatar;

				// Create user profile
				$modelProfile = new GNUserProfile;
				$createProfile = $modelProfile->createProfile($user->id, $arrProfileInfo);
				if (!$createProfile) throw new Exception('Cannot create profile');
				if (isset($userInfo['birthday'])) {
					$date = explode('/', $userInfo['birthday']);
					if (isset($date[2])) {
						$createProfile->birth = "$date[2]-$date[0]-$date[1]";
						$createProfile->save();
					}
				}

				// Assign Permissions
				Rights::assign(Yii::app()->params['roles']['MEMBER'],$user->id);

				// Send mail to user
				Yii::app()->mail->viewPath = $this->mailViewPath;
				$sendMail = Yii::app()->mail->sendMailWithTemplate($user->email, Yii::t("greennet", 'GreenNet membership created'), 'sendMailUserSocialCreated', $data=array('user'=>$user, 'password'=>$password, 'socialName'=>'Facebook'));
			}
			
			// map user with facebook account
			$user->facebook->saveConnectionData($userInfo['id'], $userInfo);
			
			// print out
			$out = array(
				'error'		=> false,
				'message'	=> 'You are now connected with Facebook'
			);
			// if not json request, perform login
			if (!$this->isJsonRequest) {
				$user->forceLogin();

				Yii::app()->jlbd->dialog->notify(array(
					'error'	=> false,
					'type' => 'success',
					'autoHide' => true,
					'message' => Yii::t("greennet", 'Login successful.'),
				));
				$this->redirect('/landingpage');
				//$this->renderPartial('success', compact('out'));
				
				exit;
			} else {
				ajaxOut($out);
			}
			
		} else {
			if (!$this->isJsonRequest) {
				Yii::app()->jlbd->dialog->notify(array(
					'error'	=> true,
					'type' => 'error',
					'autoHide' => true,
					'message' => Yii::t("greennet", 'Could not login with Facebook.'),
				));
				$this->redirect('/');
			} else {
				ajaxOut(array(
					'error'		=> true,
					'message'	=> Yii::t("greennet", 'Could not login with Facebook.'),
				));
			}
			//$this->renderPartial('failed', compact('out'));
			exit;
		}
	}
	
	public function actionRevoke() {
		$this->_connector->revoke();
		
		// TODO: Remove linked data too
		currentUser()->facebook->removeLinkedData();
// 		ajaxOut(array(
// 			'error'		=> false,
// 			'message'	=> 'Facebook is diconnected.'
// 		));
		$this->redirect('/facebook');
	}
	
	public function actionCheckConnection() {
		echo $this->_connector->isConnected ? 1 : 0;
	}
	
	public function actionCheckPhotoPermission() {
		$config = array(
			'class' => 'GNFacebookProfilePhotoConnector'
		);
		
		$config = CMap::mergeArray($config, Yii::app()->params['OAuth']['Facebook']);
		
		$profilePhoto = Yii::createComponent($config);
		$profilePhoto->init();
		
		$params = array(
			'redirect_uri'	=> GNRouter::createAbsoluteUrl('/facebook/syncPhotos'),
			'scope'	=> 'user_photos'
		);
		$facebookAuthUrl	= $profilePhoto->getAuthUrl($params);
		$this->redirect($facebookAuthUrl);
		
	}
	public function actionSyncPhotos() {
		set_time_limit(0);
		Yii::import('greennet.modules.social.models.*');
		$config = array(
			'class' => 'GNFacebookProfilePhotoConnector'
		);
		
		$config = CMap::mergeArray($config, Yii::app()->params['OAuth']['Facebook']);
		
		$profilePhoto = Yii::createComponent($config);
		$profilePhoto->init();

		if (!$profilePhoto->isConnected) {
			$params = array(
				'redirect_uri'	=> GNRouter::createAbsoluteUrl('/facebook/checkPhotoPermission'),
				'scope'	=> 'user_photos'
			);
			$facebookAuthUrl	= $profilePhoto->getAuthUrl($params);
				
			$this->redirect($facebookAuthUrl);
		} else {
			$offset = GNSyncFacebookPhoto::model()->count('user_id=:user_id', array(':user_id'=>currentUser()->id));
			$photos = $profilePhoto->getPhotos(0, 1000);

			foreach ($photos as $photo) {
				$source = $photo['source'];
				$id = $photo['id'];
				if (GNSyncFacebookPhoto::model()->count('fb_id=:fb_id and user_id=:user_id', array(
					':fb_id'	=> $id,
					':user_id'	=> currentUser()->id
				))) {
					continue;
				}
				
				$model = new GNSyncFacebookPhoto();
				$model->fb_id = $id;
				$model->source = $source;
				$model->user_id = currentUser()->id;
				$model->created = date("Y-m-d H:i:s");
				
				$model->save();
				//header("Content-type: image/jpg");
				//$content = file_get_contents($source);
				//exit;
			}
			
			$total = GNSyncFacebookPhoto::model()->count('user_id=:user_id', array(
				':user_id'	=> currentUser()->id
			));
// 			dump(currentUser()->id);
			$done = GNSyncFacebookPhoto::model()->count('user_id=:user_id AND done=1', array(
				':user_id'	=> currentUser()->id
			));

			$criteria = new CDbCriteria();
			$criteria->condition = 'user_id=:user_id AND done=0';
			$criteria->params = array(
				':user_id'	=> currentUser()->id
			);
			$criteria->limit = 20;
			$records = GNSyncFacebookPhoto::model()->findAll($criteria);

			$user = GNCoreUser::model()->findByID(currentUser()->id);
			if (!empty($user->profile) && isset($user->profile->tableSchema->columns['lastsyncfbphotos'])) {
				$user->profile->lastsyncfbphotos = time();
				$user->profile->save();
			}

			if ($this->isJsonRequest) {
				$source = array();
				foreach ($records as $item)
					$source[] = array(
						'id'		=> IDHelper::uuidFromBinary($item->id, true),
						'fb_id'		=> $item->id,
						'source'	=> $item->source,
					);
				ajaxOut(array(
					'total'		=> $total,
					'done'		=> $done,
					'source'	=> $source,
				));
			}

			$this->render('greennet.modules.social.views.facebook.sync-photo', compact('records', 'total', 'done'));
		}
	}
	
	private function _downloadAvatar($strUrlImage, $strUserId)
	{
		// get ext image
		$arr = explode('.', $strUrlImage);
		$ext = $arr[count($arr) - 1];
		$strPathSave = "upload/user-photos/{$strUserId}/{$strUserId}.{$ext}";
		try {
			//Get the file
			$content = file_get_contents($strUrlImage);
			//Store in the filesystem.
			@mkdir(Yii::getPathOfAlias('jlwebroot') . '/' . "upload/user-photos/{$strUserId}", 0755, true);
			$fp = fopen(Yii::getPathOfAlias('jlwebroot') . '/' . $strPathSave, "w");
			fwrite($fp, $content);
			fclose($fp);

			return "{$strUserId}.{$ext}";
		} catch (Exception $e) {}
		return '';
	}
	
	public function actionGetPhoto() {
		set_time_limit(0);
		$id = $_GET['id'];
		$record = GNSyncFacebookPhoto::model()->find('id=:id', array(
			':id'	=> IDHelper::uuidToBinary($id)
		));
		
		if (empty($record)) {
			$out = array(
				'error'		=> true,
				'message'	=> "Invalid Facebook Record ID"
			);
			ajaxOut($out);
		}
		
		$headers = @get_headers($record->source);
		
		$status = array_shift($headers);
		if ($status == "HTTP/1.1 403 Forbidden") {
			$out = array(
				'error'		=> true,
				'message'	=> "Can't get photo at {$record->source}"
			);
			ajaxOut($out);
		}
		
		foreach ($headers as $header) {
			if (substr($header, 0, 12) == 'Content-Type') {
				if (!preg_match("/image\/(jpg|jpeg|png)/", $header)) {
					$out = array(
						'error'		=> true,
						'message'	=> "Can't get photo at {$record->source}"
					);
					ajaxOut($out);
				} else {
					break;
				}
			}
			continue;
		}
		// || !preg_match("/image\/(jpg|jpeg|png)/", $headers[1])) {
		
		
		// else, continue
		// 1. Save file to local
		$content = file_get_contents($record->source);
		$ext = 'jpg';
		if (preg_match("/image\/png/", $header)) {
			$ext = 'png';
		}
		
		$fileID = substr(md5(uniqid()), 0, 32);
		
		$newFilename = "{$fileID}.{$ext}";
		
		$webroot = Yii::getPathOfAlias("jlwebroot");
		$dir = "{$webroot}/upload/user-photos/" . currentUser()->hexID;
		$newFilePath = "{$dir}/{$newFilename}";
		@mkdir($dir, 766);
		file_put_contents($newFilePath, $content);
		
		// Save to database
		Yii::import('application.modules.users.models.ZoneUserAvatar');
		$model = new ZoneUserAvatar();
		$model->image = $newFilename;
		$model->id = IDHelper::uuidToBinary($fileID);
		$model->object_id = ZoneUserAvatar::model()->prefix . currentUser()->hexID;
		$model->created = date("Y-m-d H:i:s");
			
		// timestamp
		$time = explode(" ", microtime());
		$model->microtime = $time[0];
		$model->md5 = md5_file($newFilePath);
		$model->score = 100.0;
			
		$imageSize = @getimagesize($newFilePath);
		if (!empty($imageSize)) {
			$ratio = $imageSize[0] / $imageSize[1];
				
			$model->ratio = $ratio;
			$model->max_width = $imageSize[0];
			$model->max_height = $imageSize[1];
		} else {
			$model->invalid = 1;
			$out = array(
				'error'		=> true,
				'message'	=> "Can't get imagesize of {$record->source}"
			);
			ajaxOut($out);
		}
			
		$model->save();
		
		// TODO: Change profile photo if not existed

		// 5. Mark as done
		$record->done = 1;

		// 6. Save photo_id (huytbt added)
		$record->photo_id = $model->id;

		$record->save();
		
		// ==========================================================
		$out = array(
			'error'		=> false,
			'message'	=> "Photo has been saved from Facebook",
			'data'		=> array(
				'id'	=> IDHelper::uuidFromBinary($model->id, true),
				'image'	=> $model->image,
			)
		);
		ajaxOut($out, false);
		// 2. Put file to server
		// store file in storage engines
		
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
		$s3Uploader = Yii::createComponent($config);
		$s3Uploader->store($newFilePath, array('s3path' => "upload/user-photos/" . currentUser()->hexID));
	}
	
	public function actionFriends() {
		
	}
} 
