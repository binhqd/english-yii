<?php
/**
 * User controller
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @author truonghn <truonghn@gmail.com>
 * @version 2.0
 */
class UserController extends GNApiController
{
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}

	public function actions()
	{
		return array(
			// action get user info
			'user_info'	=> array(
				'class'		=> 'api_app.modules.users.actions.APIGetUserInfoAction',
			),
			// action get user new member list
			'new_member_list'	=> array(
				'class'		=> 'api_app.modules.users.actions.APINewMemberListAction',
			),
			// action register new member list
			'register'	=> array(
				'class'		=> 'api_app.modules.users.actions.APIUserRegisterAction',
			),
			'list_article'	=> array(
				'class'		=> 'api_app.modules.articles.actions.APIUserArticleListAction',
			),
		);
	}
}