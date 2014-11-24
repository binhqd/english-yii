<?php
/**
 * This action is used to support user login
 */
Yii::import('greennet.modules.users.models.*');
Yii::import('greennet.modules.registration.models.*');
Yii::import('greennet.modules.registration.actions.*');

class ZoneActivateAction extends GNActivateAction {
	public $validation_success_uri = "/welcome";
	public $validation_failure_uri = "/";
	public $mailViewPath = 'greennet.modules.registration.views.mail';
	private $_onCreated = array();
	
	public function run() {
		$controller = $this->controller;
		
		// Display error if code is not valid
		if (!isset($_GET['code'])) {
			Yii::app()->jlbd->dialog->notify(array(
				'error'		=> true,
				'type'		=> 'error',
				'autoHide'	=> true,
				'message'	=> UsersModule::t("Code is invalid"),
			));
			$controller->redirect('/');
			Yii::app()->end();
		}
		$code = $_GET['code'];
		
		Yii::import('greennet.modules.validation_codes.models.ValidationCode');
		
		try {
			$tmpUser = GNRegistrationValidation::model()->getUserByCode($code);
		} catch (Exception $ex) {
			Yii::app()->jlbd->dialog->notify(array(
			'error'		=> true,
			'type'		=> 'error',
			'autoHide'	=> true,
			'message'	=> UsersModule::t($ex->getMessage()),
			));
			$controller->redirect('/');
		}
		
		// Display error if tmpUser not exist
		if (empty($tmpUser)) {
			$strMessage = UsersModule::t('<p>Activation Failure</p>Incorrect activation URL or Activation Code has expired!');
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
				$user = ZoneUser::model()->find('id=:id', array(
					':id'	=> $tmpUser->id
				));
				
				// Create user
				// set event handlers
				foreach ($this->_onCreated as $event) {
					$user->onCreated = $event;
				}
				
				// Delete activation code
				
				// Assign Permissions
				Rights::assign(Yii::app()->params['roles']['MEMBER'], $user->id);
				Rights::revoke(Yii::app()->params['roles']['AWAITING'],$user->id);
				
				$deleteCode = GNRegistrationValidation::model()->deleteCode($code);
				// delete temporary user
				if (!$tmpUser->delete()) throw new Exception('Cannot delete temporary user');
				
				$msg = "Your account has been validated!";
				$redirectUrl = GNRouter::createUrl($this->validation_success_uri);
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> false,
						'type'			=> 'success',
						'message'		=> UsersModule::t($msg),
						'urlRedirect'	=> $redirectUrl,
					));
				} else {
					$user->forceLogin();
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> false,
						'type'		=> 'success',
						'autoHide'	=> true,
						'message'	=> UsersModule::t($msg),
					));
					$controller->redirect($redirectUrl);
				}
			} catch (Exception $ex) {
				$failureUrl = GNRouter::createUrl($this->validation_failure_uri);
				Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR, "Validation");
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'			=> true,
						'type'			=> 'error',
						'message'		=> UsersModule::t($ex->getMessage()),
						'urlRedirect'	=> $failureUrl,
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> true,
						'type'		=> 'error',
						'autoHide'	=> true,
						'message'	=> UsersModule::t($ex->getMessage()),
					));
					$controller->redirect($failureUrl);
				}
			}
		}
	}
} 