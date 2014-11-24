<?php
class AuthController extends JLController
{
	public function allowedActions() {
		return '*';
	}
	
	function actionLogin() {
		// check if request is post
		
		$model = new JLLoginForm;
		
		$msg = array(
			"validate"		=> false,
			"sessionid"	=> '',
			'username'		=> '',
			'displayname'		=> ''
		);
		// collect user input data
		if (isset($_POST['Login'])) {
			$model->attributes = $_POST['Login'];
			// validate user input and redirect to the previous page if valid
			if ($model->validate() && $model->login()) {
				$msg = array(
					"validate"		=> true,
					"sessionid"	=> Yii::app()->session->sessionID,
					"username"		=> $model->username,
					"displayname"		=> $model->displayname
				);
				
			}
		}
		
		jsonOut($msg);
	}
}