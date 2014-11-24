<?php
class ZoneRouter extends GNRouter {
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
	public static function CDNUrl($route, $params=array(), $schema='', $ampersand='&') {
		$url = self::createUrl($route, $params, $ampersand);
		if (strpos($url, 'http') === 0)
			return $url;
		else
			return Yii::app()->params['AWS']['CDN'] . $url;
	}
}