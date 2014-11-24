<?php
/**
 * PointSystemModule - This class is used to initialization module Friends
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 27-Apr-2012 8:34:40 AM
 * @modified 21-Jun-2012 3:10:35 PM
 */
class MobileModule extends JLWebModule
{

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
	
		// import the module-level models and components
		$this->setImport(array(
			'pointSystem.models.*',
			'pointSystem.components.*',
		));
	}
	
	/**
	 * 
	 * @param action
	 * @param controller
	 */
	public function beforeControllerAction($action, $controller)
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