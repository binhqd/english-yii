<?php
error_reporting(E_ALL);

// change the following paths if necessary
$includePath = get_include_path();
set_include_path($includePath . PATH_SEPARATOR . dirname(__FILE__) . '/extensions');

require(dirname(__FILE__) . '/config/constants.php');

$yiic=dirname(__FILE__).'/../frameworks/yii/yii.php';

$greennet = dirname(__FILE__) . '/../frameworks/greennet/greennet.php';
$classmaps = dirname(__FILE__) . '/config/class_maps.php';

// FIXME: May alternate the config file here
$config = dirname(__FILE__) . '/../config/' . APPLICATION_ENV . '/config.php';


require_once($yiic);
require_once($classmaps);
require_once($greennet);

Yii::setPathOfAlias("framework", realpath(dirname($yiic)));
Yii::setPathOfAlias("greennet", realpath(GNBase::getFrameworkPath()));

Yii::import("application.extensions.zendAutoloader.EZendAutoloader", true);


EZendAutoloader::$prefixes = array('Zend', 'Custom');

Yii::registerAutoloader(array("EZendAutoloader", "loadClass"));
// spl_autoload_register(array('GNBase','autoload'));
Yii::registerAutoloader(array('GNBase','autoload'), true);

Yii::setPathOfAlias('Everyman', Yii::getPathOfAlias('ext.neo4jphp.lib.Everyman'));