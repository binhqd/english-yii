<?php
$includePath = get_include_path();
set_include_path($includePath . PATH_SEPARATOR . dirname(__FILE__) . '/protected/extensions');

// có thể config trong file .htaccess bằng cách sử dụng: SetEnv APPLICATION_ENV development
require(dirname(__FILE__) . '/protected/config/constants.php');

// change the following paths if necessary
$yii = dirname(__FILE__) . '/frameworks/yii/yiilite.php';
$greennet = dirname(__FILE__) . '/frameworks/greennet/greennet.php';
$yiicms = dirname(__FILE__) . '/frameworks/yiicms/yiicms.php';

$classmaps = dirname(__FILE__) . '/protected/config/class_maps.php';

$config = dirname(__FILE__) . '/protected/config/' . APPLICATION_ENV . '/config.php';

require_once($yii);
require_once($classmaps);
require_once($greennet);
require_once($yiicms);

Yii::setPathOfAlias("framework", realpath(dirname($yii)));
Yii::setPathOfAlias("greennet", realpath(GNBase::getFrameworkPath()));
Yii::setPathOfAlias("yiicms", realpath(YiiCMSBase::getFrameworkPath()));

$app = Yii::createWebApplication($config);
Yii::import("ext.zendAutoloader.EZendAutoloader", true);

EZendAutoloader::$prefixes = array('Zend', 'Custom');
//dump($_SERVER);
Yii::registerAutoloader(array("EZendAutoloader", "loadClass"));
// spl_autoload_register(array('GNBase','autoload'));
Yii::registerAutoloader(array('GNBase','autoload'), true);
Yii::registerAutoloader(array('YiiCMSBase','autoload'), true);

Yii::app()->onError = array("CustomErrorHandler", "handlingError");
Yii::app()->onException = array("CustomErrorHandler", "handlingException");

Yii::app()->onBeginRequest = array("GNAuthHandler", "checkAuth");
Yii::app()->onBeginRequest = array("GNRequestHandler", "checkDevice");
//Yii::app()->onBeginRequest = array("ZoneRequestHandler", "setup");

$app->run();
