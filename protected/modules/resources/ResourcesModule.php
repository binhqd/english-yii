<?php

/**
 * Module Articles  
 * 
 * @class	   FeedbackModule
 * @author	  binhqd
 * @version	 1.0
 * @date		2011-05-23
 */
class ResourcesModule extends GNWebModule
{
	/**
	 * Assign default controller for current module
	 */
	public $defaultController = 'default';
	
	/**
	 * init
	 */
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		// import the module-level models and components
		$this->setImport(array(
			'articles.models.*',
			'articles.components.*',
		));
	}
	
	
}
