<?php
$includePath = get_include_path();
define('APP_ROOT', realpath(dirname(__FILE__) . '/../'));

set_include_path($includePath . PATH_SEPARATOR . APP_ROOT . '/protected/extensions');

// có thể config trong file .htaccess bằng cách sử dụng: SetEnv APPLICATION_ENV development
require(APP_ROOT . '/protected/config/constants.php');
require(APP_ROOT . '/api/protected/config/constants.php');

// change the following paths if necessary
$yii = APP_ROOT . '/frameworks/yii/yiilite.php';
$greennet = APP_ROOT . '/frameworks/greennet/greennet.php';
$yiicms = APP_ROOT . '/frameworks/yiicms/yiicms.php';

$classmaps = APP_ROOT . '/protected/config/class_maps.php';

$config = APP_ROOT . '/api/protected/config/config.php';

require_once($yii);
require_once($classmaps);
require_once($greennet);
require_once($yiicms);

Yii::setPathOfAlias("framework", realpath(dirname($yii)));
Yii::setPathOfAlias("greennet", realpath(GNBase::getFrameworkPath()));
Yii::setPathOfAlias("yiicms", realpath(YiiCMSBase::getFrameworkPath()));
Yii::setPathOfAlias("api_app", APP_ROOT . "/api/protected/");

$app = Yii::createWebApplication($config);

//Yii::import("ext.zendAutoloader.EZendAutoloader", true);


//EZendAutoloader::$prefixes = array('Zend', 'Custom');

//Yii::registerAutoloader(array("EZendAutoloader", "loadClass"));
// spl_autoload_register(array('GNBase','autoload'));
Yii::registerAutoloader(array('GNBase','autoload'), true);
Yii::registerAutoloader(array('YiiCMSBase','autoload'), true);

Yii::app()->onError = array("CustomErrorHandler", "handlingError");
Yii::app()->onException = array("CustomErrorHandler", "handlingException");

Yii::app()->onBeginRequest = array("ApiAuthHandler", "checkAuth");
// Yii::app()->onBeginRequest = array("GNAuthHandler", "checkAuth");
Yii::app()->onBeginRequest = array("ApiRequestHandler", "checkDevice");
//Yii::app()->onBeginRequest = array("ZoneRequestHandler", "setup");


$app->run();
