<?php
// file boot.php
 
$yiit = dirname(__FILE__) . '/../../yii/framework/yiit.php';
$config = dirname(__FILE__).'/../config/test.php';
 
require_once($yiit);
 
Yii::createWebApplication($config);
 
?>
