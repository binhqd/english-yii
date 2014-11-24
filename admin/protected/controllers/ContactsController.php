<?php
class ContactsController extends GNController {
	public $layout = "//themes/unicorn/layouts/default";
	public function allowedActions()
	{
		return '*';
	}
	
	public function actionIndex() {
		$this->render('admin.views.contacts.index');
	}
	
	public function actionDetail() {
		$id = Yii::app()->request->getParam('id');
		
		$this->render('detail');
	}
}