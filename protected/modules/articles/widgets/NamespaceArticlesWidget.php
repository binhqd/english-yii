<?php
class NamespaceArticlesWidget extends CWidget {
	public $namespaceID;
	public $viewPath = 'namespace-articles';
	public function init() {
// 		GNAssetHelper::init(array(
// 			'image'		=> 'img',
// 			'css'		=> 'css',
// 			'script'	=> 'js',
// 		));
// 		$this->assetUrl = GNAssetHelper::setBase($this->assetPath);
		
	}
	public function run() {
		Yii::import('application.modules.articles.models.*');
		// $articles = ZoneArticleNamespace::model()->getArticles(IDHelper::uuidToBinary($this->namespaceID));
		// $articles = ZoneArticle::model()->getArticlesByObject(null,IDHelper::uuidToBinary($this->namespaceID),!empty($_GET['keywordArticles']) ? $_GET['keywordArticles'] : null);
		$activities = ZoneActivity::getActivities(null,true,IDHelper::uuidToBinary($this->namespaceID),null,ZoneActivity::OBJECT_TYPE_ARTICLE,10);
		
		
		
		$this->render($this->viewPath, array(
			'activities'=>$activities['data'],
			'pages'=>$activities['pagination'],
		));
		
// 		if ($this->ref != "") {
// 			$items = $this->_model->findAllByAttributes(array(
// 				'object_id'	=> $this->ref
// 			));
// 		} else {
// 			$items = array();
// 		}
		
// 		$this->render('gallery', array(
// 			'items'	=> $items
// 		));
	}
}