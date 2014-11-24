<?php
/**
 * This action is used to display profile home of user
 */
Yii::import('greennet.modules.users.models.*');
class GNDoChangeEmailAction extends CAction {
	public $viewFile = 'greennet.modules.users.views.profile.change_email';
	
	public function run()
	{
		$controller = $this->controller;
		
		try {
			// Check activation code
			$request = GNChangeEmailOwnershipValidation::model()->findRequestByCode($code);
			$user = GNUser::model()->getUserInfo($request->user_id);
			
			if (empty($user)) throw new Exception(Yii::t("greennet", 'User does not exists'));
			
			//if (!$user->saveAttributes(array('email'=>$email))) throw new Exception('Cannot change email');
			if (!$user->isCurrent) {
				$backUrl = GNRouter::createUrl('/profile');
				$message = Yii::t("greennet", 'You are changing email of another');
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'	=> true,
						'message'	=> $message,
						'url'		=> $backUrl
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'	=> true,
						'type'		=> 'success',
						'autoHide'	=> true,
						'message'	=> $message,
					));
					$controller->redirect($backUrl);
				}
			}
			
			$user->email = $request->new_email;
			$user->saveAttributes(array('email'));
			
			GNChangeEmailOwnershipValidation::model()->cleanOldRequest($request->user_id);
			
			// $user->forceLogin();
			
			$backUrl = GNRouter::createUrl('/profile');
			$message = sprintf(Yii::t("greennet", 'Your email has successfully changed to %s'), $request->new_email);
			
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'	=> false,
					'message'	=> $message,
					'url'		=> $backUrl
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error'		=> true,
					'type'		=> 'success',
					'autoHide'	=> true,
					'message'	=> $message,
				));
				$controller->redirect($backUrl);
			}
		} catch (Exception $ex) {
			$backUrl = GNRouter::createUrl('/profile');
			
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'	=> true,
					'url' => GNRouter::createAbsoluteUrl($backUrl),
					'message'	=> $ex->getMessage(),
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error'	=> true,
					'type' => 'error',
					'autoHide' => true,
					'message' => $ex->getMessage(),
				));
				$controller->redirect($backUrl);
			}
		}
		
	}
} 