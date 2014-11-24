<?php

/**
 * GNRouter - Helper chứa các phương thức phục vụ việc quản lí url
 * 
 * @ingroup components
 * @author dunghd
 * @version 0.1
 */
class GNRouter {

	/**
	 * Phương thức dùng để tạo url
	 * @param mixed $strUrl		 
	 * @param array $param
	 * @param string $ampersand
	 * @return url
	 * example:
	 * createUrl('login',array('username' => 'user1', 'password' => 'pwd')) 
	 * --> router : /login
	 */
	public static function createUrl($strUrl, $param = array(), $ampersand='&') {
		if (!is_array($strUrl)) {
			return Yii::app()->urlManager->createUrl($strUrl, $param, $ampersand);
		} else {
			$url = "";

			if (isset($strUrl['module'])) {
				$url = $strUrl['module'];
			}

			if (isset($strUrl['controller'])) {
				$url .= '/' . $strUrl['controller'];
			}

			if (isset($strUrl['action'])) {
				$url .= '/' . $strUrl['action'];
			}

			return Yii::app()->urlManager->createUrl($url, $param, $ampersand);
		}
	}

	/**
	 * Creates an absolute URL for the specified action defined in this controller.
	 * @param string $route the URL route. This should be in the format of 'ControllerID/ActionID'.
	 * If the ControllerPath is not present, the current controller ID will be prefixed to the route.
	 * If the route is empty, it is assumed to be the current action.
	 * @param array $params additional GET parameters (name=>value). Both the name and value will be URL-encoded.
	 * @param string $schema schema to use (e.g. http, https). If empty, the schema used for the current request will be used.
	 * @param string $ampersand the token separating name-value pairs in the URL.
	 * @return string the constructed URL
	 */
	public static function createAbsoluteUrl($route, $params=array(), $schema='', $ampersand='&') {
		$url = self::createUrl($route, $params, $ampersand);
		if (strpos($url, 'http') === 0)
			return $url;
		else
			return Yii::app()->getRequest()->getHostInfo($schema) . $url;
	}

}

?>
