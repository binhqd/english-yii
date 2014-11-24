<?php
return array(
	// me controller
	array('pattern' => 'collections/<parentId>/<sub>', 'collections/listSubItems','verb' => 'GET'),

	// others

	// common
	'page/<alias:[a-zA-Z0-9\_\-]+>'			=> 'page/index',
	'dashboard/index'						=> 'dashboard/review',
	'lang/<lang:[\w]{2}>'					=> 'language/switch',
	'<controller:\w+>/<id:\d+>'				=> '<controller>/view',
	'<controller:\w+>/<action:\w+>/<id:\d+>'=> '<controller>/<action>',
	'<controller:\w+>/<action:\w+>'			=> '<controller>/<action>',
	'minify/<group:[^\/]+>'					=> 'minify/index',
);