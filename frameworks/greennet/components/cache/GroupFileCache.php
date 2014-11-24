<?php
namespace Gateway\Components;
class K36FileCache {
	public $cachePath;
	public function set($key, $value) {
		file_put_contents($this->cachePath . "/{$key}", serialize($value));
	}
	
	public function get($key) {
		$path = $this->cachePath . "/{$key}";
		if (is_file($path)) {
			$content = file_get_contents($path);
			return unserialize($content);
		} else {
			return false;
		}
	}
	
	public function delete($key) {
		$path = $this->cachePath . "/{$key}";
		if (is_file($path)) {
			unlink($path);
		}
	}
}

class GroupFileCache extends \Gateway\Components\ClientGroupCache {
	private $_instance;
	private $_cachePath;
	protected $_f = array();
	
	public function init() {}
	public function getInstance() {
		if (!isset($this->_instance)) {
			$this->_instance = new \Gateway\Components\K36FileCache();
			$this->_instance->cachePath = CACHE_DIR;
		}
		return $this->_instance;
	}
	
	public function setCachePath($path) {
		$this->_cachePath = $path;
	}
}