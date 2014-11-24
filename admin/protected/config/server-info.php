<?php
return array(
	'server1'	=> array(
		'live'	=> array(
			'mysql'		=> array(
				'server'	=> 'localhost',
				'port'		=> 3306,
				'dbname'	=> 'jl1',
				'sock'		=> '/var/lib/mysql/mysql.sock'
			),
			'sphinx'	=> array(
				'server'	=> '127.0.0.1',
				'sqlport'	=> 9306,
				'sqlsock'	=> '/data/sphinx/sphinxql.sock',
				'apiport'	=> 9312,
				'apisock'	=> '/data/sphinx/sphinxapi.sock'
			),
			'neo4j'	=> array(
				'server'	=> '127.0.0.1',
				'httpport'	=> 7474,
				'httpsport'	=> 7473
			),
			'mongodb'	=> array(
				'server'	=> '54.215.136.218',
				'port'		=> 7474,
				'dbname'	=> 'myzonedev'
			),
			'nodejs'	=> array(
				'server'	=> 'notification.justlook.com.au',
				'port'		=> '8080'
			)
		)
	),
	'server2'	=> array(
		'beta'	=> array(
			'mysql'		=> array(
				'server'	=> 'localhost',
				'port'		=> 3306,
				'dbname'	=> 'jl2',
				'sock'		=> '/var/run/mysqld/mysqld.sock'
			),
			'sphinx'	=> array(
				'server'	=> '127.0.0.1',
				'sqlport'	=> 9306,
				'sqlsock'	=> '/data/sphinx/sphinxql.sock',
				'apiport'	=> 9312,
				'apisock'	=> '/data/sphinx/sphinxapi.sock'
			),
			'neo4j'	=> array(
				'server'	=> '127.0.0.1',
				'httpport'	=> 7474,
				'httpsport'	=> 7473
			),
			'mongodb'	=> array(
				'server'	=> '54.215.136.218',
				'port'		=> 7474,
				'dbname'	=> 'myzonedev'
			),
			'nodejs'	=> array(
				'server'	=> 'notification.justlook.com.au',
				'port'		=> '8080'
			)
		)
	),
	'local'	=> array(
		'development'	=> array(
			'mysql'		=> array(
				'server'	=> '127.0.0.1',
				'port'		=> 3306,
				'dbname'	=> 'myzone',
				'sock'		=> ''
			),
			'sphinx'	=> array(
				'server'	=> '192.168.1.110',
				'sqlport'	=> 9308,
				'sqlsock'	=> '',
				'apiport'	=> 9314,
				'apisock'	=> ''
			),
			'neo4j'	=> array(
				'server'	=> 'localhost',
				'httpport'	=> 7474,
				'httpsport'	=> 7474
			),
			'mongodb'	=> array(
				'server'	=> '192.168.1.110',
				'port'		=> 27019,
				'dbname'	=> 'myzonedev'
			),
			'nodejs'	=> array(
				'server'	=> '192.168.1.109',
				'port'		=> '80'
			)
		)
	),
);