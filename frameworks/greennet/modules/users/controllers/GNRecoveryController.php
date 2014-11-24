<?php
/**
 * RecoveryController - This controller is used to support recovery (forgot password)
 *
 * @author Ngocnm
 * @version 1.0
 * @created 01-Feb-2013
 * @modified
 */
class GNRecoveryController extends GNController
{
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}

	public function actions() {
		return array(
			'forgot_password'	=> array(
				'class'			=> 'greennet.modules.users.actions.forgot_password.GNRequestPasswordAction',
				//'viewFile'	=> 'login'
			),
			'change_password'	=> array(
				'class'			=> 'greennet.modules.users.actions.forgot_password.GNRecoverPasswordAction',
				//'viewFile'	=> 'login'
			)
		);
	}
}