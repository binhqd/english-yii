<?php
/**
 * This action is used to display profile home of user
 */
Yii::import('greennet.modules.users.models.*');
class GNChangePasswordAction extends CAction {
	public $viewFile = 'greennet.modules.users.views.profile.changepass';
	public $redirect_uri = '/profile';
	
	public function run()
	{
		$controller = $this->controller;

		$hasCreatedPassword = true;
		Yii::import('greennet.modules.social.models.GNLinkedAccount');
		$linkedAccount = GNLinkedAccount::model()->findByAttributes(array('user_id'=>currentUser()->id));
		if (!empty($linkedAccount)) {
			$hasCreatedPassword = $linkedAccount->has_created_password == 1;
		}

		if ($hasCreatedPassword)
			$model = new GNChangePasswordForm('fullchange');
		else
			$model = new GNChangePasswordForm;

		if (isset($_POST['GNChangePasswordForm'])) {
			$model->attributes = $_POST['GNChangePasswordForm'];
			// Validate 
			// Check validate user and userProfile
			try {
				$valid = $model->validate();
			} catch (Exception $ex) {
				if ($controller->isJsonRequest) {
					$url = GNRouter::createUrl($this->redirect_uri);
					ajaxOut(array(
						'error'		=> true,
						'message'	=> $ex->getMessage(),
						'url'		=> $url
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

			if ($valid) {
				// Change password
				$changePassword = currentUser()->changePassword($model->password);
				if (!$changePassword) throw new Exception(Yii::t("greennet", 'Cannot change password'));

				if (!$hasCreatedPassword) {
					$linkedAccount->has_created_password = 1;
					$linkedAccount->save();
				}

				// notify by ajax change password success
				$msg = $hasCreatedPassword ? Yii::t("greennet", 'Your password has been changed successful.') : Yii::t("greennet", 'Your password has been created successful.');
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> false,
						'url'		=> GNRouter::createUrl($this->redirect_uri),
						'message'	=> $msg,
					));
				} else {
					//notify change password success
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> false,
						'type'		=>'success',
						'autoHiden'	=> true,
						'message'	=> $msg
					));
					$controller->redirect(GNRouter::createUrl($this->redirect_uri));
				}
			} else {
				$msg = Yii::t("greennet", "Can't validate your information.");
				
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> true,
						'message'	=> $msg,
						'url'		=> GNRouter::createUrl($this->redirect_uri),
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> true,
						'type' 		=> 'error',
						'autoHide'	=> true,
						'message'	=> $msg
					));
				
					$controller->refresh();
				}
			}
		}
		$controller->render($this->viewFile, array(
			'model'				=> $model,
			'hasCreatedPassword'=> $hasCreatedPassword,
		));
	}
} 