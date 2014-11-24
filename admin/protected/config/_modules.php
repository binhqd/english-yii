<?php

return array(
	'users',
	'status',
	//'demo',	
	'feedback',
	'rights' => array(
		'debug' => true,
		'install' => false,
		'enableBizRuleData' => true,
		'userClass' => 'GNUser',
		'superuserName' => 'SAdmin',
		'layout' => '//layouts/main',
	),
	'gii' => array(
		'class' => 'system.gii.GiiModule',
		'password' => '12345',
		'generatorPaths' => array(
			'ext.bootstrap.gii',
			'ext.gtc' // a path alias
		),
		// If removed, Gii defaults to localhost only. Edit carefully to taste.
		'ipFilters' => array('127.0.0.1', '::1'),
	),
	'garbage',
	'mobile',
	'validation_codes',
	'registration',
	'articles',
	'reports',
);