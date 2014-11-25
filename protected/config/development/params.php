<?php

// this contains the application parameters that can be maintained via GUI
return array(
	// this is displayed in the header section
	'title' => 'Justlook',
	// this is used in error pages
	'adminEmail' => 'binhqd@gmail.com',
	'systemSalt' => 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi',
	'sitename' => "English",
	'mailer' => array(
		'host' => 'smtp.gmail.com',
		'port' => '587',
		'secure' => 'tls',
		'username' => '',
		'password' => '',
		'name' => 'English',
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
	'notificationServer' => 'http://192.168.1.109',
	'gearmanPort' => '5900',
	'cacheResetTime' => 1800,
	'mailContact' => array(
		'mail' => 'binhqd@gmail.com'
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
			'application_name' => '',
			'oauth2_client_id' => '',
			'oauth2_client_secret' => '',
			'site_name' => '',
			'oauth2_redirect_uri'	=> '',
		),
		'Yahoo'		=> array(
			'app_id'			=> 'M05K607i',
			'consumer_key'		=> '',
			'consumer_secret'	=> '',
			'connected_path' 	=> '',
			're_url'			=> ""
		),
		'Facebook'	=> array(
			'appId'  => '',
			'secret' => '',
			'cookie' => true
		)
	),
	//'CDN'	=> 'http://d1synugzxoq5oj.cloudfront.net/'
	//'CDN'	=> '/'
	'AWS'	=> array(
		'CDN'	=> '',
		'S3URL'	=> '',
		'S3'	=> array(
			'upload'	=> array(
				'accessKey'	=> '',
				'secretKey'	=> '',
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
