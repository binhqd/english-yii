<?php

class Admin_manageModule extends JLWebModule
{
	public $strIconPath = '/files/categories/icons/';
	public $intBizCategoriesRows = 20;
	public $defaultController = "dashboard";
	public $defaultLayout = "//layouts/back_end";
	public $layout = "//layouts/back_end";
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin_manage.models.*',
			'admin_manage.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
