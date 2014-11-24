<?php

/**
 * This class is used to change passsword user
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 1.0
 */
class APIChangePasswordAction extends GNAction
{

	/**
	 * This method is used to run action
	 * @author ngocnm
	 * @return void
	 */
	public function run()
	{
		ApiAccess::allow("PUT");

		// check user
		if (currentUser()->id === -1) {
			throw new Exception(null, 403);
		}

		// get params
		$oldPassword = Yii::app()->request->getPut('old-password');
		$newPassword = Yii::app()->request->getPut('new-password');

		// validate
		Yii::import('api_app.modules.users.models.forms.APIChangePasswordForm');
		$changePasswordForm = new APIChangePasswordForm();
		$changePasswordForm->oldPassword = $oldPassword;
		$changePasswordForm->newPassword = $newPassword;
		if (!$changePasswordForm->validate()) {
			$errors = array_shift(array_values($changePasswordForm->errors));
			if (isset($errors[0])) {
				throw new Exception($errors[0], 400);
			}
		}

		// change password
		$userInfo = currentUser();
		$userInfo->changePassword($newPassword);

		// create new access-token
		$accessToken = ApiAccess::generate();

		// response
		Yii::app()->response->send(200, array(
			'access_token'	=> $accessToken,
		),Yii::t('Youlook','Change password successful.'));
	}

}