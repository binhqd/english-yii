<?php
/**
 * Me controller
 * @author huytbt <huytbt@gmail.com>
 * @version 2.0
 */
class MeController extends GNApiController
{
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}

	/**
	 * This method is used to define actions of me controllers
	 * @return array
	 */
	public function actions()
	{ 
		return array(
			'info'	=> array(
				'class'		=> 'api_app.modules.users.actions.APIGetMeInfoAction',
			),
			// This action is used change pass user
			'change_pass'	=> array(
				'class'		=> 'api_app.modules.users.actions.APIChangePasswordAction',
			),
			'list_article'	=> array(
				'class'		=> 'api_app.modules.articles.actions.APIArticleListAction',
			),
			'create_article'	=> array(
				'class'		=> 'api_app.modules.articles.actions.APICreateArticleAction',
			),
			
		);
	}
	
	public function actionTest() {
		Yii::app()->response->send(200, array(
			'hello'	=> array(1,2,3,4),
		));
	}
}