<?php
/**
 * This class still not in development yet
 * @author BinhQD
 *
 */
class GNMemcacheBehavior extends CBehavior {
	public $prefix = '';
	private $_cache = null;
	
	public function getInstance() {
		
		if (empty($this->_cache))
			$this->_cache = Yii::app()->cache;
// 		$this->_cache->flush();
		return $this->_cache;
	}
	
	/**
	 * 
	 * @param unknown_type $key
	 * @param unknown_type $value
	 */
	public function set($key, $value) {
		$this->instance->set("{$this->prefix}{$key}", $value);
	}
	
	/**
	 * 
	 * @param unknown_type $key
	 */
	public function get($key) {
		$val = $this->instance->get("{$this->prefix}{$key}");
		
		return $val;
	}
	
	public function delete($key) {
		$this->instance->delete("{$this->prefix}{$key}");
	}
}