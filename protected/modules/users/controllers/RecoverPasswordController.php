<?php
Yii::import('greennet.modules.users.models.*');
class RecoverPasswordController extends GNController
{
	public $viewFile = 'greennet.modules.users.views.forgot_password.changePassword';
	
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

	public function actionIndex($code = null)
	{
	
		
		
		//check code
		$model = new GNChangePasswordForm();
		
		// Display error if code is not valid
		if (!isset($_GET['code'])) {
			
			$this->redirect('/');
			Yii::app()->end();
		}
		$code = $_GET['code'];
		
		
		Yii::import('greennet.modules.validation_codes.models.ValidationCode');
		
		if (!ValidationCode::isCodeValidate($code)) {
			$strMessage = UsersModule::t("Code is invalid");
			
			if (Yii::app()->request->isAjaxRequest) {
				ajaxOut(array(
					'error'		=> true,
					'type'			=> 'error',
					'message'		=> $strMessage,
					'urlRedirect'	=> GNRouter::createUrl('/'),
				));
			} else {
				$this->redirect('/user/profile');
			}
		}
		if(!empty($code) && currentUser()->id!=-1){
			Yii::app()->user->logout();
			$this->redirect("/recover/change_password?code={$code}");
		}
		
		// -------------------------
		if (!empty($_POST['GNChangePasswordForm']))
		{
			
			$model->setAttributes($_POST['GNChangePasswordForm'], false);
			
			try {
				$validate = $model->validate();
			} catch (Exception $ex) {
				if(Yii::app()->request->isAjaxRequest) {
					ajaxOut(array(
						'error' => true,
						'message'	=> UsersModule::t($ex->getMessage())
					));
				} else {
					
				}
			}
			
			
			try {
				$request = GNForgotPasswordValidation::model()->findRequestByCode($code);
				$user = GNUser::model()->findByEmail($request->email);
				$user->forceLogin();
				if (empty($user)) throw new Exception('This user does not exists');

				$email = $request->email;
				// valid account user exists
				$changePassword = $user->changePassword($model->password);
				
				if ($changePassword == false) throw new Exception('Cannot change password');

				//delete code
				//GNActivationCode::model()->deleteCode($check->id);
				GNForgotPasswordValidation::model()->deleteCode($code);
				
				//Send email change password success
				
				Yii::app()->mail->viewPath = $this->mailViewPath;
				
				// url user confirm mail
				$url = GNRouter::createAbsoluteUrl('/login');
				$subject = UsersModule::t('Your Youlook password was updated');
				$data = array(
					'email'		=> $email,
					'subject'	=> $subject,
					'url'		=> $url,
					'user'		=> $user,
				);
				
				// ajax notify
				if(Yii::app()->request->isAjaxRequest) {
					ajaxOut(array(
						'error'	=> false,
						'url'	=> $url,
						'message'	=> UsersModule::t('Thank you ! You have successfuly changed password at Greenet.')
					), false);
					Yii::app()->mail->sendMailWithTemplate($email, $subject, 'sendMailChangePassword', $data);
				} else {
					Yii::app()->mail->sendMailWithTemplate($email, $subject, 'sendMailChangePassword', $data);
					
					$this->redirect($url);
				}
			} catch (Exception $ex) {
				if(Yii::app()->request->isAjaxRequest) {
					ajaxOut(array(
						'error'		=> true,
						'message'	=> UsersModule::t($ex->getMessage())
					));
				} else {
					
				}
			}
		}

		$this->render('index', array(
			'model'=>$model,
		));
		
	}
	

}