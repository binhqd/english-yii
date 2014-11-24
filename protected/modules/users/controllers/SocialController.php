<?php
/**
 * SocialController - This controller is used to contain actions support for social integration
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 24-Jan-2013 10:41:14 AM
 * @modified 29-Jan-2013 11:09:19 AM
 */
class SocialController extends JLController
{
	public function allowedActions()
	{
		return '*';
	}
	public function actionGetPhotoFB(){
		if(currentUser()->isGuest) jsonOut(array(
			'error'=>true
		));
		$model = GNSyncFacebookPhoto::model()->findAllByAttributes(array(
			'user_id'=>currentUser()->id
		),array('order'=>'created desc','limit'=>3));
		if(!empty($model)){
			jsonOut(array(
				'error'=>false,
				'data'=>$model
			));
		}else jsonOut(array(
			'error'=>true
		));
		
	}
	/**
	 * This action is used to login with Facebook
	 */
	public function actionLoginWithFacebook()
	{
		// Yii::import('greennet.modules.social.controllers.*');
		// $controller = new GNFacebookController('LoginWithFacebook','social');
		// $controller->mailViewPath = "application.views.mail";
		// $controller->init();
		// $controller->actionIndex();
		// dump($controller);
	}
	public function actionLoginWithGooglePlus()
	{
		$model = new GNSocial();

		if (!empty($_POST['GNSocial']))
		{
			$model->attributes=$_POST['GNSocial'];
			if($model->validate())
			{
						
				
				
			}
		}$this->render('get_users_info',array(
			'model'=>$model,
		));
	}

		
	

	/**
	 * This action is used to map account with social account
	 */
	public function actionMap()
	{
	
	}

	/**
	 * This action is used to manage social integration (connect/disconnect with
	 * socials)
	 */
	public function actionManageGooglePlus()
	{
		$model = new GNSocial();

		if (!empty($_POST['GNSocial']))
		{
			$model->attributes=$_POST['GNSocial'];
			if($model->validate())
			{
				
				
			}
		}$this->render('users_info',array(
			'model'=>$model,
		));
	}
		

	/**
	 * This action is used to connect current account with social account
	 *
	 * @param social_alias    String of social alias
	 */
	public function actionConnect($social_alias)
	{
	}

	/**
	 * This action is used to disconnect social
	 *
	 * @param social_mapping_id    String of ID social_mapping_id
	 */
	public function actionDisconnect($social_mapping_id)
	{
	}

}