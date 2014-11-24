<?php
namespace Gateway\Components;
class GroupCache extends \Gateway\Components\CComponent {
	private $_instance;
	protected $_f = array('servers');
	
	public function init() {}
	public function save($category, $key, $value) {
		if ($category == 'dependency') {
			throw new Exception(Yii::t("{category} is reserved by system keywords"), array(
				'{category}'	=> $category
			));
		}
		$ret = $this->instance->set("{$category}_{$key}", $value);
		
		// save dependency
		$this->updateDependency($category);
		return $ret;
	}
	
	public function get($category, $key) {
		return $this->instance->get("{$category}_{$key}");
	}
	
	public function delete($category, $key) {
		return $this->instance->delete("{$category}_{$key}");
	}
	
	private function updateDependency($category) {
		$dependenceInfo = array(
			'modified'	=> microtime()
		);
		$this->instance->set("dependency_{$category}", $dependenceInfo);
	}
}