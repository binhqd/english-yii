<?php
/**
 * This action is used to display profile home of user
 */
Yii::import('greennet.modules.users.models.*');
class GNChangeEmailAction extends CAction {
	public $viewFile = 'greennet.modules.users.views.profile.change_email';
	public $mailViewPath = 'greennet.views.mail_templates.profile';
	
	public function run()
	{
		$controller = $this->controller;
		
		//$model = new GNForgotPasswordForm();
		
		try {
			// Clean up old request
			GNChangeEmailValidation::model()->cleanOldRequest(currentUser()->id);
			
			//create code
			$codeChangeEmail = GNChangeEmailValidation::model()->createCode(currentUser()->id);
			
			if (empty($codeChangeEmail)) throw new Exception(Yii::t("greennet", 'Cannot create confirmation code'));
			
			// Send confirmation mail
			Yii::app()->mail->viewPath = $this->mailViewPath;
			
			$strLink = GNRouter::createAbsoluteUrl('profile/confirm_change_email',array(
				'code' 	=> $codeChangeEmail,
			));
			
			$sendTo = currentUser()->email;
			$subject = Yii::t("greennet", "Change email request confirmation");
			$message = $strLink;
			$view = 'tmp_change_email';
			$data = array(
				'sendTo'	=> $sendTo,
				'subject'	=> $subject,
				'message'	=> $message
			);
			$messageNotify = Yii::t("greennet", 'The system has sent you a confirmation email for confirming change email.<br/>Please check your email to continue.');
			
			$backUrl = GNRouter::createUrl('/profile');
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'	=> false,
					'message'	=> $messageNotify,
					'url'		=> $backUrl
				), false); //muc dich cua false : khong dung,chay tiep.
				Yii::app()->mail->sendMailWithTemplate($sendTo, $subject, $view, $data);
			} else {
				Yii::app()->mail->sendMailWithTemplate($sendTo, $subject, $view, $data);
				Yii::app()->jlbd->dialog->notify(array(
					'error'	=> false,
					'type'		=>	'success',
					'autoHide'	=>	true,
					'message'	=>	$messageNotify,
				));
				$controller->redirect($backUrl);
			}
		} catch (Exception $ex) {
			$backUrl = GNRouter::createUrl('/profile');
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error' => true,
					'message' => Yii::t("greennet", $ex->getMessage()),
					'url'		=> $backUrl
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error' => true,
					'type'		=>	'error',
					'autoHide'	=>	true,
					'message'	=>	Yii::t("greennet", $ex->getMessage()),
				));
				$controller->redirect($backUrl);
			}
		}
	}
} 