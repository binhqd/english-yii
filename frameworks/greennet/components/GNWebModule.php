<?php

// Fix for php < 5.3
if ( !function_exists( 'get_called_class' ) ) {
	function get_called_class ()
	{
		foreach ( debug_backtrace() as $trace ) {
			if ( isset( $trace['object'] ) )
				if ( $trace['object'] instanceof $trace['class'] )
					return get_class( $trace['object'] );
		}

		return false;
	}
}

/**
 * Component dùng chung cho toàn bộ controller của hệ thống, dùng để thiết lập các phương thức, thuộc tính mặc định
 * 
 * @ingroup components
 * @class	   JLController
 * @author	  huytbt
 * @version	 1.0
 * @date		2011-05-23
 */
class GNWebModule extends CWebModule {
	/**
	 * 
	 * Biến Config, sử dụng cho ext.config.FileConfig ...
	 * @var unknown_type
	 */
	public $Config = null;
	
	/**
	 * 
	 * Phương thức được sử dụng cho việc xử lý ngôn ngữ
	 * @param unknown_type $message
	 */
	public static function t($message, $params=array(), $source=null, $language=null) {
		return Yii::t(get_called_class(), $message, $params, $source, $language);
	}
}