<?php
class SluggableBehavior extends CActiveRecordBehavior {
	public $name = 'name';
	public $alias = 'alias';
	
	public function beforeSave($event) {
		parent::beforeSave($event);
		
		Yii::import('greennet.helpers.Sluggable');
		$name = isset($this->owner->{$this->name}) ? $this->owner->{$this->name} : "";
		$alias = isset($this->owner->{$this->alias}) ? $this->owner->{$this->alias} : "";
		
		if ($alias == "") {
			// create alias base on name
			if ($this->owner->isNewRecord) {
				$alias = $this->autoCreateAlias($name);
			} else {
				$alias = $this->autoCreateAlias($name, $this->owner->id);
			}
		} else  {
			// create alias base on alias
			if ($this->owner->isNewRecord) {
				$alias = $this->autoCreateAlias($alias);
			} else {
				$alias = $this->autoCreateAlias($alias, $this->owner->id);
			}
		}
		
		$this->owner->{$this->alias} = $alias;
		return true;
	}
	
	/**
	 * Phương thức dùng để kiểm tra 1 alias có tồn tại hay chưa
	 * @param $alias
	 * @param $id
	 * @return true if alias exists, else return false
	 */
	private function isAliasExisted($alias, $id = -1) {
		if ($this->owner->count("{$this->alias}=:alias and id <> :id", array(
			':alias'	=> $alias,
			':id'		=> $id
		))) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Method autoCreateAlias help auto create alias from title
	* @param $title
	* @return $alias
	*/
	private function autoCreateAlias($name, $id = -1){
		// generate alias from title
		$alias_from_title = Sluggable::slug($name);
		$alias = $alias_from_title;
		// create alias
		$number = rand();
		while ($this->isAliasExisted($alias, $id)) {
			$alias = "{$alias_from_title}-{$number}";
			$number = rand();
		}
		return $alias;
	}
}