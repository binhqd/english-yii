<?php

Yii::import('application.modules.social.models.*');
class InviteFriendController extends GNController
{
	public $layout = '//layouts/homepage';

	public function allowedActions() {
		return '*';
	}
	
	/**
	 * This method is used to active invite friend
	 * @param unknown_type $key
	 * @param unknown_type $email
	 */
	public function actionActive($key=NULL, $email=NULL){
		
		$activeEmail = ActiveMail::model()->findByAttributes(array(
			'keycode'=>$key,
			'receiver'=>$email,
		));
		// dump($activeEmail);
		if(!empty($activeEmail)){
			//check receiver email exists
			
			$currentUser = GNUser::model()->getUserInfoByEmail($activeEmail->receiver,false);
			
			if(!empty($currentUser)){
				Yii::app()->user->setFlash('warning', Yii::t("greennet", 'Email is exist in system. Please login to use!'));
				$this->redirect(Yii::app()->createUrl("/?key={$key}&email={$activeEmail->receiver}"));
				
			}else{
				
				$this->redirect(Yii::app()->createUrl("/?key={$key}&email={$activeEmail->receiver}"));
			}
			
		}else{
			Yii::app()->user->setFlash('warning', Yii::t("greennet", 'Active code not found.'));
			$this->redirect('/');
		}
		
		
	}
	
	/**
	 * This method is used to view all form to request invite friend
	 */
	public function actionIndex($email = null) {
		if(!empty($email)){
			$strRandom = uniqid ();
			Yii::app()->session['active-url'] = $strRandom;
			
			InviteFriend::model()->saveInviteEmail(array(
				'sender'	=> currentUser()->email,
				'receiver'	=> $email,
				'keycode'	=> $strRandom,
			));
			
			$debug = array(
				'error'=>false,
				'message'=>Yii::t("greennet", 'User has been invited to join Slidelane'),
				'receiver'=>$email,
				'keycode'=>$strRandom,
			);
			jsonOut($debug, false);
			
			/* $sendMail = GNMailer::sendMailWithTemplate($email,'You have been invited to join Slidelane - user has been invited to join Slidelane','invite_friend',array(
				'email'=>$email,
				'strRandom'=>$strRandom,
			)); */
		} else {
			$this->render('index', array(
					
			));
		}
	}
	
	/**
	 * This method is used to load view invite friend
	 * @param unknown_type $fileName
	 */
	public function actionLoadViewInviteFriend($fileName = NULL) {
		if (isset($fileName)) {
			$this->renderPartial('/partials/'.$fileName);
		}
	}
}
