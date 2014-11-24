<?php
namespace Gateway\Components;
class GroupMemCache extends \Gateway\Components\ClientGroupCache {
	private $_instance;
	protected $_f = array('servers');
	
	public function init() {}
	public function getInstance() {
		if (!isset($this->_instance)) {
			$this->_instance = new \Memcached;
		}
		
		return $this->_instance;
	}
	
	public function setServers($arrServers = array()) {
		foreach ($arrServers as $server) {
			$this->instance->addServer($server['host'], $server['port']);
		}
	}
	
}
