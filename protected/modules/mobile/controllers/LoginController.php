<?php
class LoginController extends JLController {
	public function allowedActions() {
		return '*';
	}
	
	public function actionLogin()
	{
		$model = new JLLoginForm;
		if (!empty($_POST['User']))
		{
			$model->username = $_POST['User']['username'];
			$model->password = $_POST['User']['password'];
			$model->rememberMe = false;
			
			if ($model->validate()) {
				jsonOut(array(
					'error' => true,
					'msg' => 'Login successful.'
				));
			} else {
				$msg = '';
				$errors = $model->getErrors();
				foreach ($errors as $error) {
					foreach ($error as $key => $val) {
						$msg .= $val . '<br/>';
					}
				}
				jsonOut(array(
					'error' => false,
					'msg' => $msg,
				));
			}
		} else
		{
			jsonOut(array('error' => true, 'msg' => 'Invalid request'));
		}
	}
}
