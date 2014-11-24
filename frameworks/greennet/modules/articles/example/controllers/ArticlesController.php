<?php
/**
 * ArticlesController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Apr 5, 2013 10:59:48 AM
 */
Yii::import('greennet.modules.articles.example.models.*');
class ArticlesController extends GNController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	
	private $_common = array(
		'model'			=> 'DNTArticle',
		'uploadPath'	=> 'upload/articles/',
		'indexUri'		=> '/articles/index',
		'createUri'		=> '/articles/create',
		'editUri'		=> '/articles/edit',
		'viewUri'		=> '/articles/view',
		'deleteUri'		=> '/articles/delete'
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
				'bulkDeleteUrl'	=> GNRouter::createUrl('/articles/bulk_delete'),
			), $this->_common),
				
			// Add new article
			'create'	=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNCreateArticleAction',
				'successUrl'	=> GNRouter::createUrl('/articles/index'),
				'errorUrl'		=> GNRouter::createUrl('/articles/create'),
			), $this->_common),
				
			// Edit article
			'edit'	=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNEditArticleAction',
				'successUrl'	=> GNRouter::createUrl('/articles/index'),
				'errorUrl'		=> GNRouter::createUrl('/articles/edit/'),
			), $this->_common),
			
			// Delete an article
			'delete'	=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNDeleteArticleAction',
				'successUrl'	=> GNRouter::createUrl('/articles/index'),
				'errorUrl'		=> GNRouter::createUrl('/articles/index')
			), $this->_common),
				
			// Bulk delete
			'bulk_delete'		=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNBulkDeleteArticlesAction',
				'successUrl'	=> GNRouter::createUrl('/articles/index'),
				'errorUrl'		=> GNRouter::createUrl('/articles/index')
			), $this->_common),
		);
		
	}
}