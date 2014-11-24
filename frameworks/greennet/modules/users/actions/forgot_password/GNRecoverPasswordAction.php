<?php
Yii::import('greennet.modules.users.models.GNChangePasswordForm');
Yii::import('greennet.modules.users.models.GNForgotPasswordValidation');

/**
 * This action is used to change password (Forgot password - Step 2)
 *
 * @param code	String of cofirmation code
 * Action ChangePassword is used to change password of user after they has verified the request
 */
class GNRecoverPasswordAction extends CAction {
	public $viewFile = 'greennet.modules.users.views.forgot_password.changePassword';
	public $mailViewPath = 'greennet.views.mail_templates.recovery';
	
	public function run($code = null)
	{
		$controller = $this->controller;
		
		//check code
		$model = new GNChangePasswordForm();
		
		// Display error if code is not valid
		if (!isset($_GET['code'])) {
			Yii::app()->jlbd->dialog->notify(array(
				'error'		=> true,
				'type'		=> 'error',
				'autoHide'	=> true,
				'message'	=> "Your code is invalid",
			));
			$controller->redirect('/');
			Yii::app()->end();
		}
		$code = $_GET['code'];
		
		Yii::import('greennet.modules.validation_codes.models.ValidationCode');
		
		if (!ValidationCode::isCodeValidate($code)) {
			$strMessage = Yii::t("greennet", "Code is invalid");
			
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'		=> true,
					'type'			=> 'error',
					'message'		=> $strMessage,
					'urlRedirect'	=> GNRouter::createUrl('/'),
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
		}
		
		// -------------------------
		if (!empty($_POST['GNChangePasswordForm']))
		{
			$model->setAttributes($_POST['GNChangePasswordForm'], false);
			
			try {
				$validate = $model->validate();
			} catch (Exception $ex) {
				if($controller->isJsonRequest) {
					ajaxOut(array(
						'error' => true,
						'message'	=> Yii::t("greennet", $ex->getMessage())
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'	=> true,
						'type'=>'error',
						'autoHiden'=> true,
						'message'=>Yii::t("greennet", $ex->getMessage())
					));
				}
			}
			
			
			try {
				$request = GNForgotPasswordValidation::model()->findRequestByCode($code);
				$user = GNUser::model()->findByEmail($request->email);
				
				if (empty($user)) throw new Exception(Yii::t("greennet", 'This user does not exists'));

				$email = $request->email;
				// valid account user exists
				$changePassword = $user->changePassword($model->password);
				
				if ($changePassword == false) throw new Exception(Yii::t("greennet", 'Cannot change password'));

				//delete code
				//GNActivationCode::model()->deleteCode($check->id);
				GNForgotPasswordValidation::model()->deleteCode($code);
				
				//Send email change password success
				Yii::app()->mail->viewPath = 'application.modules.users.views.mailtemplates';
				// url user confirm mail
				$url = GNRouter::createAbsoluteUrl('/login');
				$subject = Yii::t("greennet", 'Your password has been changed successful');
				$data = array(
					'email'		=> $email,
					'subject'	=> $subject,
					'url'		=> $url,
					'user'		=> $user,
				);
				
				// ajax notify
				if($controller->isJsonRequest) {
					ajaxOut(array(
						'error'	=> false,
						'url'	=> $url,
						'message'	=> Yii::t("greennet", 'Thank you ! You have successfuly changed password at Greenet.')
					), false);
					Yii::app()->mail->sendMailWithTemplate($email, $subject, 'sendMailChangePassword', $data);
				} else {
					Yii::app()->mail->sendMailWithTemplate($email, $subject, 'sendMailChangePassword', $data);
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> false,
						'type'		=> 'success',
						'autoHiden'	=> true,
						'url'	=> GNRouter::createUrl('/login'),
						'message'=>Yii::t("greennet", 'Thank you ! You have successfuly changed password at Greenet.')
					));
					$controller->redirect($url);
				}
			} catch (Exception $ex) {
				if($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> true,
						'message'	=> Yii::t("greennet", $ex->getMessage())
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'type'		=> 'error',
						'autoHiden'	=> true,
						'message'	=> Yii::t("greennet", $ex->getMessage())
					));
				}
			}
		}

		$controller->render($this->viewFile, array(
			'model'=>$model,
		));
	}
}