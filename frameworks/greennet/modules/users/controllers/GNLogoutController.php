<?php
/**
 * LogoutController - This controller is used to support member log out
 *
 * @author Thanhngt
 * @version 1.0
 * @created 23-Jan-2013 5:00:16 PM
 * @modified 29-Jan-2013 11:09:18 AM
 */
class GNLogoutController extends GNController
{
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}

	/**
	 * This action is used to log out
	 */
	public function actionIndex()
	{
		Yii::app()->user->logout();
		if ($this->isJsonRequest) {
			ajaxOut(array(
				'error'		=> false,
				'url'		=> GNRouter::createAbsoluteUrl('/'),
				'message'	=> Yii::t("greennet", 'You are logged out'),
			));
		} else {
			Yii::app()->jlbd->dialog->notify(array(
				'error'	=> false,
				'type' => 'success',
				'autoHide' => true,
				'message' => Yii::t("greennet", 'You are successfully logged in'),
			));
			$this->redirect('/');
			
		}
	}

}