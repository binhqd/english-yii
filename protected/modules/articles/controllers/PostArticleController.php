<?php
/**
 * ArticlesController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Apr 5, 2013 10:59:48 AM
 */
Yii::import('greennet.modules.articles.example.models.*');
class PostArticleController extends GNController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	
	private $_common = array(
		'model'			=> array(
			'class'		=> 'ZoneArticle',
			'belongsTo'	=> array(
				'namespace'	=> array(
					'class'	=> 'ZoneArticleNamespace'
				),
				'author'	=> array(
					'class'	=> 'ZoneArticleAuthor'
				)
			)
		),
		'uploadPath'	=> 'upload/articles/',
		'indexUri'		=> '/articles/postArticle/index',
		'createUri'		=> '/articles/postArticle/create',
		'editUri'		=> '/articles/postArticle/edit',
		'viewUri'		=> '/articles/postArticle/view',
		'deleteUri'		=> '/articles/postArticle/delete'
	);
	
	public function allowedActions()
	{
		return '*';
	}
	
	public function actions(){
		return array(
			// List articles
			'index'	=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNArticleIndexAction',
				'bulkDeleteUrl'	=> GNRouter::createUrl('/articles/postArticle/bulk_delete'),
			), $this->_common),
				
			// Add new article
			'create'	=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNCreateArticleAction',
				'successUrl'	=> GNRouter::createUrl('/articles/postArticle/index'),
				'errorUrl'		=> GNRouter::createUrl('/articles/postArticle/create'),
			), $this->_common),
				
			// Edit article
			'edit'	=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNEditArticleAction',
				'successUrl'	=> GNRouter::createUrl('/articles/postArticle/index'),
				'errorUrl'		=> GNRouter::createUrl('/articles/postArticle/edit/'),
			), $this->_common),
			
			// Delete an article
			'delete'	=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNDeleteArticleAction',
				'successUrl'	=> GNRouter::createUrl('/articles/postArticle/index'),
				'errorUrl'		=> GNRouter::createUrl('/articles/postArticle/index')
			), $this->_common),
				
			// Bulk delete
			'bulk_delete'		=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNBulkDeleteArticlesAction',
				'successUrl'	=> GNRouter::createUrl('/articles/postArticle/index'),
				'errorUrl'		=> GNRouter::createUrl('/articles/postArticle/index')
			), $this->_common),
		);
		
	}
}