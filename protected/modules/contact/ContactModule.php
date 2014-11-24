<?php
class ContactModule extends JLWebModule
{
	/**
	 * Thiết lập Controller mặc định cho Module
	 */
	public $defaultController = 'default';
	
	/**
	 * init
	 */
	public function init()
	{
		$this->setImport(array(
			'contact.models.*',
		));
	}
}
