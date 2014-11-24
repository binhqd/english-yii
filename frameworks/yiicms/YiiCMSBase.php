<?php

defined('YIICMS_PATH') or define('YIICMS_PATH',dirname(__FILE__));
class YiiCMSBase
{
	public static $mappedControllers = array();
	public static $configs = array();
	public static $classMap=array();
	public static $enableIncludePath=true;
	
	private static $_aliases=array('yiicms'=>YIICMS_PATH); // alias => path
	private static $_includePaths = array();						// list of include paths
	
	/**
	 * @return string the path of the framework
	 */
	public static function getFrameworkPath()
	{
		return YIICMS_PATH;
	}

	private static $_coreClasses=array(
		// Core components
		//'GNController'		=> '/components/GNController.php',
		'DType'					=> '/definitions/DType.php'
	);
	
	/**
	 * Class autoload loader.
	 * This method is provided to be invoked within an __autoload() magic method.
	 * @param string $className class name
	 * @return boolean whether the class has been loaded successfully
	 */
	public static function autoload($className)
	{
		// use include so that the error PHP file may appear
		if(isset(self::$classMap[$className]))
			include(self::$classMap[$className]);
		elseif(isset(self::$_coreClasses[$className]))
			include(YIICMS_PATH.self::$_coreClasses[$className]);
		elseif(isset(GNClassMap::$classMap[$className])) {
			include(APPLICATION_PATH.GNClassMap::$classMap[$className]);
		} else
		{
			
			return false;
		}
		return true;
	}
	
	public static function loadModule($moduleName, $loadController = true) {
		$pathAlias = "yiicms.modules.{$moduleName}";
		Yii::import("{$pathAlias}.models.*");
		Yii::import("{$pathAlias}.messages.*");
		
		$mappedControllers = array();
		
		$path = Yii::getPathOfAlias("{$pathAlias}") . "/controller_maps.php";
		if (is_file($path)) {
			$mappedControllers = require_once($path);
		}
		
		if ($loadController) {
			YiiCMSBase::$mappedControllers = CMap::mergeArray(YiiCMSBase::$mappedControllers, $mappedControllers);
		}
	}
}