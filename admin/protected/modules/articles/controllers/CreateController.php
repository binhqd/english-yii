<?php
//namespace \ExtendedYii\Article\Controllers\Category;
class CreateController extends GNController {
	public $layout = "//themes/unicorn/layouts/default";
	public $defaultAction = "create";
	public function allowedActions()
	{
		return '*';
	}
	public function actionCreate() {
		$article = new Article();
		
		if (!empty($_POST)) {
			$article->title = $_POST['Article']['title'];
			$article->description = $_POST['Article']['description'];
			$article->content = $_POST['Article']['content'];
			if ($article->save()) {
				$binArticleId = $article->id;
				
				foreach ($_POST['Article']['ArticleCategory'] as $item) {
					$middle = new ArticlesCategory();
					$middle->category_id = IDHelper::uuidToBinary($item);
					$middle->article_id = $binArticleId;
					
					$middle->save();
				}
				$this->redirect(GNRouter::createUrl('/articles/create'));
			} else {
				exit('fail');
			}
			
		} else {
			// Get categories
			$categories = ArticleCategory::model()->criteria('getall')
				->select('id, name')
				->cache('article.categories.all')
				->toArray(function($record) {
					$attr = $record->attributes;
					$attr['id'] = IDHelper::uuidFromBinary($record->id, true);
					return $attr;
				});
			
			$this->render('create', compact('article', 'categories'));
		}
	}
	
}