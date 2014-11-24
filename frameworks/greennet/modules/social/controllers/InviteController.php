<?php
Yii::import('application.modules.validation_codes.models.*');
class InviteController extends GNController
{
	public $layout = '//layouts/homepage';

	public function allowedActions() {
		return '*';
	}
	
	/**
	 * This method is used to activate an request invite our beta launch
	 * @param unknown_type $key
	 * @param unknown_type $email
	 */
	public function actionActive($key = NULL, $email = NULL){
		if(!isset($key) || !isset($email)) {
			Yii::app()->user->setFlash('warning', Yii::t("greennet", 'Code or email is invalid.'));
			$this->redirect('/');
		}
		
		$checkCode = ValidationCode::isCodeValidate($key);

		if(!$checkCode) {
			Yii::app()->user->setFlash('warning', Yii::t("greennet", 'Code is expired or invalid.'));
			$this->redirect('/');
		}
		$activeEmail = ActiveMail::model()->isInvited($key, $email);

		if(!empty($activeEmail)){
			//check receiver email exists
			
			$currentUser = GNUser::model()->getUserInfoByEmail($activeEmail->receiver,false);
			
			Yii::app()->session['invite_code'] = $key;
			Yii::app()->session['receiver_id'] = $email;
			
			if(!empty($currentUser)){
				if(currentUser()->isGuest) {
					Yii::app()->user->setFlash('warning', Yii::t("greennet", 'This email has been existed in system. Please login to use!'));
				}
				$this->redirect(Yii::app()->createUrl("/?key={$key}&email={$activeEmail->receiver}&isExist=true"));
				
			}else{
				
				$this->redirect(Yii::app()->createUrl("/?key={$key}&email={$activeEmail->receiver}"));
			}
			
		} else {
			Yii::app()->user->setFlash('warning', Yii::t("greennet", 'Active code not found.'));
			$this->redirect('/');
		}
	}
	
	/**
	 * This method is used to request an invite our beta launch
	 */
	public function actionIndex() {
		if(!empty($_POST)){
			$email = $_POST['email'];
			
			if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email)) {
				$arrEmails = array('0'=>$email);
			} else {
				jsonOut(array(
					'error'		=> true,
					'status'	=> 1,
					'message'	=> Yii::t("greennet", 'Email is wrong format.')
				));
			}
			
			$existEmail = GNUser::model()->findByAttributes(array(
				'email'	=> $email
			));
			
			if(!empty($existEmail)) {
				jsonOut(array(
					'error'		=> true,
					'message'	=> Yii::t("greennet", 'Email has been exist in system.')
				));
			}
			
			$saveActive = ActiveMail::model()->saveActiveMail(array(
				'sender'	=> null,
				'receiver'	=> $email,
			));	
			
			$type = false;
			if (!$saveActive) {
				$type= true;
				$msg = Yii::t("greennet", 'Email has been invited.');
			} else {
				$msg = Yii::t("greennet", 'User has been invited to join Greennet');
			}
			
			$debug = array(
				'error'		=>$type,
				'message'	=>$msg,
				'receiver'	=>$email,
			);
			jsonOut($debug,false);
		}
	}
}
