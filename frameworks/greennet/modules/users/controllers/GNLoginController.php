<?php
/**
 * LoginController - This controller is used to contain actions support for login
 *
 * @author Thanhngt
 * @version 1.0
 * @created 23-Jan-2013 4:59:44 PM
 * @modified 29-Jan-2013 11:09:18 AM
 */
class GNLoginController extends GNController
{
	/**
	 * This method is used to allow action
	 * @return string
	 */
	
	public function allowedActions()
	{
		return '*';
	}
	
	public function actions(){
		return array(
			'index'	=> array(
				'class'			=> 'greennet.modules.users.actions.login.GNLoginAction',
				'redirect_uri'	=> GNRouter::createUrl('/profile')
				//'viewFile'	=> 'login'
			)
		);
		
	}
	
}