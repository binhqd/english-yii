<?php
//phpinfo();
$includePath = get_include_path();
set_include_path($includePath . PATH_SEPARATOR . dirname(__FILE__) . '/extensions');

// có thể config trong file .htaccess bằng cách sử dụng: SetEnv APPLICATION_ENV development
require(dirname(__FILE__) . '/config/constants.php');

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../yii/framework/yii.php';
$config = dirname(__FILE__) . '/config/' . APPLICATION_ENV . '/config.php';

require_once($yii);

Yii::setPathOfAlias("framework", realpath(dirname($yii)));
$app = Yii::createWebApplication($config);

Yii::import("ext.zendAutoloader.EZendAutoloader", true);

EZendAutoloader::$prefixes = array('Zend', 'Custom');
Yii::registerAutoloader(array("EZendAutoloader", "loadClass"));

Yii::app()->onError = array("JLErrorHandler", "handlingError");
Yii::app()->onException = array("JLErrorHandler", "handlingException");

Yii::app()->onBeginRequest = array("JLAuthHandler", "checkAuth");
$app->run();
