<?php
/**
 * This action is used to display profile home of user
 */
Yii::import('greennet.modules.users.models.*');
class GNConfirmChangeEmailAction extends CAction {
	public $viewFile = 'greennet.modules.users.views.profile.change_email';
	public $mailViewPath = 'greennet.views.mail_templates.profile';
	
	public function run()
	{
		$controller = $this->controller;
		
		$model = New GNChangeEmailForm();
		
		if (!empty($_POST['GNChangeEmailForm'])) {
			$model->setAttributes($_POST['GNChangeEmailForm'], false);

			// Validate form change email
			try {
				$validate = $model->validate();
			} catch (Exception $ex) {
				if($controller->isJsonRequest) {
					ajaxOut(array(
						'error' => true,
						'message'	=> $ex->getMessage()
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'	=> true,
						'type'=>'error',
						'autoHiden'=> true,
						'message'=>$ex->getMessage()
					));
				}
			}
			
			try {
				//$request = GNChangeEmailValidation::model()->findRequestByCode($code);
				//$user = GNUser::model()->getUserInfo($request->user_id);
				
				// Clean up old request
				GNChangeEmailValidation::model()->cleanOldRequest(currentUser()->id);
				GNChangeEmailOwnershipValidation::model()->cleanOldRequest(currentUser()->id);
				
				//create code
				$obj = new GNChangeEmailOwnershipValidation;
				$obj->new_email = $model->email;
				$codeChangeEmail = $obj->createCode(currentUser()->id);
				
				if (empty($codeChangeEmail)) throw new Exception(Yii::t("greennet", 'Cannot create confirmation code'));

				// Send confirmation mail
				Yii::app()->mail->viewPath = $this->mailViewPath;
			
				$link = GNRouter::createAbsoluteUrl('/profile/do_change_email', array(
					'email'	=>	$model->email,
					'code'	=>	$codeChangeEmail,
				));
				$sendTo		= $model->email;
				$subject	= Yii::t("greennet", 'Confirm email ownership');
				$message	= $link;
				$view 		= 'active_change_email';
				$data 	= array(
					'sendTo'	=> $sendTo,
					'subject'	=> $subject,
					'message'	=> $message,
				);
				
				$backUrl = GNRouter::createUrl('/profile');
				$msg = sprintf(Yii::t("greennet", 'The system has sent an email to %s for confirming about email ownership.<br/>Please check %s to continue.'), $model->email, $model->email);
				
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error' => false,
						'url' => $backUrl,
						'message' => $msg
					), false);
					
					Yii::app()->mail->sendMailWithTemplate($sendTo, $subject, $view, $data);
					
					exit;
				} else {
					
					Yii::app()->mail->sendMailWithTemplate($sendTo, $subject, $view, $data);
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> true,
						'type'		=> 'success',
						'autoHide'	=> true,
						'message'	=> $msg
					));
					watch(array($sendTo, $subject, $view, $data));
					$controller->redirect($backUrl);
				}
			} catch (Exception $ex) {
				$backUrl = GNRouter::createUrl('/profile');
				
				if($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> true,
						'message'	=> $ex->getMessage(),
						'url'		=> $backUrl
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> true,
						'type'		=> 'error',
						'autoHiden'	=> true,
						'message'	=> $ex->getMessage()
					));
					$controller->redirect($backUrl);
				}
			}
		}
		
		$controller->render($this->viewFile, array(
			'model'=>	$model,
		));
	}
} 