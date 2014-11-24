<?php
//namespace \ExtendedYii\Article\Controllers\Category;
class CategoriesController extends GNController {
	public $layout = "//themes/unicorn/layouts/default";
	public function allowedActions()
	{
		return '*';
	}
	public function actionIndex() {
		$categories = ArticleCategory::model()->findAll();
		$this->render('index', compact('categories'));
	}
	
	public function actionCreate() {
		$category = new ArticleCategory();
		if (!empty($_POST)) {
// 			dump($_POST['ArticleCategory']);
			$category->name = $_POST['ArticleCategory']['name'];
			$category->description = $_POST['ArticleCategory']['description'];
			if ($category->save()) {
				$this->redirect(GNRouter::createUrl('/articles/categories'));
			}
		} else {
			$this->render('create', compact('category'));
		}
// 		exit('a');
	}
}