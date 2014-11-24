<?php

/**
 * Component dùng chung cho toàn bộ controller của hệ thống, dùng để thiết lập các phương thức, thuộc tính mặc định
 * 
 * @ingroup components
 * @class	   JLController
 * @author	  huytbt
 * @version	 1.0
 * @date		2011-05-23
 */
class GNi18n {
	/**
	 * 
	 * Phương thức được sử dụng cho việc xử lý ngôn ngữ
	 * @param unknown_type $message
	 */
	public static function t($message, $params=array(), $source=null, $language=null) {
		return Yii::t(__CLASS__, $message, $params, $source, $language);
	}
}