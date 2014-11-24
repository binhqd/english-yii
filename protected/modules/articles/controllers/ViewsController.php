<?php

class ViewsController extends ZoneController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public $layout = "//layouts/master/myzone";
	public function allowedActions()
	{
		return '*';
	}
	
	public function actions(){
		return array(
			
		);
	}
	
	public function actionIndex() {
		if(Yii::app()->request->isAjaxRequest){
			$this->layout = "//layouts/master/ajax";
			$this->renderHtml = true;
		}
		$nodeId = Yii::app()->request->getParam('id',null);
		if($nodeId == null) throw new CHttpException(Yii::t('yii', "Sorry, this page isn't available"));
		$keywordArticle = Yii::app()->request->getParam('keywordArticle',null);
		
		$isUser = ZoneUser::isUser(IDHelper::uuidToBinary($nodeId));
		
		
		if($isUser)
			$articles = ZoneArticle::model()->getArticlesByObject(IDHelper::uuidToBinary($nodeId),null,$keywordArticle,9);
		else $articles = ZoneArticle::model()->getArticlesByObject(null,IDHelper::uuidToBinary($nodeId),$keywordArticle,9);
		
		
		// dump($node);
		
		$this->render('index',array(
			'isUser'=>$isUser,
			'articles'=>$articles,
			'nodeId'=>$nodeId,
			'keywordArticle'=>$keywordArticle,
			
		));
		
		
	}
}