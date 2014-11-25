<?php
class GNApiActiveRecord extends GNActiveRecord {
	public $defaultFields = '*';
	/**
	 * @var array Define keys for query
	 */
	protected $queryKeys = array('id', 'alias');
	
	/**
	 * Find by key
	 * @param mix $queryValue
	 * @param string $key
	 * @return APIZoneArticle
	 */
	public function findByKey($queryValue, $key = 'id')
	{
		if (!in_array($key, $this->queryKeys)) {
			return null;
		}
		
		$record = Yii::app()->db->createCommand()
		    ->select($this->getFields(ApiAccess::getFields()))
		    ->from($this->tableName())
		    ->where("{$key}=:value", array(':value'=>$queryValue))
		    ->queryRow();
		return $this->parse($record);
	}
	
	public function getFields($fields) {
		// check restrict field
// 		$fields = trim($fields);
// 		if (empty($fields) || $fields == "*") {
// 			return $fields;
// 		} else {
// 			$arrFields = explode(",", $fields);
// 			$arrFields = array_map("trim", $arrFields);
// 			$arrFields = array_unique($array)
// 			return $fields;
// 		}
		
		return $fields;
	}
	
	public function getTotal($condition = "", $params = array()) {
		return $this->count($condition, $params);
	}
	
	public function parse($record) {
		$record['id'] = IDHelper::uuidFromBinary($record['id'], true);
		return $record;
	}
	
	public function getList($pages, $filter = array()) {
		$command = Yii::app()->db->createCommand()
		->select($this->getFields(ApiAccess::getFields()))
		->from($this->tableName())
		->limit($pages->limit)
		->offset($pages->offset);
		
		if (!empty($filter)) {
			$command->where($filter['conditions'], $filter['params']);
		}
		
		$records = $command->queryAll();
		
		return $records;
	}
}