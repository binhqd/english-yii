<?php
Yii::import('greennet.modules.users.models.*');
class ForgotController extends GNController
{
	public $viewFile = 'greennet.modules.users.views.forgot_password.forgotPassword';
	
	public $mailViewPath = 'application.views.mail';
	
	public $layout = "//layouts/master/homepage";
	public function allowedActions()
	{
		return '*';
	}
	// public function actions(){
		// return array(
			
			// 'index'	=> array(
				// 'class'			=> 'greennet.modules.users.actions.login.GNLoginAction',
				// 'viewFile'	=> 'application.modules.users.views.login.login',
			// ),

		// );
	// }

	public function actionIndex()
	{
	
		$this->pageTitle = 'Youlook - Forgot Password';
		
		// Deny user if they are not a guest
		if(currentUser()->id != -1) {
			// if request ajax then call ajaxOut
			$url = GNRouter::createAbsoluteUrl('/');
			if (Yii::app()->request->isAjaxRequest) {
				ajaxOut(array(
					'error'		=> true,
					'message'	=> UsersModule::t("You don't have permission to access this page"),
					'url'		=> $url
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error'		=> true,
					'type'		=>'error',
					'autoHiden'	=> true,
					'message'	=> UsersModule::t("You don't have permission to access this page")
				));
				Yii::app()->controller->redirect('/');
			}
		}
		
		$model = new ZoneForgotPasswordForm();
		
		if (!empty($_POST['ZoneForgotPasswordForm']))
		{
			$model->attributes = $_POST['ZoneForgotPasswordForm'];
			$valid = false;

			try {
				$valid = $model->validate();
				// exit('sdfsdf');
			} catch (Exception $ex) {
				if (Yii::app()->request->isAjaxRequest) {
					ajaxOut(array(
						'error' 	=> true,
						'message'	=> UsersModule::t($ex->getMessage()),
					));
				} else {
					
				}
			}
			
			if($valid)
			{
				// Check user has already exists
				try {
					$modelUser = GNUser::model()->findByEmail($model->email);
					if (empty($modelUser)) throw new Exception("This email doesn't exists in our database");
					
					// if user already request for a code, delete old code and create new code
					GNForgotPasswordValidation::model()->cleanOldRequest($model->email);
					
					//create code
					$codeForgotPassword = GNForgotPasswordValidation::model()->createCode($model->email);
					
					if (empty($codeForgotPassword)) throw new Exception('Cannot create confirmation code');
					
					
					//send email
					Yii::app()->mail->viewPath = $this->mailViewPath;
					$sendTo = $model->email;
					$subject = UsersModule::t("Password recovery");
					// url user cofirm mail
					$url = GNRouter::createAbsoluteUrl('/recover/change_password', array('code' => $codeForgotPassword));
					$data = array(
						'email'				=> $sendTo,
						'subject' 			=> $subject,
						'codeForgotPassword'=> $codeForgotPassword,
						'url'				=> $url,
						'user'				=> $modelUser,
						'profile'			=> $modelUser->profile,
					);
					
					//notify ajax
					if (Yii::app()->request->isAjaxRequest) {
						
						Yii::app()->mail->sendMailWithTemplate($sendTo, $subject, 'sendMailForgotPassword', $data);
						
						$ret = array(
							'error' 	=> false,
							'email'		=>$model->email,
							'type'		=>'success',
							'autoHiden'	=> true,
							'message'	=> UsersModule::t("Thank you!<br>We've sent an email to <b>{email}</b>.<br>Please check your spam folder if the email doesn't appear within a few minutes.", array('{email}' => $modelUser->email)),
						);
						
						ajaxOut(array(
							'error' 	=> false,
							'type'		=>'success',
							'email'		=>$model->email,
							'autoHiden'	=> true,
							'message'	=> UsersModule::t("Thank you!<br>We've sent an email to <b>{email}</b>.<br>Please check your spam folder if the email doesn't appear within a few minutes.", array('{email}' => $modelUser->email)),
						), false);
					} else {
						
					}
				} catch (Exception $ex) {
					if (Yii::app()->request->isAjaxRequest) {
						ajaxOut(array(
							'error' 	=> true,
							'message'	=> UsersModule::t($ex->getMessage()),
						));
					} else {
						Yii::app()->jlbd->dialog->notify(array(
							'error' => true,
							'type'=>'error',
							'autoHiden'=> true,
							'message'=>UsersModule::t($ex->getMessage()),
						));
					}
				}
			} else {
				if (Yii::app()->request->isAjaxRequest) {
					ajaxOut(array(
						'error'		=> true,
						'message'	=> UsersModule::t('Email is invalid')
					));
				}
			}
		} else {
			
			// if (isset(Yii::app()->session['emailRequestLogin'])) {
				// $model->email = Yii::app()->session['emailRequestLogin'];
				// unset(Yii::app()->session['emailRequestLogin']);
			// } elseif ($email != null) {
				// $model->email = $email;
			// }
		}
		$this->render('index',array(
			'model'=>$model
		));
	}
	

}