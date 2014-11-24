<?php
Yii::import('greennet.modules.users.models.*');
class ChangePasswordController extends GNController
{
	public $viewFile = 'greennet.modules.users.views.forgot_password.changePassword';
	
	public $mailViewPath = 'application.views.mail';
	
	public $layout = "//layouts/master/homepage";
	public function allowedActions()
	{
		return '*';
	}
	// public function actions(){
		// return array(
			
			// 'index'	=> array(
				// 'class'			=> 'greennet.modules.users.actions.login.GNLoginAction',
				// 'viewFile'	=> 'application.modules.users.views.login.login',
			// ),

		// );
	// }

	public function actionIndex()
	{
	
		
		$this->render('index');
		
	}
	

}