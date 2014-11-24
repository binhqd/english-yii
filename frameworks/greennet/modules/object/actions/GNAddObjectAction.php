<?php
class GNAddObjectAction extends GNAction {
	
	public $viewFile = 'greennet.modules.object.views.add';
	public $model;
// 	public $redirect_uri = '/';
	public function run() {
		$controller = $this->getController();
		
		$class = $this->model;
		$model = new $class;
		
		if (isset($_POST[$this->model])) // Check Post Form
		{
			$model->setAttributes($_POST[$this->model], false);
			
			if ($model->validate()) // Validate form
			{
				$model->save();
				
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
		}
		
		$controller->render($this->viewFile, array('model' => $model));
	}
} 