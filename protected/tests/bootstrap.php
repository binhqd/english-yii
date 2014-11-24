<?php 
$yiit= dirname(__FILE__) . '/../../yii/framework/yiit.php';
$config = dirname(__FILE__).'/../config/unitest/config.php';
 
require_once($yiit);
 
require(dirname(__FILE__) . '/../extensions/wunit/WUnit.php');
WUnit::createWebApplication($config);
