<?php
class DefaultController extends GNController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}
	
	public function actionIndex() {
		Yii::import('application.modules.contact.models.Contact');
		$model = new Contact();
		$this->render('application.modules.contact.views.contact', compact('model'));
	}
}