<?php

/**
 * @ingroup components
 * Base class of a data record
 */
class GNErrorLog extends CComponent {
	public static $_component;
	public static function getBrowser($userAgent) {
		Yii::import("application.extensions.browser.CBrowserComponent");
		self::$_component = new CBrowserComponent();
	
		self::$_component->setUserAgent($userAgent);
		//$browser = self::$_component->getBrowser() . " " . self::$_component->getVersion();
		return array(
			'platform'	=> self::$_component->getBrowser(),
			'version'	=> self::$_component->getVersion()
		);
	}
}
