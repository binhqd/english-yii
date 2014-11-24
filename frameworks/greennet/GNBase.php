<?php

defined('GN_PATH') or define('GN_PATH',dirname(__FILE__));

/**
 * GNBase is a helper class serving common framework functionalities.
 *
 * Do not use YiiBase directly. Instead, use its child class {@link Yii} where
 * you can customize methods of YiiBase.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system
 * @since 1.0
 */
class GNBase
{
	public static $mappedControllers = array();
	public static $configs = array();
	/**
	 * @var array class map used by the Yii autoloading mechanism.
	 * The array keys are the class names and the array values are the corresponding class file paths.
	 * @since 1.1.5
	 */
	public static $classMap=array();
	/**
	 * @var boolean whether to rely on PHP include path to autoload class files. Defaults to true.
	 * You may set this to be false if your hosting environment doesn't allow changing the PHP
	 * include path, or if you want to append additional autoloaders to the default Yii autoloader.
	 * @since 1.1.8
	 */
	public static $enableIncludePath=true;
	
	private static $_aliases=array('greennet'=>GN_PATH); // alias => path
	private static $_includePaths = array();						// list of include paths
	
	/**
	 * @return string the path of the framework
	 */
	public static function getFrameworkPath()
	{
		return GN_PATH;
	}
	
	/**
	 * @var array class map for core Yii classes.
	 * NOTE, DO NOT MODIFY THIS ARRAY MANUALLY. IF YOU CHANGE OR ADD SOME CORE CLASSES,
	 * PLEASE RUN 'build autoload' COMMAND TO UPDATE THIS ARRAY.
	 */
	private static $_coreClasses=array(
		// Core components
		'GNController'		=> '/components/GNController.php',
		'GNActiveRecord'	=> '/components/GNActiveRecord.php',
		'GNFormModel'		=> '/components/GNFormModel.php',
		'GNErrorHandler'	=> '/components/GNErrorHandler.php',
		'GNRequestHandler'	=> '/components/GNRequestHandler.php',
		'GNAuthHandler'		=> '/components/GNAuthHandler.php',
		'GNDictionary'		=> '/components/GNDictionary.php',
		'GNRouter'			=> '/components/GNRouter.php',
		'GNAssetManager'	=> '/components/GNAssetManager.php',
		'GNAction'			=> '/components/GNAction.php',
		'GNWidget'			=> '/components/GNWidget.php',
		'GNWebModule'		=> '/components/GNWebModule.php',
		'GNApiController'	=> '/components/GNApiController.php',
		'GNTemplateEngine'	=> '/web/widgets/GNTemplateEngine.php',
		
		'GNDBLogRoute'		=> '/components/db/GNDBLogRoute.php',
		'GNLog'				=> '/components/db/GNLog.php',
			
		'GNi18n'			=> '/components/GNi18n.php',
		
		// Core helpers
		'GNAssetHelper'		=> '/web/helpers/GNAssetHelper.php',
		'GNStringHelper'	=> '/helpers/GNStringHelper.php',
		
		// Core widgets
		'GNScriptPacker'		=> '/web/widgets/GNScriptPacker.php',
		'ExtendedClientScript'	=> '/extensions/ExtendedClientScript.php',
		
		
		/**
		 * Modules
		 */ 
		// Users models
		'GNCoreUser'				=> '/modules/users/models/GNCoreUser.php',
		'GNUserProfile'				=> '/modules/users/models/GNUserProfile.php',
		'GNTmpUser'					=> '/modules/users/models/GNTmpUser.php',
		'GNValidation'				=> '/modules/users/models/GNValidation.php',
		'GNRegistrationValidation'	=> '/modules/users/models/GNRegistrationValidation.php',
		'GNUserIdentity'			=> '/modules/users/components/GNUserIdentity.php',
		
		/**
		 * Social integration
		 */
		'GNFacebookConnector'		=> '/modules/social/components/Facebook/GNFacebookConnector.php',
		'GNFacebookProfilePhotoConnector'		=> '/modules/social/components/Facebook/GNFacebookProfilePhotoConnector.php',
		//'GNUser'			=> '/extensions/.php',
		
		// Object 
		'GNObject'					=> '/modules/object/models/GNObject.php',
		'GNCriteria'				=> '/modules/object/models/GNCriteria.php',
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
			include(GN_PATH.self::$_coreClasses[$className]);
		elseif(isset(GNClassMap::$classMap[$className])) {
			include(APPLICATION_PATH.GNClassMap::$classMap[$className]);
		} else
		{
			
			return false;
		}
		return true;
	}
	
	public static function loadModule($moduleName, $loadController = true) {
		$pathAlias = "greennet.modules.{$moduleName}";
		Yii::import("{$pathAlias}.models.*");
		Yii::import("{$pathAlias}.messages.*");
		
		$mappedControllers = array();
		
		$path = Yii::getPathOfAlias("{$pathAlias}") . "/controller_maps.php";
		if (is_file($path)) {
			$mappedControllers = require_once($path);
		}
		
		if ($loadController) {
			GNBase::$mappedControllers = CMap::mergeArray(GNBase::$mappedControllers, $mappedControllers);
		}
	}
}