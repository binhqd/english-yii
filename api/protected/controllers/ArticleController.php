<?php
/**
 * Articles Controller Class
 * @author truonghn <truonghn@gmail.com>
 * @version 2.0
 */
class ArticleController extends GNApiController
{
	/**
	 * Description of this method
	 * @return void
	 */
	public function allowedActions()
	{
		return '*';
	}
	/**
	 * This method is used to define actions of article controller
	 * @return array
	 */
	public function actions()
	{ 
		return array(
			'delete_article'	=> array(
				'class'		=> 'api_app.modules.articles.actions.APIDeleteArticleAction',
				
			),
			'getarticleinfo'	=> array(
				'class'		=> 'api_app.modules.articles.actions.APIGetArticleInfoAction',
			),
			'updatearticle'	=> array(
				'class'		=> 'api_app.modules.articles.actions.APIUpdateArticleInfoAction',
			),
			'like'	=> array(
				'class'		=> 'api_app.modules.like.actions.APILikeArticleAction',
			),
			'list_comment'	=> array(
				'class'		=> 'api_app.modules.comments.actions.APIArticleCommentListAction',
			),
			'create_comment'	=> array(
				'class'		=> 'api_app.modules.comments.actions.APICreateArticleCommentAction',
			),
			
			
		);
	}
}