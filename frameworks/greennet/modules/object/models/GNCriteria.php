<?php
class GNCriteria extends CComponent {
	private $_owner;
	private $_criteria;
	private $cache;
	public function getOwner() {
		return $this->_owner;
	}
	
	public function getCriteria() {
		if (!isset($this->_criteria)) {
			$this->_criteria = new CDbCriteria();
		}
		
		return $this->_criteria;
	}
 	
	public function setOwner($owner) {
		$this->_owner = $owner;
	}
	
	public function select($fields) {
		$this->criteria->select = $fields;
		return $this;
	}
	
	public function cache($key) {
		$this->cache = $key;
		return $this;
	}
	
	public function toArray($callback = null) {
		if (!empty($this->cache)) {
// 			$data = 
		}
		$records = $this->owner->findAll($this->criteria);
		
		$out = array();
		
		// if callback function is not null
		if ($callback != null) {
			foreach ($records as $record) {
				$out[] = $callback($record);
			}
		} else {
			foreach ($records as $record) {
				$out[] = $record->attributes;
			}
		}
		
		
		return $out;
	}
}