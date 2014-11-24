<?php
/**
 * Greennet bootstrap file.
 *
 * @author Binh Quan <binhqd@gmail.com>
 * @package greennet
 * @since 1.0
 */

require(dirname(__FILE__).'/GNBase.php');
require_once(dirname(__FILE__) . "/extensions/jldebug/debug_methods.php");

/**
 * Yii is a helper class serving common framework functionalities.
 *
 * It encapsulates {@link YiiBase} which provides the actual implementation.
 * By writing your own Yii class, you can customize some functionalities of YiiBase.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system
 * @since 1.0
 */
class Greennet extends GNBase
{
}




/**
 * this method is used to get current user
*/
function currentUser() {
	if (isset(GNUser::$currentUser)) {
		// 		watch('1');
		return GNUser::$currentUser;
	} else if (isset(Yii::app()->user->model)) {
		// 		watch('2');
		$hexID = Yii::app()->user->model->hexID;
		$cookieName = "userInfoCache-{$hexID}";
		$cookies = Yii::app()->request->cookies;

		if (isset($cookies[$cookieName])) {
			$since = time() - $cookies[$cookieName]->value;
			if ($since > Yii::app()->params['cacheResetTime']) {
				$binUserID = IDHelper::uuidToBinary($hexID);
				$user = new GNUser();
				$user = $user->loadFromCache($binUserID);
				// is current user && not synch
				$user->updateState(true, false);
			}
		}


		GNUser::$currentUser = Yii::app()->user->model;
		GNUser::$loadedUsers[$hexID] = GNUser::$currentUser;
		return GNUser::$currentUser;
	} else {
		// 		watch('3');
		$user = new GNUser();
		$user->id = -1;
		$user->displayname = "Guest";

		GNUser::$currentUser = $user;
		return GNUser::$currentUser;
	}
}


function jlOut($obj, $dataType = 'json', $exit = true) {
	// dataType: json, text
	error_reporting(0);

	$obj = @CJSON::encode($obj);
	

	header('Connection: close');
	
	switch ($dataType) {
		case 'json':
			header("Content-type: application/json");
			break;
		case 'text':
			//header("Content-type: application/json");
			break;
	}
	
	$isAcceptGzipEncoding = stripos($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip");
	if ($isAcceptGzipEncoding >= 0) {
	//
		$gzContent = gzencode($obj, 5);
		if ($gzContent) {
			header('Content-Encoding: gzip');
			header('Vary: Accept-Encoding');
			header("Content-Length: ".strlen($gzContent));
			echo $gzContent;
			@ob_end_flush();
		} else {
			if (stripos($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip") !== false) {
				header('Content-Encoding: gzip');
				header('Vary: Accept-Encoding');
				ob_start("ob_gzhandler");
			} else {
				ob_start();
			}
	
			echo $obj;
			$size = ob_get_length();
			//ob_end_flush();
	
			header("Content-Length: {$size}");
	
			@ob_end_flush();
			@ob_flush();
	
		}
	} else {
		header("Content-Length: " . strlen($obj));
		echo $obj;
	}

	@flush();

	if ($exit) {
		if (YII_DEBUG) exit();
		else Yii::app()->end();
	} else {
		$session_id = session_id();
		if (session_id()) session_write_close();
		return $session_id;
	}
}
/**
 * This method is used to output a json string and terminate current process
 */
function jsonOut($obj, $exit = true) {
	jlOut($obj, 'json', $exit);
}


// function setHeader() {
// 	header('Vary: Accept');
// 	if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
// 		header('Content-type: application/json');
// 	} else {
// 		header('Content-type: text/plain');
// 	}
// }

function dump($obj,$isExit = true) {
	CVarDumper::dump($obj,10,true);
	if ($isExit) exit();
}

function ajaxOut($out, $exit = true) {
	$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	if (!preg_match("/MSIE/", $userAgent)) {
		jsonOut($out, $exit);
	} else {
		jlOut($out, 'text', $exit);
	}
}

/** dunghd add alias function for reduct typing 07/11/2012 */
/**
 * This is the shortcut to DIRECTORY_SEPARATOR
 */
defined('DS') or define('DS',DIRECTORY_SEPARATOR);

/**
 * This is the shortcut to Yii::app()
*/
function app()
{
	return Yii::app();
}

/**
 * This is the shortcut to Yii::app()->clientScript
 */
function cs()
{
	// You could also call the client script instance via Yii::app()->clientScript
	// But this is faster
	return Yii::app()->getClientScript();
}

/**
 * This is the shortcut to Yii::app()->createUrl()
 */
function url($route,$params=array(),$ampersand='&')
{
	return Yii::app()->createUrl($route,$params,$ampersand);
}

/**
 * This is the shortcut to CHtml::encode
 */
function h($text)
{
	return htmlspecialchars($text,ENT_QUOTES,Yii::app()->charset);
}

/**
 * This is the shortcut to CHtml::link()
 */
function l($text, $url = '#', $htmlOptions = array())
{
	return CHtml::link($text, $url, $htmlOptions);
}

/**
 * This is the shortcut to Yii::t() with default category = 'stay'
 */
function t($message, $category = 'stay', $params = array(), $source = null, $language = null)
{
	return Yii::t($category, $message, $params, $source, $language);
}

/**
 * This is the shortcut to Yii::app()->request->baseUrl
 * If the parameter is given, it will be returned and prefixed with the app baseUrl.
 */
function bu($url=null)
{
	static $baseUrl;
	if ($baseUrl===null)
		$baseUrl=Yii::app()->getRequest()->getBaseUrl();
	return $url===null ? $baseUrl : $baseUrl.'/'.ltrim($url,'/');
}

/**
 * Returns the named application parameter.
 * This is the shortcut to Yii::app()->params[$name].
 */
function param($name)
{
	return Yii::app()->params[$name];
}