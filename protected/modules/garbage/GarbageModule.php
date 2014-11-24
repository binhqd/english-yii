<?php

/**
 * Module dùng cho việc quản lý user
 * 
 * @class	   UserModule
 * @author	  huytbt
 * @version	 1.0
 * @date		2011-05-23
 */
class GarbageModule extends JLWebModule
{
	/**
	 * Thiết lập Controller mặc định cho Module
	 */
	public $defaultController = 'list';
	
	/**
	 * init
	 */
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		// import the module-level models and components
		$this->setImport(array(
			'garbage.models.*',
			'garbage.controllers.*',
		));
	}
}
