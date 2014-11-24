<?php
/**
 * This action is used to support user login
 */
Yii::import('greennet.modules.users.models.*');
Yii::import('greennet.modules.registration.models.*');

class GNActivateAction extends GNAction {
	public $validation_success_uri = "/profile";
	public $validation_failure_uri = "/";
	public $mailViewPath = 'greennet.modules.registration.views.mail';
	private $_onCreated = array();
	
	/**
	 * 
	 * @param handlers $handlers
	 */
	public function setOnCreated($handlers) {
		// TODO : May check if handler is an object
		if (is_array($handlers)) {
			for ($i = 0; $i < count($handlers); $i++) {
				$handler = $handlers[$i];
	
				if (!empty($handler[0])) {
					$className = Yii::import($handler[0]);
				}
				$handlers[$i][0] = $className;
			}
	
			$this->_onCreated = $handlers;
		}
	}
	
	public function run()
	{
		$controller = $this->controller;
		
		// Display error if code is not valid
		if (!isset($_GET['code'])) {
			Yii::app()->jlbd->dialog->notify(array(
				'error'		=> true,
				'type'		=> 'error',
				'autoHide'	=> true,
				'message'	=> Yii::t("greennet", "Code is invalid"),
			));
			$controller->redirect('/');
			Yii::app()->end();
		}
		$code = $_GET['code'];
		
		Yii::import('greennet.modules.validation_codes.models.ValidationCode');
		
		// Get TmpUser object
		try {
			$tmpUser = GNRegistrationValidation::model()->getUserByCode($code);
		} catch (Exception $ex) {
			Yii::app()->jlbd->dialog->notify(array(
				'error'		=> true,
				'type'		=> 'error',
				'autoHide'	=> true,
				'message'	=> $ex->getMessage(),
			));
			$controller->redirect('/');
		}
		
		// Display error if tmpUser not exist
		if (empty($tmpUser)) {
			$strMessage = Yii::t("greennet", '<p>Activation Failure</p>Incorrect activation URL or Activation Code has expired!');
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'		=> true,
					'type'			=> 'error',
					'message'		=> $strMessage,
					'urlRedirect'	=> GNRouter::createAbsoluteUrl('/'),
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error'		=> true,
					'type'		=> 'error',
					'autoHide'	=> true,
					'message'	=> $strMessage,
				));
				$controller->redirect('/');
			}
		} else {
			try {
				$user = new GNUser();
				// Create user
				// set event handlers
				foreach ($this->_onCreated as $event) {
					$user->onCreated = $event;
				}
				
				$user->createUser($tmpUser->attributes);
				if (empty($user)) throw new Exception(Yii::t("greennet", 'Cannot delete temporary user'));

				// delete temporary user
				if (!$tmpUser->delete()) throw new Exception(Yii::t("greennet", 'Cannot delete temporary user'));

				// Delete activation code
				$deleteCode = GNRegistrationValidation::model()->deleteCode($code);

				// Create user profile
				$modelProfile = new GNUserProfile;
				$createProfile = $modelProfile->createProfile($user->id);
				if (!$createProfile) throw new Exception(Yii::t("greennet", 'Cannot create profile'));

				// Assign Permissions
				Rights::assign(Yii::app()->params['roles']['MEMBER'],$user->id);
				
				$msg = Yii::t("greennet", "Your account has been validated!");
 				$redirectUrl = GNRouter::createUrl($this->validation_success_uri);
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> false,
						'type'			=> 'success',
						'message'		=> $msg,
						'urlRedirect'	=> $redirectUrl,
					));
				} else {
					$user->forceLogin();
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> false,
						'type'		=> 'success',
						'autoHide'	=> true,
						'message'	=> $msg,
					));
					$controller->redirect($redirectUrl);
				}
			} catch (Exception $ex) {
				$failureUrl = GNRouter::createUrl($this->validation_failure_uri);
				
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'			=> true,
						'type'			=> 'error',
						'message'		=> $ex->getMessage(),
						'urlRedirect'	=> $failureUrl,
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> true,
						'type'		=> 'error',
						'autoHide'	=> true,
						'message'	=> $ex->getMessage(),
					));
					$controller->redirect($failureUrl);
				}
			}
		}
	}
	
	
} 