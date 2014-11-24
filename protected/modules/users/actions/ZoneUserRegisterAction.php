<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 */
class ZoneUserRegisterAction extends GNAction {

	/**
	 * Define view file (path)
	 */
	public $emailViewPath = 'application.views.mail';
	public $activeUrl = '/users/registration/activate/';
	public $scenario = 'Registration';
	public $getAccessToken =  false;

	/**
	 * This method is used to run action
	 */
	public function run() {
		try {
			if (!empty($_POST['ZoneRegisterForm'])) {
				list($code, $data) = $this->register($_POST['ZoneRegisterForm']);
				if ($code != 200) {
					ajaxOut(array(
						'error' => true,
						'type' => 'error',
						'autoHide' => true,
						'error_message' => $data['validationErrors'],
						'message' => $data['message']
					));
				}
				ajaxOut(array(
					'error' => false,
					'cdn' => $data['cdn'],
					'user' => 'user',
					'data' => $data['data'],
					//'password' => $_POST['ZoneRegisterForm']['password'],
					'message' => UsersModule::t('Create user success')
						), false);
				$this->notify();
			}
		} catch (Exception $e) {
			ajaxOut(array(
				'error' => true,
				'type' => 'error',
				'autoHide' => true,
				'message' => $e->getMessage()
			));
		}
	}

	function register(array $post) {
		$Model = new ZoneRegisterForm($this->scenario);
		$Model->attributes = $post;

		if (!$Model->validate()) {
			$result = array(
				'message' => UsersModule::t('The data is invalid.'),
				'validationErrors' => $Model->getErrors()
			);
			return array(400, $result);
		}

		$this->model = new GNTmpUser;
		if (!$this->model->createUser($Model->attributes)) {
			throw new Exception(UsersModule::t('Cannot create user!'), 500);
		}

		$Captcha = $this->controller->createAction('captcha');
		$Captcha && $Captcha->getVerifyCode(true);

		$ModelTmpProfile = new ZoneUserTmpProfile;
		$ModelTmpProfile->attributes = $_POST;

		if ($Model->daybirth && $Model->monthbirth && $Model->yearbirth) {
			$birth = strtotime(intval($Model->yearbirth) . '-'
					. intval($Model->monthbirth) . '-' . intval($Model->daybirth));
			$ModelTmpProfile->birth = date('Y-m-d', $birth);
		}

		$ModelTmpProfile->createProfile($this->model->id, $ModelTmpProfile->attributes);

		// create gn user
		$User = new ZoneUser();
		$User->createUser($this->model->attributes);

		$ModelProfile = new GNUserProfile;
		if (!$ModelProfile->createProfile($User->id)) {
			throw new Exception('Cannot create profile', 500);
		}
		// Assign Permissions
		Rights::assign(Yii::app()->params['roles']['AWAITING'], $User->id);
		// hack the old code
		Yii::import("application.modules.users.events.MigrateProfileHandler");
		MigrateProfileHandler::MigrateProfile($User);
		Yii::import("application.modules.users.events.UpdateUserNodeHandler");
		UpdateUserNodeHandler::UpdateUserNode($User);
		// delete temporary user
		// if (!$this->model->delete()){
		//		throw new Exception('Cannot delete temporary user');
		// }
		$User->forceLogin();
		$UserInfo = new ApiZoneUser($User);

		$result = array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => array(
				'id' => $UserInfo->hexID,
				'username' => $UserInfo->username,
				'displayname' => $UserInfo->displayname,
				'email' => $UserInfo->email,
				'profile' => $UserInfo->profile()
			)
		);
		
		if($this->getAccessToken){
			$result['accessToken'] = $UserInfo->accessToken();
		}
		
		return array(200, $result);
	}

	public function notify() {
		// sent notify if need
		if (!$this->model) {
			return;
		}
		$validationCode = $this->model->createValidationCode();
		$strActiveUrl = GNRouter::createAbsoluteUrl($this->activeUrl, array('code' => $validationCode));

		// Send activation code to email
		Yii::app()->mail->viewPath = $this->emailViewPath;
		$sendMail = Yii::app()->mail->sendMailWithTemplate($this->model->email, UsersModule::t('Youlook membership confirmation')
				, 'sendMailActivationAccount', array('strActiveUrl' => $strActiveUrl, 'user' => $this->model));
		if (!empty($validationCode) && $sendMail == false) {
			$this->model->saveAttributes(array('has_sent_code' => 1));
		}
	}

}