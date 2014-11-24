<?php
$strJLPath = dirname(__FILE__) . '/../../components/jl_bd';
$strMyZonePath = dirname(__FILE__) . '/../../components';
Yii::setPathOfAlias('widgets', $strJLPath . '/widgets');
Yii::setPathOfAlias('widgets', $strMyZonePath . '/widgets');
Yii::setPathOfAlias('rules', $strMyZonePath . '/../../runtime/rules');
Yii::setPathOfAlias('utils', $strJLPath . '/utils');
Yii::setPathOfAlias('behaviors', $strJLPath . '/behaviors');
Yii::setPathOfAlias('events', $strJLPath . '/events');
Yii::setPathOfAlias('helpers', $strJLPath . '/helpers');
Yii::setPathOfAlias('filters', $strJLPath . '/filters');
Yii::setPathOfAlias('vendors', $strJLPath . '/vendors');
Yii::setPathOfAlias('fonts', dirname(__FILE__) . '/../../data/fonts');
Yii::setPathOfAlias('themes', dirname(__FILE__) . "/../../views/themes");

Yii::setPathOfAlias('zend', dirname(__FILE__) . "/../../extensions/Zend");
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . "/../../extensions/bootstrap");
// Webroot
Yii::setPathOfAlias('wwwroot', dirname(__FILE__) . "/../../../wwwroot");


// Easyweb

