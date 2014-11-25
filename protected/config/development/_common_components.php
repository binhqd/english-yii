<?php 
if (preg_match("/jlframework\.com$/", $_SERVER['HTTP_HOST'])) {
	defined('COOKIE_DOMAIN') or define('COOKIE_DOMAIN', ".localhost.com");
} else {
	defined('COOKIE_DOMAIN') or define('COOKIE_DOMAIN', ".localhost.com");
}

return array(
	'user' => array(
		// enable cookie-based authentication
		'class' => 'RWebUser',
		'allowAutoLogin' => true,
		'loginUrl' => array('/login')
	),
	// session configuration
	'id' => 'myzone',
	'session' => array(
		'savePath' =>  dirname(__FILE__) . '/../../../runtime/session/',
		'cookieMode' => 'allow',
		'timeout'=>3600*24*30,
		'cookieParams' => array(
			'lifetime' => 0,
			'path' => '/',
			'domain' => COOKIE_DOMAIN,
			'httpOnly' => true,
		),
	),
	'authManager' => array(
		'class' => 'RDbAuthManager',
		'connectionID' => 'db',
		'itemTable' => 'core_authitem',
		'itemChildTable' => 'core_authitemchild',
		'assignmentTable' => 'core_authassignment',
		'rightsTable' => 'core_rights',
		'defaultRoles' => array('Authenticated', 'Guest'),
	),
	// uncomment the following to use a MySQL database
	'db' => array(
		'connectionString' => "mysql:host=localhost;dbname=english;port=3306",
		'emulatePrepare' => true,
		'username' => 'binhqd',
		'password' => '123456',
		'charset' => 'utf8',
		'tablePrefix' => '',
	),
	'coreMessages'=>array(
		'basePath' => dirname(__FILE__) . '/../../messages',
	),
	'cache'=>array(
		'class'=>'system.caching.CFileCache',
	),
	// uncomment the following to enable URLs in path-format
	'urlManager' => array(
		'urlFormat' => 'path',
		'showScriptName' => false,
		'rules' 	=> array(
			'profile/edit'						=> 'profile/edit',
			'profile/change_password'			=> 'profile/change_password',
			'recover/change_password'			=> 'users/recoverPassword',
			'profile/<username>'	=> 'user/viewByUsername',
			'user/activities'	=> 'user/activities',
			'user/<username>'	=> 'user/viewByUsername',
			
			'profile/wall/<page>'	=> 'profile/wall',
			'profile/id/<id:[a-fA-F]+>'	=> 'profile/view',
			'prototype/<action:\w+>'		=> 'prototype/default/<action>',
			
			'page/<alias:[a-zA-Z0-9\_\-]+>'=>'page/index',
			
			//'user/register'		=> 'site/functionDisabled',
			//''	=> 'publicPages/default',
			'lang/<lang:[\w]{2}>'	=> 'language/switch',
			'<controller:\w+>/<id:\d+>' => '<controller>/view',
			'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
			'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				
			'minify/<group:[^\/]+>'=>'minify/index',
			// 			'profile/<id:[a-f]+>'	=> 'profile/view'
		)
	),

	'assetManager' => array(
		'basePath' => dirname(__FILE__) . "/../../../wwwroot/assets/",
		'baseUrl' => "/assets",
		'class'=>'greennet.components.GNAssetManager',
	),
	'errorHandler' => array(
		// use 'site/error' action to display errors
		'errorAction' => 'site/error',
	),
	'log' => array(
		'class' => 'CLogRouter',
		'routes' => array(
// 			array(
// 				'class'=>'CFileLogRoute',
// 				'levels'=>'error, warning',
// 			),
			array(
				'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
				'ipFilters' => array('127.0.0.1', '192.168.1.*', '10.0.0.*'),
			),
		),
	),
	'themeManager'	=> array(
		'basePath'	=> dirname(__FILE__) . "/../../views/themes/",
	),
	'mail' => array(
		'class' => 'application.components.JLMailer',
		'transportType'=>'smtp', /// case sensitive!
		'transportOptions'=>array(
			'host'=>'smtp.gmail.com',
			'username' => '',
			'password' => '',
			'port'=>'465',
			'encryption'=>'ssl',
		),
		'viewPath' => 'application.views.mail',
		'logging' => true,
		'dryRun' => false
	),
	'file'=>array(
		'class'=>'ext.file.CFile',
	),
	'bootstrap'=>array(
		'class'=>'ext.bootstrap.components.Bootstrap'
	),
	'jlbd' => array(
		'class' => 'application.components.jlbd.JLBDLibrary',
		'plugins' => array(
			'dialog' => 'application.components.jlbd.JLBDDialog',
		),
	),
	'clientScript'=>array(
		'class'=>'ExtendedClientScript',
		'combineFiles'	=> false,
		'compressCss'	=> false,
		'compressJs'	=> false,
		'baseDir'		=> "/assets/"
	),
// 	'cache'	=> array(
// 		'class'=>'greennet.components.cache.memcache.GNMemCache',
// 		'servers'=>array(
// 			array(
// 				'host'=>'192.168.1.110',
// 				'port'=>11211,
// 				'weight'=>60,
// 			),
// 			array(
// 				'host'=>'server2',
// 				'port'=>11211,
// 				'weight'=>40,
// 			),
// 		),
// 	),
);
