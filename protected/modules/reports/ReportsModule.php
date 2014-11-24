<?php

/**
 * @author : Chu Tieu
 * @version	 1.0
 */
class ReportsModule extends JLWebModule
{
	/**
	 * Thiết lập Controller mặc định cho Module
	 */
	public $defaultController = 'reportConcern';
	
	/**
	 * init
	 */
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		// import the module-level models and components
		$this->setImport(array(
			'reports.models.*',
			'reports.components.*',
		));
	}
}
