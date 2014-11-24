<?php
/**
 * This action is used to display profile home of user
 */
Yii::import('greennet.modules.users.models.GNForgotPasswordForm');
Yii::import('greennet.modules.users.models.GNForgotPasswordValidation');
class GNRequestPasswordAction extends CAction {
	public $viewFile = 'greennet.modules.users.views.forgot_password.forgotPassword';
	public $mailViewPath = 'greennet.views.mail_templates.recovery';
	
	public function run($email = null)
	{
		$controller = $this->controller;
		
		// Deny user if they are not a guest
		if(currentUser()->id != -1) {
			// if request ajax then call ajaxOut
			$url = GNRouter::createAbsoluteUrl('/');
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'		=> true,
					'message'	=> Yii::t("greennet", "You don't have permission to access this page"),
					'url'		=> $url
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error'		=> true,
					'type'		=>'error',
					'autoHiden'	=> true,
					'message'	=> Yii::t("greennet", "You don't have permission to access this page")
				));
				$controller->redirect('/');
			}
		}
		
		$model = new GNForgotPasswordForm();
		if (!empty($_POST['GNForgotPasswordForm']))
		{
			$model->attributes = $_POST['GNForgotPasswordForm'];
			$valid = false;

			try {
				$valid = $model->validate();
			} catch (Exception $ex) {
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error' 	=> true,
						'message'	=> Yii::t("greennet", $ex->getMessage()),
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error' => true,
						'type'=>'error',
						'autoHiden'=> true,
						'message'=>Yii::t("greennet", $ex->getMessage()),
					));
				}
			}
			
			if($valid)
			{
				// Check user has already exists
				try {
					$modelUser = GNUser::model()->findByEmail($model->email);
					if (empty($modelUser)) throw new Exception(Yii::t("greennet", "This email doesn't exists in our database"));
					
					// if user already request for a code, delete old code and create new code
					GNForgotPasswordValidation::model()->cleanOldRequest($model->email);
					
					//create code
					$codeForgotPassword = GNForgotPasswordValidation::model()->createCode($model->email);
					
					if (empty($codeForgotPassword)) throw new Exception(Yii::t("greennet", 'Cannot create confirmation code'));
					
					
					//send email
					Yii::app()->mail->viewPath = $this->mailViewPath;
					$sendTo = $model->email;
					$subject = Yii::t("greennet", "Password recovery");
					// url user cofirm mail
					$url = GNRouter::createAbsoluteUrl('/recover/change_password', array('code' => $codeForgotPassword));
					$data = array(
						'email'				=> $sendTo,
						'subject' 			=> $subject,
						'codeForgotPassword'=> $codeForgotPassword,
						'url'				=> $url,
						'user'				=> $modelUser,
					);
					
					//notify ajax
					if ($controller->isJsonRequest) {
						
						Yii::app()->mail->sendMailWithTemplate($sendTo, $subject, 'sendMailForgotPassword', $data);
						
						$ret = array(
							'error' 	=> false,
							'type'		=>'success',
							'autoHiden'	=> true,
							'message'	=> Yii::t("greennet", "Thank you!<br>We've sent an email to <b>{email}</b>.<br>Please check your spam folder if the email doesn't appear within a few minutes.", array('{email}' => $modelUser->email)),
						);
						
						ajaxOut(array(
							'error' 	=> false,
							'type'		=>'success',
							'autoHiden'	=> true,
							'message'	=> Yii::t("greennet", "Thank you!<br>We've sent an email to <b>{email}</b>.<br>Please check your spam folder if the email doesn't appear within a few minutes.", array('{email}' => $modelUser->email)),
						), false);
					} else {
						Yii::app()->mail->sendMailWithTemplate($sendTo, $subject, 'sendMailForgotPassword', $data);
						Yii::app()->jlbd->dialog->notify(array(
							'error' => false,
							'type'	=>'success',
							'autoHiden'	=> true,
							'message'	=> Yii::t("greennet", "Thank you!<br>We've sent an email to <b>{email}</b>.<br>Please check your spam folder if the email doesn't appear within a few minutes.", array('{email}' => $modelUser->email))
						));
						$controller->redirect("/login");
					}
				} catch (Exception $ex) {
					if ($controller->isJsonRequest) {
						ajaxOut(array(
							'error' 	=> true,
							'message'	=> Yii::t("greennet", $ex->getMessage()),
						));
					} else {
						Yii::app()->jlbd->dialog->notify(array(
							'error' => true,
							'type'=>'error',
							'autoHiden'=> true,
							'message'=>Yii::t("greennet", $ex->getMessage()),
						));
					}
				}
			} else {
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> true,
						'message'	=> Yii::t("greennet", 'Email is invalid')
					));
				}
			}
		} else {
			if (isset(Yii::app()->session['emailRequestLogin'])) {
				$model->email = Yii::app()->session['emailRequestLogin'];
				unset(Yii::app()->session['emailRequestLogin']);
			} elseif ($email != null) {
				$model->email = $email;
			}
		}
		
		$controller->render($this->viewFile, array(
			'model'=>$model,
		));
	}
}