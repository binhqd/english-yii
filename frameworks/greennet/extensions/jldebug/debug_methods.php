<?php 
class GNDebugger {
	public static $watch = array();
}

function debug($obj, $exit = true) {
	$_REQUEST['call_debuging'] = true;
	$path = realpath(dirname(__FILE__) . "/../../views/common/debug.php");
	include($path);

	!$exit or Yii::app()->end();
}

function watch($obj, $showLines = true) {
	$backtrace = debug_backtrace();
	$files = array();
	$lineToGet = 20;
	
	$cnt = 0;
	foreach ($backtrace as $item) {
		if (!isset($item['file']) || !isset($item['line'])) continue;
		$files[] = "{$item['file']} ({$item['line']})";
		if ($cnt++ == ($lineToGet - 1))
			break;
	}
	
	$data = array($obj, implode("<br/>", $files), $showLines);
	GNDebugger::$watch[] = $data;
	
	$runtimePath = Yii::app()->runtimePath;
	file_put_contents($runtimePath . "/watches.txt", base64_encode(serialize($data)) . "\n", FILE_APPEND);
}