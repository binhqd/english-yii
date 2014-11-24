<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiForgotPasswordAction extends GNAction {

	protected $_runtime = array();

	/**
	 * This method is used to run action
	 */
	public function run($email = '') {
		$this->controller->out(200, $this->retrieve($email), false);
		$this->delivery();
	}

	/**
	 * This action is used to get list of videos of an user
	 */
	public function retrieve($email = '') {
		if (!currentUser()->isGuest) {
			$this->controller->redirect('/user/profile');
		}
		Yii::import('greennet.modules.users.models.*');

		$model = new ZoneForgotPasswordForm();
		$model->attributes = array('email' => $email);
		// Check user has already exists
		if (!$model->validate()) {
			throw new Exception(UsersModule::t('The email is invalid') , 400);
		}
		$modelUser = GNUser::model()->findByEmail($model->email);
		if (empty($modelUser)) {
			throw new Exception(UsersModule::t('This email doesn\'t exists in our database'));
		}
		GNForgotPasswordValidation::model()->cleanOldRequest($model->email);
		//create code
		$codeForgotPassword = GNForgotPasswordValidation::model()->createCode($model->email);
		if (empty($codeForgotPassword)) {
			throw new Exception(UsersModule::t('Cannot create confirmation code'));
		}
		// url user cofirm mail
		$url = GNRouter::createAbsoluteUrl('/recover/change_password', array('code' => $codeForgotPassword));
		$subject = UsersModule::t('Password recovery');
		$this->_runtime = array(
			'to' => $model->email,
			'subject' => $subject,
			'data' => array(
				'email' => $model->email,
				'subject' => $subject,
				'codeForgotPassword' => $codeForgotPassword,
				'url' => $url,
				'user' => $modelUser,
				'profile' => $modelUser->profile,
		));

		return array(
			'message' => UsersModule::t("Thank you!<br>We've sent an email to <b>{email}</b>.<br>
					Please check your spam folder if the email doesn't appear within a few minutes.", array('{email}' => $modelUser->email)));
	}

	public function delivery() {
		if (empty($this->_runtime)) {
			return false;
		}
		Yii::app()->mail->viewPath = 'application.views.mail';
		Yii::app()->mail->sendMailWithTemplate($this->_runtime['to']
				, $this->_runtime['subject'], 'sendMailForgotPassword', $this->_runtime['data']);
		$this->_runtime = array();
		return true;
	}

}