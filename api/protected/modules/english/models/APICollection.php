<?php
class APICollection extends GNApiActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getName() {
		return __CLASS__;
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_data';
	}
	
// 	public function count($condition = '', $params = array()) {
// 		return parent::count($condition, $params);
// 	}
	public function parse($record) {
		$record = parent::parse($record);
		
		if (isset($record['created']))
			$record['created'] = date(DateTime::ISO8601, strtotime($record['created']));
			
		if (isset($record['user_id']))
			$record['user_id'] = IDHelper::uuidFromBinary($record['user_id'], true);
		
		return $record;
	}
	
	public function getList($pages) {
		// TODO: Filter by user_id
		try {
			$records = Yii::app()->db->createCommand()
			->select($this->getFields(ApiAccess::getFields()))
			->from($this->tableName())
			->limit($pages->limit)
			->offset($pages->offset)
			->queryAll();
		} catch (Exception $ex) {
			if ($ex->getCode() == 42) {
				Yii::app()->response->send(400, array(), Yii::t('apicore', "Invalid request. Unknow column."));
			}
		}
		
		return $records;
	}
}