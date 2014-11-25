<?php


/**
 * ZoneApiResponse
 *
 * @author huytbt <huytbt@gmail.com>
 */
class ZoneApiResponse extends CApplicationComponent
{
	private static $_runtime = array(
		'init' => false,
		'data' => array(),
		'cache' => array(),
		'url' => ''
	);
	private static $_httpStatusCodes = array(
		'100' => 'Continue',
		'101' => 'Switching Protocols',
		'102' => 'Processing (WebDAV)',
		'200' => 'OK',
		'201' => 'Created',
		'202' => 'Accepted',
		'203' => 'Non-Authoritative Information',
		'204' => 'No Content',
		'205' => 'Reset Content',
		'206' => 'Partial Content',
		'207' => 'Multi-Status (WebDAV)',
		'208' => 'Already Reported (WebDAV)',
		'226' => 'IM Used',
		'300' => 'Multiple Choices',
		'304' => 'Not Modified',
		'400' => 'Bad Request',
		'401' => 'Unauthorized',
		'402' => 'Payment Required',
		'403' => 'Forbidden',
		'404' => 'Not Found',
		'405' => 'Method Not Allowed',
		'406' => 'Not Acceptable',
		'407' => 'Proxy Authentication Required',
		'408' => 'Request Timeout',
		'409' => 'Conflict',
		'500' => 'Internal Server Error',
		'501' => 'Not Implemented',
		'502' => 'Bad Gateway',
		'503' => 'Service Unavailable',
		'504' => 'Gateway Timeout',
	);

	/**
	 * This method is used to response api result to client
	 *
	 * @param int	 $httpStatusCode httpStatusCode
	 * @param array   $data		   data
	 * @param string  $message		message
	 * @param array   $customHeaders  customHeaders
	 * @param boolean $exit		   exit
	 *
	 * @return void
	 */
	public function send($httpStatusCode, $data = array() , $message = '', $customHeaders = array() , $exit = true)
	{
		$headers = array(
			'Cache-Control' => "private, post-check=0, pre-check=0, must-revalidate",

			//		  'Expires'	   => gmdate('D, d M Y H:i:s \G\M\T', time() + 3600),

			//		  'Last-Modified' => gmdate('D, d M Y H:i:s \G\M\T', strtotime("2014-05-30 16:32:12"))


		);
		if (isset($data['Etag'])) {
			$headers["ETag"] = $data['Etag'];
		}
		header_remove("Pragma");
		header_remove("X-Powered-By");
		header_remove("Set-Cookie");
		header_remove("Keep-Alive");
		header_remove("Server");
		header('Vary: Accept');
		if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
			//header('Content-type: application/json');
		} else {
			//header('Content-type: application/json');
		}

		// Add headers
		foreach ($headers as $key => $value) {
			header("{$key}: {$value}");
		}
		if (strtoupper(ApiAccess::$method) == "OPTIONS") {
			$responseCode = 200;
			$status_header = 'HTTP/1.1 ' . $responseCode . ' ' . self::$_httpStatusCodes[$responseCode];
			header($status_header);
			die;
		} else if (isset(ApiAccess::$headers['If-None-Match']) && isset($data['Etag']) && $data['Etag'] == ApiAccess::$headers['If-None-Match']) {
			// if isset ETag and Etag equal with If-None-Match
			$responseCode = 304;
			$status_header = 'HTTP/1.1 ' . $responseCode . ' ' . self::$_httpStatusCodes[$responseCode];
			header($status_header);
			die;
		} else {
			if ($message == '' && isset(self::$_httpStatusCodes[$httpStatusCode])) {
				$message = self::$_httpStatusCodes[$httpStatusCode];
			}

			/**
			 Support Partitions & Fields
			 */
			$isDoAction = self::$_runtime['init'];
			$isGetSuccess = is_array($data) && count($data) && ApiAccess::$method == 'GET';
			// Support Fields
			if ($isGetSuccess && ($fields = $this->getFields())) {
				$fields['id'] = 0;
				if (isset($data['items'])) {
					foreach ($data['items'] as &$item) {
						$item = array_intersect_key($item, $fields);
					}
				} else {
					$item = reset($data);
					$data[key($data)] = array_intersect_key($item, $fields);
				}
			}

			// Support Partitions
			if ($isGetSuccess && ($parts = $this->getParts('parts'))) {
				if (!$isDoAction) {
					self::$_runtime['init'] = true;
					$offset = strpos(Yii::app()->request->requestUri, '?');
					self::$_runtime['url'] = (string) substr(Yii::app()->request->requestUri, 0, $offset);
				}
				self::$_runtime['url'] = trim(self::$_runtime['url'], '/');

				$partAlias = Yii::app()->params['partAlias'];
				$paths = explode('/', self::$_runtime['url']);
				$alias = end($paths);

				$hasAlias = false;
				if (isset($partAlias[$alias])) {
					$resource = $partAlias[$alias];
					$hasAlias = true;
				} else {
					$resource = self::$_runtime['url'];
				}

				if (isset($data['items'])) {
					foreach ($data['items'] as &$item) {
						$item = $this->_parseData($item, $parts, $resource, $hasAlias);
					}
				} else {
					$item = reset($data);
					$data[key($data)] = $this->_parseData($item, $parts, $resource, $hasAlias);
				}
			}
			
			if ($httpStatusCode == 200 && $isDoAction) {
				self::$_runtime['data'] = $data;
				return;
			}
			/**
			 Support Partitions & Fields
			 */

			if ($httpStatusCode < 100 || $httpStatusCode > 599) {
				$httpStatusCode = 500;
			}
			$response = array(
				'meta' => array(
					'code' => $httpStatusCode,
					'message' => $message,
					'version' => API_VERSION,
					'encoding' => 'UTF-8',
					'language' => 'en',
				) ,
			);
			if (!empty($data)) {
				$response['data'] = $data;
			}
		}
		$obj = @CJSON::encode($response);
		if (!empty($customHeaders)) {
			foreach ($customHeaders as $key => $value) {
				header("{$key}: {$value}");
			}
		}
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
			$isAcceptGzipEncoding = stripos($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip");
		} else {
			$isAcceptGzipEncoding = - 1;
		}
		if ($isAcceptGzipEncoding >= 0) {

			//
			$gzContent = gzencode($obj, 5);
			if ($gzContent) {
				header('Content-Encoding: gzip');
				header('Vary: Accept-Encoding');
				header("Content-Length: " . strlen($gzContent));
				echo $gzContent;
				@ob_end_flush();
			} else {
				if ($isAcceptGzipEncoding !== false) {
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
	 * Get fields
	 *
	 * @param boolean $flip flip
	 *
	 * @return array
	 */
	public function getFields($flip = true)
	{
		$fields = Yii::app()->request->getQuery('fields');
		if (!is_array($fields)) {
			$fields = preg_split('/[\s,]+/', $fields, -1, PREG_SPLIT_NO_EMPTY);
		}
		if (!$flip) {
			return $fields;
		}
		return array_flip($fields);
	}

	/**
	 * Get parts
	 *
	 * @return array
	 */
	public function getParts()
	{
		$jsonDecode = function ($s) {
			if (is_array($s)) {
				return $s;
			}
			$arr = @json_decode($s, true);
			if (!empty($arr)) {
				return $arr;
			}
			$s = str_replace(array('"', "'"), array('\"', '"'), $s);
			$s = preg_replace('/(\w+):/i', '"\1":', $s);
			$s = preg_replace('/:(\w+)/i', ':"\1"', $s);
			return @json_decode($s, true);
		};
		$parts = $jsonDecode(Yii::app()->request->getQuery('parts'));
		if (!is_array($parts) || count($parts) == 0) {
			return null;
		}
		return $parts;
	}

	/**
	 * Parse data
	 *
	 * @param array  $data	 data
	 * @param array  $parts	parts
	 * @param string $resource resource
	 * @param string $hasAlias hasAlias
	 *
	 * @return array
	 */
	private function _parseData($data, $parts, $resource, $hasAlias)
	{
		if ($hasAlias) {
			if (isset($data['id'])) {
				$key = 'id';
			}
			if (isset($data['part_uri'])) {
				$key = 'part_uri';
			}
			$requestURL = $resource . '/' . $data[$key];
		} else {
			if (isset($data['part_uri'])) {
				$key = 'part_uri';
				$requestURL = $data[$key];
			} else {
				$requestURL = $resource;
			}
		}
		$requestURL = '/' . trim($requestURL, '/') . '/';
		foreach ($parts as $part => $params) {
			$serverBackup = $_SERVER;
			$getBackup = $_GET;
			$requestBackup = Yii::app()->request;
			$_SERVER['REQUEST_URI'] = $requestURL . $part;
			$_SERVER['QUERY_STRING'] = http_build_query($params);
			$_GET = $this->_parserParams($params);
			$request = Yii::createComponent(
				array(
					'class' => 'CHttpRequest',
				)
			);
			$route = Yii::app()->urlManager->parseUrl($request);
			if (empty($route)) {
				continue;
			}
			self::$_runtime['url'] = $_SERVER['REQUEST_URI'];

			Yii::app()->setComponent('request', $request);
			Yii::app()->runController($route);
			$_SERVER  = $serverBackup;
			$_GET  = $getBackup;
			Yii::app()->setComponent('request', $requestBackup);

			if (isset(self::$_runtime['data']['items'])) {
				$data['parts'][$part] = self::$_runtime['data'];
			} else {
				$key = array_keys(self::$_runtime['data']);
				if (!empty($key[0]) && !empty(self::$_runtime['data'][$key[0]]) && $part == $key[0]) {
					$data['parts'][$part] = self::$_runtime['data'][$key[0]];
				} else {
					$data['parts'][$part] = self::$_runtime['data'];
				}
			}
			self::$_runtime['data'] = array();
		}
		return $data;
	}

	/**
	 * Parser Params
	 *
	 * @param array $params Parameters
	 *
	 * @return array
	 */
	private function _parserParams($params)
	{
		if (isset($params['parts'])) {
			$params['parts'] = json_encode($params['parts']);
		}
		return $params;
	}
}
