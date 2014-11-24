<?php
/**
 * Module CMS  
 * @author huytbt <huytbt@gmail.com>
 * @version 1.0
 */
class CmsModule extends GNWebModule
{
	/**
	 * Assign default controller for current module
	 */
	public $defaultController = 'privacy';
	
	/**
	 * init
	 */
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		// import the module-level models and components
		$this->setImport(array(
			'cms.models.*',
			'cms.components.*',
		));
	}
}
