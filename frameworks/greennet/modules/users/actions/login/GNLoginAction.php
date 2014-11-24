<?php
/**
 * This action is used to support user login
 */
Yii::import('greennet.modules.users.models.*');
class GNLoginAction extends GNAction {
	const TYPE_LOGIN_SUCCESS = 1;
	const TYPE_VALIDATE_FAILURE = 2;
	const TYPE_USER_PASS_INCORRECT = 3;
	
	public $viewFile = 'greennet.modules.users.views.login.login';
	public $redirect_uri = '/';
	public function run($email = null, $returnUrl = null) {
		$controller = $this->getController();
		
		// Check user has logged in
		if (currentUser()->id != -1) {
			if ($controller->isJsonRequest)
				ajaxOut(array(
					'error'		=> false,
					'code'		=> self::TYPE_LOGIN_SUCCESS,
					'url'		=> GNRouter::createAbsoluteUrl(!empty($returnUrl) ? $returnUrl : '/'),
					'message'	=> Yii::t("greennet", 'You already logged in'),
				));
			else
				$controller->redirect('/');
		}
		
		$model = new GNLoginForm();
	
		if (isset($_POST['GNLoginForm'])) // Check Post Form
		{
			$model->attributes = $_POST['GNLoginForm'];
			//Yii::app()->session['emailRequestLogin'] = $model->email; // support for forgot password
			
			try {
				if ($model->validate()) // Validate form login
				{
					$url = $this->redirect_uri;
					if (strpos(Yii::app()->user->returnUrl, '/index.php') === false) {
						$url = Yii::app()->user->returnUrl;
					}
					if (!empty($returnUrl))
						$url = $returnUrl;
		
					$login = $model->user->forceLogin($model->rememberMe);
					if ($login) {
	// 					unset(Yii::app()->session['emailRequestLogin']);
						if ($controller->isJsonRequest) {
							ajaxOut(array(
								'error'	=> false,
								'code'	=> self::TYPE_LOGIN_SUCCESS,
								'message'	=> Yii::t("greennet", 'You are successfully logged in'),
								'url'	=> $url
							));
						} else {
							Yii::app()->jlbd->dialog->notify(array(
								'error'	=> false,
								'type' => 'success',
								'autoHide' => true,
								'message' => Yii::t("greennet", 'You are successfully logged in'),
							));
							$controller->redirect($url);
						}
					}
				} else {
					if ($controller->isJsonRequest) {
						ajaxOut(array(
							'error'	=> true,
							'code'	=> self::TYPE_VALIDATE_FAILURE,
							'message'	=> Yii::t("greennet", "Your email or password is invalid"),
						));
					} else {
						Yii::app()->jlbd->dialog->notify(array(
							'error'	=> true,
							'type' => 'error',
							'autoHide' => true,
							'message' => Yii::t("greennet", "Your email or password is invalid"),
						));
					}
				}
			} catch (Exception $ex) {
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'	=> true,
						'code'	=> self::TYPE_VALIDATE_FAILURE,
						'message'	=> $ex->getMessage(),
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'	=> true,
						'type' => 'error',
						'autoHide' => true,
						'message'	=> $ex->getMessage(),
					));
				}
			}
		} else {
			if ($email != null) $model->email = $email;
		}
		
		$controller->render($this->viewFile, array('model' => $model));
	}
} 