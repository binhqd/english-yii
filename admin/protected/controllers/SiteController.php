<?php
class SiteController extends GNController
{
	//public $layout = "//layouts/master/welcome";
	public $defaultAction = 'homepage';
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
			'upload'=>array(
				'class'=>'xupload.actions.XUploadAction',
				'path' =>Yii::getPathOfAlias('webroot') . "/upload/career",
				'publicPath' => Yii::app() -> getBaseUrl() . "/upload/career",
			),
		);
	}
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		exit('admin');
		$this->pageTitle = Yii::app()->name . ' - Homepage';
		
		$this->render('index');
	}
	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		debug('error');
	}
	
	public function actionHomepage() {
// 		dump(Yii::getPathOfAlias('admin.views.site'));
		$this->render('admin.views.site.homepage');
	}
}