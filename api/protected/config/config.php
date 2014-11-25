<?php
define('DEFAULT_CONFIG_DIR', APP_ROOT . "/protected/config/");
define('DEFAULT_DOMAIN', 'http://myzone.localhost.com');
define('API_BASE', APP_ROOT . "/api/protected");
define('API_CONFIG_DIR', API_BASE . "/config/");

// uncomment the following to define a path alias
require(DEFAULT_CONFIG_DIR . APPLICATION_ENV . '/_path-aliases.php');
require(API_CONFIG_DIR . '/_path-aliases.php');

// Yii::setPathOfAlias('admin', dirname(__FILE__) . '/../../admin/');

require(DEFAULT_CONFIG_DIR . "/function_alias.php");

$info = require_once(DEFAULT_CONFIG_DIR . '/server-info.php');

//GNBase::loadModule('articles');
// GNBase::loadModule('comment');

// parse headers
Yii::import('api_app.components.ApiAccess');
Yii::import('api_app.components.ApiSession');
$headers = getallheaders();

$controllerPath = APP_ROOT . "/api/protected/controllers";
// dump($headers);
if (isset($headers['API-Version'])) {
	if (is_dir(API_BASE . "/versions/v{$headers['API-Version']}")) {
		$controllerPath = API_BASE . "/versions/v{$headers['API-Version']}/controllers";
	}
}

return 
	array(
		'sourceLanguage'	=> 'en',
		'language' 			=> 'en',
		'basePath' 			=> APP_ROOT . "/protected/",
		'runtimePath'		=> APP_ROOT . '/runtime',
		'name' 				=> 'Youlook',
		//'theme'				=> 'bootstrap',
		'controllerPath' 	=> $controllerPath,
		'controllerMap'		=> CMap::mergeArray(require(Yii::getPathOfAlias('greennet') . '/controller_maps.php'),
			array(
				'words'		=> 'api_base.modules.english.controllers.WordsController',
				'collections'	=> 'api_base.modules.english.controllers.CollectionsController',
			)
		),
		// preloading 'log' component
		'preload'			=> array('log'),
		'import'			=> CMap::mergeArray(
								require(DEFAULT_CONFIG_DIR . APPLICATION_ENV . '/_common_imported.php'), 
								require(API_BASE . '/config/_imported.php')
								),
		'modules'			=> require(DEFAULT_CONFIG_DIR . APPLICATION_ENV . '/_modules.php'),
		'components' 		=> array_merge(
			require(DEFAULT_CONFIG_DIR . APPLICATION_ENV . '/_common_components.php'),
			array(
				'urlManager' => array(
					'urlFormat' => 'path',
					'showScriptName' => false,
					'rules' 	=> require(dirname(__FILE__) . '/_rewrite_url_rules.php'),
				),
				'clientScript'=>array(
					'class'=>'ExtendedClientScript',
					'combineFiles'	=> false,
					'compressCss'	=> false,
					'compressJs'	=> false,
					'baseDir'		=> "/assets/"
				),
				'response'	=> array(
					'class'	=> 'api_app.components.ZoneApiResponse'
				),
				'session' => array(
					'class'			=> 'ApiSession',
					//'sessionID'		=> "n7c48h37jebma5kvcbbudqfe21",
					'sessionName'	=> "YoulookAPI",
					'autoStart'		=> false,
					'savePath'		=>  APP_ROOT . '/runtime/session/',
					'cookieMode'	=> 'none',
					'timeout'		=> 3600*24*30,
// 					'cookieParams'	=> array(
// 						'lifetime'	=> 0,
// 						'path'		=> '/',
// 						'domain'	=> COOKIE_DOMAIN,
// 						'httpOnly'	=> true,
// 					),
				),
			)
		),
		'params'			=> array_merge(require(DEFAULT_CONFIG_DIR . APPLICATION_ENV . '/params.php'),
			array(
				'partAlias' => array(
					'me' => 'users',
					'collections'	=> 'collections'
				)
			)
		),
		'aliases' => array(
				//If you manually installed it
				'xupload' => 'ext.xupload'
		),
	);
