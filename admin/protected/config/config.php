<?php
// require "constants.php";

// uncomment the following to define a path alias
require(dirname(__FILE__) . '/_path-aliases.php');

require(dirname(__FILE__) . "/function_alias.php");
$info = require_once(dirname(__FILE__) . '/server-info.php');

//GNBase::loadModule('articles');
GNBase::loadModule('comment');

return 
	array(
		'onBeginRequest'	=> array('GNRequestHandler', 'LocaleHandler'),
		'sourceLanguage'	=> 'en',
		'language' 			=> 'en',
		'basePath' 			=> dirname(__FILE__) . '/../../../protected/',
		'controllerPath'	=> dirname(__FILE__) . '/../controllers',
		'viewPath'			=> dirname(__FILE__) . '/../views',
		'modulePath'		=> dirname(__FILE__) . '/../modules',
		'runtimePath'		=> dirname(__FILE__) . '/../../../runtime',
		'name' 				=> 'My App',
		
		'controllerMap'		=> CMap::mergeArray(require(Yii::getPathOfAlias('greennet') . '/controller_maps.php'),
			array(
				// invites
				'google'	=> array(
					'class'	=> 'application.modules.invites.controllers.GmailController'
				),
			)
		),
		// preloading 'log' component
		'preload'			=> array('log'),
		'import'			=> require(dirname(__FILE__) . '/_common_imported.php'),
		'modules'			=> require(dirname(__FILE__) . '/_modules.php'),
		'components' 		=> require(dirname(__FILE__) . '/_common_components.php'),
		'params'			=> require(dirname(__FILE__) . '/params.php'),
		'theme'				=> 'bootstrap',
		'aliases' => array(
				//If you manually installed it
				'xupload' => 'ext.xupload'
		),
	);
