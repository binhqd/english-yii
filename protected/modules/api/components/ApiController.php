<?php

/**
 * API Controller
 *
 * @author TienVV
 * @version 1.0
 */
class ApiController extends GNController {

	public function filters() {
		return array(
			array('ApiAccessFilter'),
		);
	}

	public function __construct($id, $module = null) {
		Yii::app()->onError = array($this, "handleError");
		Yii::app()->onException = (array($this, "handleException"));

		// Start Session By Access token
		$token = ApiAccessToken::requestToken();
		if ($token) {
			ApiAccessToken::startSession($token);
		} else {
			Yii::app()->session->open();
		}
		parent::__construct($id, $module);
	}

	public function handleError($event) {
		if ($event->code & error_reporting()) {
			$message = "$event->code: , $event->message ($event->file:$event->line)";
			$this->out(500, compact('message'));
		}
	}

	public function run($actionID) {
		try {
			parent::run($actionID);
		} catch (Exception $e) {
			$event = new CExceptionEvent(null , $e);
			$this->handleException($event);
		}
	}

	public function handleException($event) {
		$exception = $event->exception;
		if (isset($_GET['xdebug'])) {
			debug($exception);
		}
		if (isset($exception->statusCode)) {
			$code = intval($exception->statusCode);
		} else {
			$code = intval($exception->getCode());
		}
		if (!($code >= 100 && $code < 600)) {
			$code = 500;
		}
		$this->out($code, array(
			'message' => $exception->getMessage()
		));
	}

//	public function beforeAction($action) {
////		// login
////		$CurrentUser = currentUser();
////		if (!empty($_SERVER['PHP_AUTH_USER']) && ($CurrentUser->isGuest ||
////				$CurrentUser->email != $_SERVER['PHP_AUTH_USER'])) {
////
////			$Model = new ZoneLoginForm();
////			$Model->attributes = array(
////				'rememberMe' => 0,
////				'email' => $_SERVER['PHP_AUTH_USER'],
////				'password' => $_SERVER['PHP_AUTH_PW']
////			);
////			if (!$Model->validate() ||
////					!$Model->user->forceLogin($Model->rememberMe)) {
////				$this->send(401, array(
////					'message' => UsersModule::t('Username or password is incorrect')
////				));
////			}
////		}
//	}

	public function accessDenied($message = null) {
		if (is_null($message)) {
			$message = UsersModule::t('Oops! Access denied . You are not authorized to access that location.');
		}
		$this->out(401, array(
			'message' => $message
		));
	}

	public function out($statusCode, array $data = array(), $exit = true) {
		$data['error'] = true;
		if ($statusCode == 200 || $statusCode == 201) {
			$data['error'] = false;
		}


//		$e = explode('|', '241-137|200-120|100-65|400-200|250-140|147-96|620-375|97-60');
//		sort($e);
//		$keys = array();
//		foreach (array_unique($e) as $v) {
//				list($k) = explode('-', $v);
//				if (isset($keys[$k])) {
//					$k .= '.';
//				}
//				$keys[$k] = $v;
//		}
//		ksort($keys);
//		echo "'" . (implode("', '", $keys)) . "'";
//		exit();


		if (isset($data['cdn'])) {
			$data['cdn'] = rtrim($data['cdn'], '/') . '/';
			$data['cdn_pattern'] = array(
				'user' => array(
					'format' => 'upload/user-photos/{id}/fill/{size}/{image}?album_id={id}',
					'sizes' => array(
						'32-32', '38-38', '49-49', '150-150', '206-206',
						'230-230', '236-286', '306-372', '308-358', '400-400'
					)
				),
				'topic' => array(
					'format' => 'upload/gallery/fill/{size}/{image}?album_id={id}',
					'sizes' => array(
						'8-8', '16-16', '30-30', '38-38', '47-47',
						'96-144', '96-96', '140-140', '202-202',
						'230-230', '236-286', '240-215', '306-168',
						'308-465', '340-151', '484-215'
					)
				),
				'video' => array(
					'format' => 'upload/videos/fill/{size}/{thumbnail}?album_id={id}',
					'sizes' => array(
						'16-16', '200-160',
						'97-60', '100-65', '147-96', '200-120',
						'241-137', '250-140', '400-200', '620-375'
					)
				)
			);
		}
		$data['code'] = intval($statusCode);
		//header('HTTP/1.1 302 Page moved', true, 302);
		ajaxOut($data, $exit);
	}

	public function redirect($url, $terminate = true, $statusCode = 302) {
		if (is_array($url)) {
			$route = isset($url[0]) ? $url[0] : '';
			$url = $this->createUrl($route, array_splice($url, 1));
		}
		if (strpos($url, '/') === 0) {
			$url = Yii::app()->getRequest()->getHostInfo() . $url;
		}
		$this->out($statusCode, array(
			'location' => $url,
			'message' => UsersModule::t('The page has been moved')), $terminate);
	}

	public function initAction($actionID, array $actions = array()) {
		$action = $this->createActionFromMap($actions + $this->actions(), $actionID, $actionID);
		if ($action !== null && !method_exists($action, 'run')) {
			throw new CException(UsersModule::t('Action class {class} must implement the "run" method.'
					, array('{class}' => get_class($action))));
		}
		return $action;
	}

	public function paginate($count, $defaultLimit = 10) {
		$Paginate = new CPagination($count);
		$Paginate->validateCurrentPage = false;
		$limit = abs(Yii::app()->request->getParam('limit'));
		$Paginate->pageSize = $limit ? $limit : intval($defaultLimit);
		return $Paginate;
	}

	public function userInfo($id, $guestAllowed = false) {
		static $UserInfo = null;
		if (!empty($id) && (!$UserInfo || $UserInfo->hexID != $id)) {
			$Model = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($id));
			if (!$Model || $Model->id == -1) {
				throw new Exception(UsersModule::t('The user "{id}" is not found.', array(
					'{id}' => $id
				)));
			}
			$UserInfo = new ApiZoneUser($Model);
		} elseif (empty($id) && (!$UserInfo || $UserInfo->hexID != currentUser()->hexID)) {
			$UserInfo = new ApiZoneUser(currentUser());
			if (!$guestAllowed && $UserInfo->isGuest) {
				$this->accessDenied();
			}
		}

		return $UserInfo;
	}

	public function isValidTimestamp($timestamp) {
		return $timestamp && ($timestamp == @strtotime(date('c', $timestamp)));
	}

}