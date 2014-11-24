<?php

/**
 * RegistrationController - This controller is used to contain actions support for register
 *
 * @author Phihx
 * @version 1.0
 * @created 23-Jan-2013 4:45:32 PM
 * @modified 29-Jan-2013 11:09:19 AM
 */
class RegistrationController extends GNController {

	public $viewFile = 'greennet.modules.registration.views.register';
	public $validation_uri = "/users/registration/activate/";
	public $validation_success_uri = "/profile";
	public $validation_failure_uri = "/";
	public $mailViewPath = 'application.views.mail';
	public $layout = "//layouts/master/homepage";
	public $defaultAction = 'register';

	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions() {
		return '*';
	}

	public function actions() {
		return array(
			'activate' => array(
				'class' => 'application.modules.users.actions.ZoneActivateAction',
				'onCreated' => array(
				// array("application.modules.users.events.UpdateUserNodeHandler", "UpdateUserNode"),
				// array("application.modules.users.events.MigrateProfileHandler", "MigrateProfile"),
				// array("application.modules.users.events.YourInterestsHandler", "YourInterests"),
				// array("application.modules.users.events.UpdateUserNodeHandler", "TestMore")
				)
			),
			'register' => array(
				'class' => 'application.modules.users.actions.ZoneUserRegisterAction',
				'scenario' => 'Registration',
			),
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
				'testLimit' => 0,
			),
		);
	}

	public function actionRegister() {
		if(!currentUser()->isGuest){
			$this->redirect('/user/profile');
		}
		
		$Action = $this->createActionFromMap($this->actions(), 'register', 'register');
		$Model = new ZoneRegisterForm($Action->scenario);
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'userRegistrationForm') {
			echo CActiveForm::validate($Model);
			Yii::app()->end();
		}
		if (isset($_POST['ZoneRegisterForm'])) {
			if (!empty($_POST['ZoneRegisterForm'])) {
				list($code, $data) = $Action->register($_POST['ZoneRegisterForm']);
				if ($code != 200) {
					if ($this->isJsonRequest) {
						ajaxOut(array(
							'error' => true,
							'message' => $data['message'],
							'url' => GNRouter::createUrl($this->validation_failure_uri)
						));
					} else {
						Yii::app()->jlbd->dialog->notify(array(
							'error' => true,
							'type' => 'error',
							'autoHide' => true,
							'message' => $data['message'],
						));
						$this->redirect('/');
					}
				}
				$this->redirect('/welcome');
			}
		}
		$this->render('register', array(
			'model' => $Model
		));
	}

	public function actionSendMailActive() {
		if (!empty($_POST)) {
			// Send activation code to email

			$strActiveUrl = $_POST['strActiveUrl'];
			$modelTmpUser = $_POST['user'];
			Yii::app()->mail->viewPath = $this->mailViewPath;
			$sendMail = Yii::app()->mail->sendMailWithTemplate($_POST['email'], UsersModule::t('Youlook membership confirmation'), 'sendMailActivationAccount', $data = array('strActiveUrl' => $strActiveUrl, 'user' => $modelTmpUser));
			// if(!empty($validationCode) && $sendMail == false)
		} else {
			jsonOut(array(
				'error' => true
			));
		}
	}

	/**
	 * This action is used to support register by email (Register by Email - Step 1)
	 * @author Phihx
	 * @date 04-02-2013
	 */
	public function actionRegisterByEmail() {
		$model = new GNRegisterByEmail;
		if (!empty($_POST['GNRegisterByEmail'])) {
			$model->email = $_POST['GNRegisterByEmail']['email'];

			//Check your email registered and activated or not?
			$this->_hasRegistered($model->email);

			try {
				// Validate form
				if ($model->validate()) {
					// Create user
					$createUser = $model->createUser(array('email' => $model->email));
					if (empty($createUser))
						throw new Exception('Cannot create user');

					// Create code to send confirm mail
// 					$activeCode = GNActivationCode::model()->createCode($model->id, GNActivationCode::TYPE_ACTIVATE_ACCOUNT);
					$validationCode = GNTmpUser::createValidationCode();
					ajaxOut($validationCode);

					if (empty($activeCode))
						throw new Exception('Cannot create confirmation code');

					$strActiveUrl = JLRouter::createAbsoluteUrl('users/registration/confirm/', array('code' => $activeCode));
					Yii::app()->mail->viewPath = 'application.modules.users.views.mailtemplates';
					$sendMail = Yii::app()->mail->sendMailWithTemplate($model->email, 'GrennNet membership confirmation', 'sendMailActivationAccount', $data = array('strActiveUrl' => $strActiveUrl));
					if ($sendMail == false)
						throw new Exception('Cannot send confirmation mail');
					$model->saveAttributes(array('has_sent_code' => 1));

					// Notice was send activation code information via email
					$message = UsersModule::t('<p>Congratulations!</p>
						You’ve just completed the first step in registering as a GreenNet member.<br/>
						We’ll now send a verification email to: <b>{strEmail}</b><br/>
						When you receive it, just click on the appropriate link or button and that’s the process complete.<br/>
						We’ll send the email immediately but it may take a little time to arrive in your inbox.', array('{strEmail}' => $model->email)
					);

					if ($this->isJsonRequest) {
						ajaxOut(array(
							'error' => false,
							'type' => 'success',
							'message' => $message,
							'url' => JLRouter::createAbsoluteUrl('/'),
						));
					} else {
						Yii::app()->jlbd->dialog->notify(array(
							'error' => false,
							'type' => 'success',
							'autoHide' => true,
							'message' => $message
						));
						$this->redirect('/');
					}
				} else {
					if ($this->isJsonRequest) {
						ajaxOut(array(
							'error' => true,
							'type' => 'error',
							'errors' => $model->errors,
						));
					}
				}
			} catch (Exception $ex) {
				if ($this->isJsonRequest) {
					ajaxOut(array(
						'error' => true,
						'type' => 'error',
						'message' => $ex->getMessage(),
						'url' => JLRouter::createAbsoluteUrl('/'),
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error' => true,
						'type' => 'error',
						'autoHide' => true,
						'message' => $ex->getMessage()
					));
					$this->redirect('/');
				}
			}
		}

		$this->render('register_by_email', array('model' => $model));
	}

	/**
	 * This action is used to confirm & complete register by Email (Register by Email - Step 2, 3)
	 * 
	 * @author Phihx
	 * @date 04-02-2013
	 * @param code String of Confirmation code
	 */
	public function actionConfirm($code) {
		$checkCode = GNActivationCode::model()->checkCode($code);
		if (!$checkCode) {
			if ($this->isJsonRequest) {
				ajaxOut(array(
					'error' => true,
					'type' => 'error',
					'message' => UsersModule::t('<p>Activation Email Failure</p><br/>Incorrect activation URL or Activation period has expired!'),
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error' => true,
					'type' => 'error',
					'autoHide' => true,
					'message' => UsersModule::t('<p>Activation Email Failure</p><br/>Incorrect activation URL or Activation period has expired!'),
				));
				$this->redirect('/');
			}
		} else {
			try {
				$tmpUser = GNTmpUser::model()->findByPk($checkCode->user_id);
				if (empty($tmpUser))
					throw new Exception('This user does not exists');

				if (!empty($_POST['GNTmpUser'])) {
					$tmpUser->attributes = $_POST['GNTmpUser'];
					$tmpUser->displayname = ucfirst(strtolower($tmpUser->firstname)) . " " . strtoupper(substr($tmpUser->lastname, 0, 1)) . ".";

					if (empty($tmpUser->username)) {
						$username = Sluggable::slug($tmpUser->email);
						$username = preg_replace("/@/", '.', $username);
						$username = preg_replace("/(\.[a-z0-9]+)$/", '', $username);

						$tmpUser->username = $username;
					}
					if ($tmpUser->validate()) {
						$tmpUser->saltkey = GNUser::createSalt();
						$tmpUser->password = GNUser::encryptPassword($tmpUser->password, $tmpUser->saltkey);
						if (!$tmpUser->save())
							throw new Exception('Cannot save your information');

						// Create user
						$user = GNUser::model()->createUser($tmpUser->attributes);
						if (empty($user))
							throw new Exception('Cannot create user');

						// delete temporary user
						if (!$tmpUser->delete())
							throw new Exception('Cannot delete temporary user');

						// Delete activation code
						$deleteCode = GNActivationCode::model()->deleteCode($checkCode->id);

						// Create user profile
						$userProfile = new GNUserProfile;
						$createProfile = $userProfile->createProfile($user->id);
						if (empty($createProfile))
							throw new Exception('Cannot create user profile');
						// Assign Permissions
						Rights::assign(Yii::app()->params['roles']['MEMBER'], $user->id);
						// Force login
						$user->forceLogin();

						if ($this->isJsonRequest) {
							ajaxOut(array(
								'error' => false,
								'type' => 'success',
								'message' => UsersModule::t('Register successful!'),
							));
						} else {
							Yii::app()->jlbd->dialog->notify(array(
								'error' => false,
								'type' => 'success',
								'autoHide' => true,
								'message' => UsersModule::t('Register successful!'),
							));
							$this->redirect('/');
						}
					} else {
						if ($this->isJsonRequest) {
							ajaxOut(array(
								'error' => true,
								'type' => 'error',
								'errors' => $tmpUser->errors,
							));
						}
					}
				}
				$this->render('fill_information', array('model' => $tmpUser));
			} catch (Exception $ex) {
				if ($this->isJsonRequest) {
					ajaxOut(array(
						'error' => true,
						'type' => 'error',
						'message' => $ex->getMessage(),
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error' => true,
						'type' => 'error',
						'autoHide' => true,
						'message' => $ex->getMessage(),
					));
					$this->redirect('/');
				}
			}
		}
	}

	/**
	 * This action is used to resend activate code.
	 * @author Hoàng Xuân Phi
	 * @date 06-02-2013
	 */
	public function actionResendCode($email) {
		try {
			//Check email registered by fil email or by fill information
			$checkEmail = GNTmpUser::model()->findByEmail($email);
			if (empty($checkEmail))
				throw new Exception('This email does not exists');

			// Delete the old activation code 
			$delCode = GNActivationCode::model()->deleteAllByAttributes(array('user_id' => $checkEmail->id));

			//Create a new activation code
			$activeCode = GNActivationCode::model()->createCode($checkEmail->id, GNActivationCode::TYPE_ACTIVATE_ACCOUNT);
			if (empty($activeCode))
				throw new Exception('Cannot create activation code');

			// Send activation mail
			if ($checkEmail->saltkey != NULL) //Email register by information
				$strActiveUrl = JLRouter::createAbsoluteUrl('users/registration/activate/', array('code' => $activeCode, 'email' => $checkEmail->email));
			else //Email register by fill email
				$strActiveUrl = JLRouter::createAbsoluteUrl('users/registration/confirm/', array('code' => $activeCode, 'email' => $checkEmail->email));

			Yii::app()->mail->viewPath = 'application.modules.users.views.mailtemplates';
			$sendMail = Yii::app()->mail->sendMailWithTemplate($checkEmail->email, 'GrennNet membership confirmation', 'sendMailActivationAccount', $data = array('strActiveUrl' => $strActiveUrl));
			if ($sendMail == false)
				throw new Exception('Cannot send activation mail');

			$checkEmail->saveAttributes(array('has_sent_code' => 1));

			if ($this->isJsonRequest) {
				ajaxOut(array(
					'error' => false,
					'type' => 'success',
					'message' => UsersModule::t('Activate code has been sent successfully!'),
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error' => false,
					'type' => 'success',
					'autoHide' => true,
					'message' => UsersModule::t('Activate code has been sent successfully!'),
				));
				$this->redirect('/');
			}
		} catch (Exception $ex) {
			if ($this->isJsonRequest) {
				ajaxOut(array(
					'error' => true,
					'type' => 'error',
					'message' => UsersModule::t($ex->getMessage()),
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error' => true,
					'type' => 'error',
					'autoHide' => true,
					'message' => UsersModule::t($ex->getMessage()),
				));
				$this->redirect('/');
			}
		}
	}

}