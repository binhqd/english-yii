<?php
Yii::import('greennet.modules.registration.interfaces.IOAuthRegistrationController');
Yii::import('greennet.modules.social.components.*');
Yii::import('greennet.modules.social.components.Google.*');
class GNGoogleController extends GNController implements IOAuthRegistrationController {
	protected $_connector;
	public $mailViewPath = 'greennet.modules.social.views.mail';
	
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
		$config = array(
			'class' => 'greennet.modules.social.components.Google.GNGmailConnector'
		);
		
		$config = CMap::mergeArray($config, Yii::app()->params['OAuth']['Gmail']);
	
		$this->_connector = Yii::createComponent($config);
		$this->_connector->init();
	}
	
	public function actionIndex() {
		$isConnected = $this->_connector->connect();
		
		if (!$isConnected) {
			$gmailAuthUrl	= $this->_connector->getAuthUrl();
			
			$this->redirect($gmailAuthUrl);
		} else {
			$userInfo = $this->_connector->userInfo;
			
			$user = GNUser::model()->findByEmail($userInfo['email']);
			if (!empty($user)) {
				// Force user to login
				$user->forceLogin();
				
				// Set success flash
				$msg = Yii::t("greennet", "You are now successful login");
				Yii::app()->jlbd->dialog->notify(array(
					'type'		=>	'success',
					'autoHide'	=>	true,
					'message'	=>	$msgNotify,
				));
				$this->redirect('/');
			} else {
				throw new Exception(sprintf(Yii::t("greennet", "This email (%s) doesn't exist in our database"), $userInfo['email']));
			}
		}
	}
	
	/**
	 * This action is used to handle event after successful login with Facebook
	 */
	public function actionConnect() {
		if (!isset($_GET['code'])) {
			if (!$this->isJsonRequest) {
				Yii::app()->jlbd->dialog->notify(array(
					'error'	=> true,
					'type' => 'error',
					'autoHide' => true,
					'message' => Yii::t("greennet", 'Could not login with Google. Because parameter is invalid.'),
				));
				$this->redirect('/');
			} else {
				ajaxOut(array(
					'error'		=> true,
					'message'	=>Yii::t("greennet", 'Could not login with Google. Because parameter is invalid.'),
				));
			}
			exit;
		}
		try {
			$isConnected = $this->_connector->connect();
			
			if ($isConnected) {
				// get user info
				$userInfo = $this->_connector->userInfo;

				// TODO: skip if this facebook has been mapped

				// Check if user with this email has registered or not
				$user = GNUser::model()->findByEmail($userInfo['email']);
				
				// If user with that email not exist, create new user
				if (empty($user)) {
					$password = uniqid();
					$strSalt = GNUser::createSalt();
					
					Yii::import('greennet.helpers.Sluggable');
					
					// create new user
					$firstname = Sluggable::convertToLatin($userInfo['given_name']);
					$lastname = Sluggable::convertToLatin($userInfo['family_name']);
					
					Yii::import('greennet.helpers.Sluggable');

					$username = Sluggable::slug($userInfo['email']);
					$username = preg_replace("/@/", '.', $username);
					$username = preg_replace("/(\.[a-z0-9]+)$/", '', $username);
					
					$arrInfo = array(
						'firstname'	=> $firstname,
						'lastname'	=> $lastname,
						'email'		=> $userInfo['email'],
						'created'	=> time(),
						'saltkey'	=> $strSalt,
						'password'	=> GNUser::encryptPassword($password, $strSalt),
						'username'	=> $username,
						'displayname'	=> GNUser::createDisplayName($firstname, $lastname)
					);
					
					// Create user
					$user = GNUser::model()->createUser($arrInfo);

					// Get avatar
					if (isset($userInfo['picture']))
					$downloadAvatar = $this->_downloadAvatar($userInfo['picture'], $user->hexID);

					// Create user profile
					$modelProfile = new GNUserProfile;
					$createProfile = $modelProfile->createProfile($user->id, array('image'=>$downloadAvatar));
					if (!$createProfile) throw new Exception('Cannot create profile');
					
					// Assign Permissions
					Rights::assign(Yii::app()->params['roles']['MEMBER'],$user->id);

					// Send mail to user
					Yii::app()->mail->viewPath = $this->mailViewPath;
					$sendMail = Yii::app()->mail->sendMailWithTemplate($user->email, Yii::t("greennet", 'GreenNet membership created'), 'sendMailUserSocialCreated', $data=array('user'=>$user, 'password'=>$password, 'socialName'=>'Google'));
				}
				
				// map user with facebook account
				$user->google->saveConnectionData($userInfo['id'], $userInfo);
				
				// print out
				$out = array(
					'error'		=> false,
					'message'	=> Yii::t("greennet", 'You are now connected with Facebook')
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
					$this->redirect('/');
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
						'message' => Yii::t("greennet", 'Could not login with Google.'),
					));
					$this->redirect('/');
				} else {
					ajaxOut(array(
						'error'		=> true,
						'message'	=>Yii::t("greennet", 'Could not login with Google'),
					));
				}
				//$this->renderPartial('failed', compact('out'));
				exit;
			}
		} catch (Exception $e) {
			if (!$this->isJsonRequest) {
				Yii::app()->jlbd->dialog->notify(array(
					'error'	=> true,
					'type' => 'error',
					'autoHide' => true,
					'message' => Yii::t("greennet", $e->getMessage()),
				));
				$this->redirect('/');
			} else {
				ajaxOut(array(
					'error'		=> true,
					'message'	=> Yii::t("greennet", $e->getMessage()),
				));
			}
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
} 