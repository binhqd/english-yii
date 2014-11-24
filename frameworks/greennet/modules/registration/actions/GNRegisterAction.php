<?php
/**
 * This action is used to support user login
 */
Yii::import('greennet.modules.users.models.*');
Yii::import('greennet.modules.registration.models.*');
class GNRegisterAction extends GNAction {
	public $viewFile = 'greennet.modules.registration.views.register';
	
	public $validation_uri = "/users/registration/activate/";
	public $validation_success_uri = "/profile";
	public $validation_failure_uri = "/";
	public $mailViewPath = 'greennet.modules.registration.views.mail';
	/**
	* This action is used to support Register by fill information (Register by fill Information - Step 1)
	*
	* @author Phihx
	* @date 01-02-2013
	*/
	public function run()
	{
		$controller = $this->controller;
		
		$model = new GNRegisterByInformation;
		if(isset($_POST['GNRegisterByInformation']))
		{
			$model->attributes = $_POST['GNRegisterByInformation'];
				
			// Check your email registered and activated or not?
			$controller->attachbehavior('checkEmail', 'greennet.modules.registration.behaviors.GNCheckExistingEmailBehavior');
			$controller->checkEmail->run($model->email);
			
			// If the information is posted has been added to the user temporarily, send another activation code.
			try {
				$validate = $model->validate();
			} catch (Exception $ex) {
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> true,
						'message'	=> $ex->getMessage(),
						'url'		=> GNRouter::createUrl($this->validation_failure_uri)
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> true,
						'type' 		=> 'success',
						'autoHide'	=> true,
						'message'	=> $ex->getMessage(),
					));
				
					$controller->refresh();
				}
			}
			
			if ($validate) {
				try {
					$modelTmpUser = new GNTmpUser;
						
					if ($modelTmpUser->createUser($model->attributes)) {
						$validationCode = $modelTmpUser->createValidationCode();

						$strActiveUrl = GNRouter::createAbsoluteUrl($this->validation_uri, array('code' => $validationCode));

						Yii::app()->mail->viewPath = $this->mailViewPath;

						//Notice was send activation information via email
						$message = Yii::t("greennet", '<p>Congratulations!</p>
							You’ve just completed the first step in registering as a GreenNet member.<br/>
							We’ll now send a verification email to: <b>{email}</b><br/>
							When you receive it, just click on the appropriate link or button and that’s the process complete.<br/>
							We’ll send the email immediately but it may take a little time to arrive in your inbox.', array('{email}'=>$model->email)
						);

						if ($controller->isJsonRequest) {
							ajaxOut(array(
								'error'		=> false,
								'type'		=> 'success',
								'message'	=> $message,
								'url'		=> GNRouter::createUrl($this->validation_success_uri)
							), false);
								
							// Send activation code to email
							$sendMail = Yii::app()->mail->sendMailWithTemplate($modelTmpUser->email, Yii::t("greennet", 'GreenNet membership confirmation'), 'sendMailActivationAccount',$data=array('strActiveUrl'=>$strActiveUrl, 'user'=>$modelTmpUser));
							if(!empty($validationCode) && $sendMail == false)
							$modelTmpUser->saveAttributes(array('has_sent_code'=>1));
							exit;
						} else {
							// Send activation code to email
								
							$sendMail = Yii::app()->mail->sendMailWithTemplate($modelTmpUser->email, Yii::t("greennet", 'GreenNet membership confirmation'), 'sendMailActivationAccount',$data=array('strActiveUrl'=>$strActiveUrl, 'user'=>$modelTmpUser));
							if(!empty($validationCode) && $sendMail == false)
							$modelTmpUser->saveAttributes(array('has_sent_code'=>1));

							Yii::app()->jlbd->dialog->notify(array(
								'error'		=> false,
								'type'		=> 'success',
								'autoHide'	=> true,
								'message'	=> $message
							));
							$controller->redirect('/');
						}
					} else {
						if ($controller->isJsonRequest) {
							ajaxOut(array(
								'error'		=> true,
								'type'		=> 'error',
								'message'	=> Yii::t("greennet", 'Cannot create user!'),
								'errors'	=> $modelTmpUser->errors,
							));
						} else {
							Yii::app()->jlbd->dialog->notify(array(
								'error'		=> true,
								'type'		=> 'error',
								'autoHide'	=> true,
								'message'	=> Yii::t("greennet", 'Cannot create user!')
							));
						}
					}
				} catch (Exception $ex) {
					if ($controller->isJsonRequest) {
						ajaxOut(array(
							'error'		=> true,
							'message'	=> $ex->getMessage(),
						));
					} else {
						Yii::app()->jlbd->dialog->notify(array(
							'error'		=> true,
							'type' 		=> 'success',
							'autoHide'	=> true,
							'message'	=> $ex->getMessage(),
						));
						$controller->refresh();
					}
				}
			}
		}
		$controller->render($this->viewFile, array('model'=>$model));
	}
	
	
} 