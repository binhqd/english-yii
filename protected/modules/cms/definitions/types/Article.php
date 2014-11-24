<?php
class Article extends DType {
	public static function getInstance($class = __CLASS__) {
		return parent::getInstance($class);
	}
	public function fields() {
		return array(
			'title'	=> array(
				'type'	=> 'text'
			),
			'description'	=> array(
				'type'	=> 'longtext'
			)
		);
	}
}