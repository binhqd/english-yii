<?php

class ResendEmailController  extends GNController
{
	
	public $layout = "//layouts/master/homepage";

	
	/**
	* This method is used to allow action
	* @return string
	*/
	public function allowedActions()
	{
		return '*';
	}
	
	
	
	public function actionActivationAccount(){
		if(currentUser()->isGuest && currentUser()->isAwaiting) jsonOut(array('error'=>true,'message'=>'This content is currently unavailable.'));
			
		
		
		Yii::app()->mail->viewPath = 'application.views.mail';
		
		$code = GNRegistrationValidation::model()->find('user_id=:user_id', array(
			':user_id'=>currentUser()->id
		));
		if(!empty($code)) $registrationValidation = GNRegistrationValidation::model()->deleteCode($code->code);

		$validationCode = GNRegistrationValidation::model()->createCode(currentUser()->id);
		
		$strActiveUrl = GNRouter::createAbsoluteUrl("/users/registration/activate/", array('code' => $validationCode));
		
		$jsonEmail = array(
			'email'=>currentUser()->email,
			'strActiveUrl'=>$strActiveUrl,
			'user'=>currentUser()
		);
		jsonOut(array(
			'error'=>false,
			'message'=>"A confirmation code was sent to <b>".currentUser()->email."</b>. To confirm your account, please check your email inbox."
		),false);

		$sendMail = Yii::app()->mail->sendMailWithTemplate(currentUser()->email, 
							UsersModule::t('Youlook membership confirmation'), 
							'sendMailActivationAccount',
							$data=array('strActiveUrl'=>$strActiveUrl, 'user'=>currentUser()));
	
	
	
	}
	
	
}