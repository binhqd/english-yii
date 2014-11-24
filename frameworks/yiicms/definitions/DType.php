<?php
class DType extends CComponent {
	private static $_instances;
	public static function getInstance($class = __CLASS__) {
		if (!isset(self::$_instances[$class])) {
			self::$_instances[$class] = new $class;
		}
		return self::$_instances[$class];
	}
	public static function test() {
		//exit('yes');
	}
	
	public function renderFields() {
		dump($this->fields());
	}
}