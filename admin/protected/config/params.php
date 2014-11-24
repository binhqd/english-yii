<?php

// this contains the application parameters that can be maintained via GUI
return array(
	// this is displayed in the header section
	'title' => 'Justlook',
	// this is used in error pages
	'adminEmail' => 'dunghd@toancauxanh.vn',
	'systemSalt' => 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi',
	'sitename' => "JustLook",
	'mailer' => array(
		'host' => 'smtp.gmail.com',
		'port' => '587',
		'secure' => 'tls',
		'username' => 'justlook.com.au@gmail.com',
		'password' => 'toancauxanh',
		'name' => 'JustLook',
	),
	'roles' => array(
		'SADMIN' => 'Super Administrator',
		'ADMIN' => 'Admin',
		'BUSINESS' => 'Business',
		'AWAITING' => 'Awaiting',
		'MEMBER' => 'Member'
	),
	'formats' => array(
		'date' => 'd M Y',
		'datetime' => 'd M Y H:i',
	),
	// the copyright information displayed in the footer section
	'copyrightInfo' => 'Copyright &copy; 2012 by Green Global.',
	// FB APP ID
	'fbAppId' => '453345281375610',
	// FB APP Secret
	'fbAppSecret' => 'd0bae04b728117e36d4511001c3782bc',
	// Justlook Page ID
	'fbPageId' => '303761709659238',
	// FB admin
	'fbAdmin' => '100003109063760',
// 	'serverSphinx' => $info['local']['development']['sphinx']['server'],
// 	'connectSphinx' => "mysql:host={$info['local']['development']['sphinx']['server']};port={$info['local']['development']['sphinx']['sqlport']}",
// 	'notificationServer'	=> 'http://49.156.53.64:7475'
	'notificationServer' => 'http://192.168.1.109',
//	'notificationServer'	=> 'http://dev.look.vn:8080'
	'gearmanPort' => '5900',
	'cacheResetTime' => 1800,
	'mailContact' => array(
		'mail' => 'contact@justlook.com.au'
	),
	'mailToYoulook' => array(
		'toan'	=> 'toannq@webdev.vn',
	),
	'device' => array(
		'type' => 'pc'
	),
	'neo4j' => array(
		'host' => '192.168.1.110',
		'port' => '7485',
		//'host' => 'localhost',
		//'port' => '7474',
		'username' => '',
		'password' => '',
		'https' => false,
		//'enableProfiling' => false
	),
	'OAuth'	=> array(
		'Gmail'	=>	array(
			'application_name' => 'MyZone',
			'oauth2_client_id' => '728945824814.apps.googleusercontent.com',
			'oauth2_client_secret' => 'FEa42tDv2P2GorOwTJfvpLmf',
			'site_name' => 'myzone.localhost.com',
			'oauth2_redirect_uri'	=> 'http://myzone.localhost.com/google/connect',
		),
		'Yahoo'		=> array(
			'app_id'			=> 'M05K607i',
			'consumer_key'		=> 'dj0yJmk9M1ZSSE80Yjg1Z0x0JmQ9WVdrOVRUQTFTell3TjJrbWNHbzlNamM1TlRnM056WXkmcz1jb25zdW1lcnNlY3JldCZ4PTA0',
			'consumer_secret'	=> '90f1687ff4b12e48a33c177765c938adaddb5c5d',
			'connected_path' 	=> 'Connected.php',
			're_url'			=> "Connected.php"
		),
		'Facebook'	=> array(
			'appId'  => '141173296063550',
			'secret' => '071334240e16b3237b825ca78ae2d94b',
			'cookie' => true
		)
	),
	//'CDN'	=> 'http://d1synugzxoq5oj.cloudfront.net/'
	//'CDN'	=> '/'
	'AWS'	=> array(
		'CDN'	=> 'http://myzone.localhost.com',
		'S3URL'	=> 'http://static.youlook.net',
		'S3'	=> array(
			'upload'	=> array(
				'accessKey'	=> 'AKIAJVYJA77SWHNFNDJA',
				'secretKey'	=> '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',
				'bucket'	=> 'static.youlook.net',
			)
		)
	),
	'Auth'	=> array(
		'API'	=> array(
			'realm'	=> "My Realm"
		)
	)
);
