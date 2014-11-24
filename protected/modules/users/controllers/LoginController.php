<?php
Yii::import('greennet.modules.users.models.*');

class LoginController extends GNController {

	const TYPE_LOGIN_SUCCESS = 1;
	const TYPE_VALIDATE_FAILURE = 2;
	const TYPE_USER_PASS_INCORRECT = 3;

	public $layout = "//layouts/login";

	public function allowedActions() {
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

	public function actionIndex() {
		$returnUrl = '/';
		if (!empty($_POST['returnURL'])) {
			$returnUrl = $_POST['returnURL'];
		} else if (!empty(Yii::app()->request->urlReferrer)) {
			$returnUrl = Yii::app()->request->urlReferrer;
		}
		
		// FIXME: Hard code to force user to login
		$returnUrl = '/dashboard';
		$model = new ZoneLoginForm();

		if (!currentUser()->isGuest) {
			Yii::app()->controller->redirect('/');
		}
		
		$isAjaxLogin = isset($_POST['ajax']) && $_POST['ajax'] === 'userLoginForm';
		if($isAjaxLogin){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if (isset($_POST['ZoneLoginForm'])) { // Check Post Form
			$model->attributes = $_POST['ZoneLoginForm'];
			
			if ($model->validate()) { // Validate form login
				$login = $model->user->forceLogin($model->rememberMe);

				if ($login) {
					if ($isAjaxLogin) {
						ajaxOut(array('id' => currentUser()->hexID) + currentUser()->attributes);
					}
					$this->redirect($returnUrl);
				}
			} elseif ($isAjaxLogin) {
				ajaxOut(array(
					'ZoneLoginForm_password' => array(
						'Your email or password does not exist in our system.'
					)
				));
			}
		}
		$this->render('login', array(
			'model'		=> $model,
			'returnUrl'	=> $returnUrl
		));
	}

}