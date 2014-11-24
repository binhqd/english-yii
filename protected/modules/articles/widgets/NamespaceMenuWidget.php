<?php
class NamespaceMenuWidget extends CWidget {
	public $namespaceID;
	public $followingObjectType = 'object';
	public $targetNode = true;
	public $viewPath = 'namespace-articles';
	public $pages = null;
	public function init() {

		
	}
	public function run() {
		
		$isUser = ZoneUser::isUser(IDHelper::uuidToBinary($this->namespaceID));
		if($isUser)
			$totalArticle = ZoneArticle::model()->countArticlesByUserID(IDHelper::uuidToBinary($this->namespaceID));
		else 
			// $totalArticle = ZoneActivity::model()->countActivities(IDHelper::uuidToBinary($this->namespaceID),ZoneActivity::OBJECT_TYPE_ARTICLE);
			$totalArticle = ZoneArticle::model()->countArticlesByObject(IDHelper::uuidToBinary($this->namespaceID));

		$totalImages = ZoneResourceImage::model()->countImages(IDHelper::uuidToBinary($this->namespaceID));
		
		
		$this->render($this->viewPath, array(
			'totalArticle'=>$totalArticle,
			'totalImages'=>$totalImages,
			'namespaceID'=>$this->namespaceID,
			'followingObjectType'=>$this->followingObjectType,
		));
		

	}
}