<?php
$strJLPath = dirname(__FILE__) . '/../../../protected/components/jl_bd';
$strMyZonePath = dirname(__FILE__) . '/../../../protected/components';
Yii::setPathOfAlias('widgets', dirname(__FILE__) . '/../../../protected/widgets');
Yii::setPathOfAlias('rules', $strMyZonePath . '/../../../protected/runtime/rules');
Yii::setPathOfAlias('frontend', dirname(__FILE__) . '/../../../');
Yii::setPathOfAlias('frontapp', dirname(__FILE__) . '/../../../protected');
// Yii::setPathOfAlias('behaviors', $strJLPath . '/behaviors');
// Yii::setPathOfAlias('events', $strJLPath . '/events');
// Yii::setPathOfAlias('helpers', $strJLPath . '/helpers');
// Yii::setPathOfAlias('filters', $strJLPath . '/filters');
// Yii::setPathOfAlias('vendors', $strJLPath . '/vendors');
Yii::setPathOfAlias('fonts', dirname(__FILE__) . '/../../../protected/data/fonts');
Yii::setPathOfAlias('themes', dirname(__FILE__) . "/../../../protected/views/themes");

Yii::setPathOfAlias('zend', dirname(__FILE__) . "/../../../protected/extensions/Zend");
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . "/../../../protected/extensions/bootstrap");
// Webroot
Yii::setPathOfAlias('wwwroot', dirname(__FILE__) . "/../../../wwwroot");
Yii::setPathOfAlias('webroot', dirname(__FILE__) . "/../../../wwwroot");
Yii::setPathOfAlias('admin', dirname(__FILE__) . "/../");


// Easyweb

