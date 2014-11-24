<?php
class ViewArticleAction extends GNAction {
	public $requestType;
	public $pathIndex	= 'application.modules.articles.views.viewArticle.index';
	public $getRelatedNodes = true;
	public $getRelatedArticles = true;
	
	public function run()
	{
		$controller = $this->controller;
		
		if (!isset($_GET['article_id'])) {
			$strMessage = "Invalid article ID";
			$backUrl = GNRouter::createUrl('/');

			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'		=> true,
					'type'		=> 'error',
					'message'	=> $strMessage,
					'url'		=> $backUrl,
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error'		=> true,
					'type'		=> 'error',
					'autoHide'	=> true,
					'message'	=> $strMessage,
				));
				
				$controller->redirect('/');
			}
		}
		
		// Check if article ID is an ID of valid article
		Yii::import('application.modules.articles.models.*');
		$binArticleID = IDHelper::uuidToBinary($_GET['article_id']);
		$article = ZoneArticle::model()->get($_GET['article_id'], array(
			'loadPoster'	=> true,
			'loadLikes'		=> true, 
			'loadComments'	=> 5
		));
		
		if (empty($article) || $article['data_status'] == ZoneArticle::DATA_STATUS_DELETED) {
			throw new CHttpException(Yii::t('yii', "Sorry, this page isn't available"));
		}
		
		if (empty($article['author']['id'])) {
			$model = ZoneArticle::model()->findByPk($binArticleID);
			$model->invalid = 1;
			$model->data_status = ZoneArticle::DATA_STATUS_DELETED;
			$model->save();
			throw new CHttpException(Yii::t('yii', "Sorry, this page isn't available"));
		}
		// if empty namespace, move to userpage
		if (empty($article['namespace'])) {
			$controller->redirect("/profile/{$article['author']['username']}?action=article-detail&a_id={$article['id']}");
		} else {
			$user = ZoneUser::model()->getUserInfo(IDHelper::uuidFromBinary($article['namespace']['holder_id'], true));
			
			/* Chu Tieu */
			if(!empty($article['node']['isUserNode']) && $article['node']['isUserNode']){
				$user = ZoneUser::model()->get($article['node']['zone_id']);
				$controller->redirect("/profile/{$user['username']}?action=article-detail&a_id={$article['id']}" );
			}
			/* End : Chu Tieu */
			// $category = array_keys(ZoneNodeRender::getCategories($article['node']['zone_id']));
			
			if (empty($user) || $user->id == -1) {
				$node = ZoneInstanceRender::get($article['namespace']['holder_id'])->toArray(true);
				$controller->redirect("/node?id={$node['zone_id']}&action=article-detail&a_id={$article['id']}");
			} else {
				$controller->redirect("/profile/{$user['username']}?action=article-detail&a_id={$article['id']}" );
			}
		}
		// check if namespace
		// $user = ZoneUser::model()->get($);
		
		Yii::import('application.modules.resources.models.ZoneResourceImage');
		$criteria = new CDbCriteria();
		$criteria->condition = "object_id=:object_id";
		$criteria->limit = 5;
		
		// 		$criteria->select = "id, name";
		$criteria->params = array(':object_id' => $_GET['article_id']);
		$criteria->order = "created desc";
		
		$images = ZoneResourceImage::model()->findAll($criteria);
		
		$authorData = ZoneArticleAuthor::model()->find('article_id=:article_id', array(
			':article_id'	=> $article->id
		));
		
// 		if (empty($authorData)) {
			
// 		}
		
		$author = ZoneUser::model()->getUserInfo($authorData->holder_id);
		$node = null;
		$relatedArticles = array();
		if(!empty($article->namespace)){
			if ($this->getRelatedNodes)
				$node = $article->namespace->nodeToolBar(IDHelper::uuidFromBinary($article->namespace->holder_id,true));
			
			if ($this->getRelatedArticles)
				$relatedArticles = ZoneArticle::model()->relatedArticles($article->namespace->holder_id,$article->id);
			
		}
		
		$countArticle = ZoneArticle::model()->countArticlesByUserID($author->id);
		$totalPhotos = ZoneUser::model()->countPhotos($author->id);
		
		$out = array(
			'article'	=>$article, 
			'images'	=> $images, 
			'author'	=> $author,
			'node'		=>$node,
			'countArticle'=>$countArticle,
			'totalPhotos'=>$totalPhotos,
			'relatedArticles'=>$relatedArticles,
		);
		
		if ($controller->isJsonRequest || $this->requestType == 'json') {
			ajaxOut($out);
		}
		// continue display article
		$controller->render($this->pathIndex, $out);
	}
}