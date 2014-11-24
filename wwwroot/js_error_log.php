<?php
require(dirname(__FILE__) . '/../../jlprotected/config/constants.php');

$sessSavePath = realpath('../../jlruntime/session/');
session_save_path($sessSavePath);
session_start();

$sess = $_SESSION;
$pattern = "/[a-fA-F0-9]+__name$/";
$username = "Guest";
foreach ($sess as $key => $value) {
	if (preg_match($pattern, $key, $matches)) {
		$username = $value;
		break;
	}
}


$content = "\n".$_GET['msg'];
if (isset($_SERVER['HTTP_REFERER'])) {
	//$content .= "\t\t\t(R: ".$_SERVER['HTTP_REFERER'].")";
}

// init connection
$info = require(dirname(__FILE__) . '/../../jlprotected/config/server-info.php');
$YiiComponentConfigs = require(dirname(__FILE__) . '/../../jlprotected/config/' .APPLICATION_ENV. '/_common_components.php');
$mongoDBConfig = $YiiComponentConfigs['mongodb'];
$m = new Mongo($mongoDBConfig['connectionString']); // connect
$db = $m->selectDB($mongoDBConfig['dbName']);
$collection = new MongoCollection($db, 'jl_error_logs');

if (!isset($_SERVER['HTTP_REFERRER'])) $_SERVER['HTTP_REFERRER'] = '';

function clean($str) { 
  $search  = array('&'    , '"'     , "'"    , '<'   , '>'    ); 
  $replace = array('&amp;', '&quot;', '&#39;', '&lt;', '&gt;' ); 

  $str = str_replace($search, $replace, $str); 
  return $str; 
}

//require_once '../../jlprotected/components/db/JLLog.php';
$data = array(
	"level"				=> 'logs',
	"category"			=> 'JS Error',
	"logtime"			=> time(),
	"message"			=> clean("{$_REQUEST['msg']} at {$_REQUEST['url']} on line {$_REQUEST['line']}"),
	"uri"				=> $_REQUEST['url'],
	"stack_trace"		=> clean($content),
	"referrer"			=> $_SERVER['HTTP_REFERER'],
	"user"				=> $username,
	"ip"				=> $_SERVER['REMOTE_ADDR'],
	"user_agent"		=> $_SERVER['HTTP_USER_AGENT'],
	"request_method"	=> $_SERVER['REQUEST_METHOD'],
	"browser_name"		=> ''
);
$collection->save($data);
exit("{$_REQUEST['msg']} at {$_REQUEST['url']} on line {$_REQUEST['line']}");