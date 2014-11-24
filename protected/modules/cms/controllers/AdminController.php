<?php
class AdminController extends GNController {
	public $layout = "//themes/unicorn/views/layouts/default";
	public function allowedActions() {
		return '*';
	}
	
	public function actionIndex() {
		Yii::import('application.modules.cms.definitions.types.Article');
// 		Article::test();
		$this->render('index');
	}
	
	public function actionAdd() {
		Yii::import('application.modules.cms.definitions.types.Article');
		if (!empty($_POST)) {
			
		} else {
			$this->render('add');
		}
	}
}