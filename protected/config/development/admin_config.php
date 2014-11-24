<?php
require "constants.php";
define('DEFAULT_DOMAIN', 'http://youlook.net');

// uncomment the following to define a path alias
require(dirname(__FILE__) . '/_path-aliases.php');
Yii::setPathOfAlias('admin', dirname(__FILE__) . '/../../admin/');


require(dirname(__FILE__) . "/../function_alias.php");
$info = require_once(dirname(__FILE__) . '/../server-info.php');

//GNBase::loadModule('articles');

return 
	array(
		'onBeginRequest'	=> array('GNRequestHandler', 'LocaleHandler'),
		'sourceLanguage'	=> 'en',
		'language' 			=> 'en',
		'basePath' 			=> dirname(__FILE__) . '/../..',
		'runtimePath'		=> dirname(__FILE__) . '/../../../jlruntime',
		'name' 				=> 'Youlook',
		'theme'				=> 'bootstrap',
		'controllerMap'		=> CMap::mergeArray(require(Yii::getPathOfAlias('greennet') . '/controller_maps.php'),
			array(
				'default'		=> 'admin.controllers.TestController',
				'errors'		=> 'greennet.modules.error_handling.controllers.ErrorLogsController',
				'reports'		=> 'greennet.modules.report_concerns.controllers.AdminController'
			)
		),
		// preloading 'log' component
		'preload'			=> array('log'),
		'import'			=> require(dirname(__FILE__) . '/_common_imported.php'),
		'modules'			=> require(dirname(__FILE__) . '/_modules.php'),
		'components' 		=> CMap::mergeArray(require(dirname(__FILE__) . '/_common_components.php'),
			array(
				'urlManager' => array(
					'urlFormat' => 'path',
					'showScriptName' => false,
					'rules' 	=> array(
						'' => 'default/index',
						
						'page/<alias:[a-zA-Z0-9\_\-]+>'=>'page/index',
						'dashboard/index'	=> 'dashboard/review',
						'lang/<lang:[\w]{2}>'	=> 'language/switch',
						'<controller:\w+>/<id:\d+>' => '<controller>/view',
						'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
						'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
						
						'minify/<group:[^\/]+>'=>'minify/index',
						// 			'profile/<id:[a-f]+>'	=> 'profile/view'
					)
				),
				'clientScript'=>array(
					'class'=>'ExtendedClientScript',
					'combineFiles'	=> false,
					'compressCss'	=> false,
					'compressJs'	=> false,
					'baseDir'		=> "/assets/"
				)
			)
		),
		//'components' 		=> require(dirname(__FILE__) . '/_common_components.php'),
		'params'			=> require(dirname(__FILE__) . '/params.php'),
		'aliases' => array(
				//If you manually installed it
				
		),
	);
