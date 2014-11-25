<?php


/**
 * Class that handling error, logging them with mongoDB
 * @author BinhQD
 *
 */
Yii::import('greennet.modules.error_handling.models.GNMongoErrorLog');
Yii::import('greennet.modules.error_handling.components.*');
class ZoneApiErrorHandler extends GNErrorHandler
{
	protected static $_collection = null;
	protected static $_db = null;
	public static function dbName()
	{
		return 'jl_error_logs';
	}
	public static function db()
	{
		if (empty(self::$_db)) {
			$connectionString = Yii::app()->mongodb->connectionString;
			$dbName = Yii::app()->mongodb->dbName;
			$m = new MongoClient($connectionString); // connect

			self::$_db = $m->selectDB($dbName);
		}
		return self::$_db;
	}
	public static function collection()
	{
		if (empty(self::$_collection)) {
			try {
				self::$_collection = new MongoCollection(self::db() , self::dbName());
			}
			catch(Exception $ex) {

				// debug($ex->getMessage());
				Yii::app()->response->send(500, array() , $ex->getMessage());
			}
		}
		return self::$_collection;
	}

	/**
	 * This method is used to display error after log them
	 * @param unknown_type $error
	 */
	protected static function showError($error)
	{
		Yii::app()->response->send($error['code'], (YII_DEBUG && ($error['code'] < 100 || $error['code'] > 599)) ? array('error'=>$error) : array() , $error['message']);

		//	  $errorPage = "404";
		
		//	  $redirectUrl = "/";

		
		//	  if ($error['code'] == 500 || $error['code'] == 0) {

		
		//		  $errorPage = 500;

		
		//		  $redirectUrl = "/error-docs/{$errorPage}";

		
		//	  } else {

		
		//		  $errorPage = 404;

		
		//		  $redirectUrl = "/error-docs/{$errorPage}?url=" . urlencode(Yii::app()->request->requestUri);

		
		//	  }

		
		//	  Yii::app()->request->redirect($redirectUrl);

		
		//	  Yii::app()->end();

		
	}

	/**
	 *
	 * @param unknown_type $err
	 */
	public static function handlingError($err)
	{
		if(error_reporting() == 0){
			return;
		}
		
		$err = array(
			'error' => 1,
			'message' => $err->message,
			'code' => $err->code,
			'file' => $err->file,
			'line' => $err->line
		);

		/*if ($event->code & error_reporting()) {
		$message = "$event->code: $event->message ($event->file:$event->line)";
		$this->sendResponse(500, array(), $message);
		}*/
		$error = self::saveError($err);
		self::showError($error);
	}
	public static function handlingException($event)
	{
		$code = 0;
		if (isset($event->exception)) {
			$ex = $event->exception;
			if (get_class($ex) == "Exception" || get_parent_class($ex) == "Exception") {
				$code = $ex->getCode();
			} else {
				if (isset($ex->statusCode)) {
					$code = $ex->statusCode;
				} else {
					$code = 500;
				}
			}
		} else {
			$ex = $event;
			$code = $ex->getCode();
		}
		$err = array(
			'error' => 1,
			'message' => $ex->getMessage() ,
			'code' => $code,
			'file' => $ex->getFile() ,
			'line' => $ex->getLine() ,

			//'trace'	   => debug_backtrace()
			
		);

		/*
		$exception = $event->exception;
		if (isset($_GET['xdebug'])) {
		debug($exception);
		}
		if (isset($exception->statusCode)) {
		$code = intval($exception->statusCode);
		} else {
		$code = intval($exception->getCode());
		}
		if (!($code >= 100 && $code < 600) || !isset($this->_httpStatusCodes[$code])) {
		$code = 500;
		}
		$this->sendResponse($code, array(),  $exception->getMessage());
		*/
		$error = self::saveError($err);
		self::showError($error);
	}

	/**
	 * This method is used to save error to mongo document
	 * @param array $err
	 */
	private static function saveError(array $err)
	{
		$err['uri'] = Yii::app()->request->requestUri;
		$err['referrer'] = Yii::app()->request->urlReferrer;
		$err['logtime'] = time();
		$err['ip'] = Yii::app()->request->userHostAddress;
		$err['user_agent'] = Yii::app()->request->userAgent;
		$err['request_method'] = Yii::app()->request->requestType;
		if (strtoupper($err['request_method']) == "POST" && !empty($_POST)) {
			$err['post_data'] = $_POST;
		} else {
			$err['post_data'] = array();
		}
		$err['browser'] = GNErrorLog::getBrowser($err['user_agent']);
		$backtrace = debug_backtrace();
		$traces = array();
		$cnt = 0;
		foreach ($backtrace as $item) {
			if ($cnt == 0) {
				$cnt++;
				continue;
			}
			if (isset($item['file']) && isset($item['line'])) {
				$traces[] = "{$item['file']} ({$item['line']}):";
			} else {
				$traces[] = "{$item['class']}{$item['type']}{$item['function']}";
			}

			//$traces[] = $item;
			
		}
		$err['traces'] = $traces;

		//dump(self::$_db);
		$collection = self::collection();
		self::collection()->save($err);
		return $err;
	}
	public static function groupErrorsByFileAndLine()
	{
		$command = "return db." . self::dbName() . ".group({
			key: { file: 1, line: 1 },
			cond: {  },
			reduce: function ( curr, result ) {
				result.total += 1;
				result.message = curr.message;
				result.traces = curr.traces;
			},
			initial: {
				total : 0
			}
		});";
		$res = self::db()->execute($command);
		return $res;
	}
	public static function removeErrorsByFileAndLine($file, $line)
	{
		$command = "db." . self::dbName() . ".remove({
			file : '" . addslashes($file) . "',
				line : {$line}
	});";

		//	  exit($command);
		$res = self::db()->execute($command);
	}
	public static function listErrorsByFileAndLine($file, $line)
	{
		$command = "return db." . self::dbName() . ".group({
			key: { uri: 1, request_method: 1 },
			cond: {
				file : '" . addslashes($file) . "',
					line : {$line}
	},
	reduce: function ( curr, result ) {
	result.total += 1;
	result.items[result.items.length] = {
	message : curr.message,
	referrer : curr.referrer,
	ip : curr.ip,
	browser : curr.browser,
	traces : curr.traces,
	logtime : curr.logtime
	};
	result.code = curr.code;
	},
	initial: {
	total : 0,
	items : []
	}
	});";
		$res = self::db()->execute($command);
		return $res;
	}
	public static function removeErrorsByGroup($file, $line, $uri, $method)
	{
		if (empty($uri)) {
			$uriConds = "{ \$or : ['', null]}";
		} else {
			$uriConds = "'{$uri}'";
		}
		$command = "db." . self::dbName() . ".remove({
			file : '" . addslashes($file) . "',
				line : {$line},
	uri : {$uriConds},
	request_method : '{$method}'
		});";

		//			  exit($command);
		$res = self::db()->execute($command);
	}
}
