<?php
//namespace \ExtendedYii\Article\Controllers\Category;
class ListController extends GNController {
	public $layout = "//themes/unicorn/layouts/default";
	public function allowedActions()
	{
		return '*';
	}
	public function actionIndex() {
		$categories = Article::model()->findAll();
		$this->render('index', compact('categories'));
	}
	
}