<?php

/**
 * @ingroup components
 * Base class of a data record
 */
class JLUserActiveRecord extends JLActiveRecord
{
	public $binFields = array();
	private static $userDB = null;
	public $enableReference = false;
	
	/**
	 * (non-PHPdoc)
	 * @see yii/framework/db/ar/CActiveRecord::getDbConnection()
	 */
	public function getDbConnection() {
		if (self::$userDB !== null)
			return self::$userDB;
		else
		{
			self::$userDB = Yii::app()->userDB;
			if (self::$userDB instanceof CDbConnection)
			{
				self::$userDB->setActive(true);
				return self::$userDB;
			} else
				throw new CDbException(Yii::t('justlook','Active Record requires a "userDB" CDbConnection application component.'));
		}
	}
	
	/**
	 * 
	 * Phương thức được sử dụng để lấy các trường kiểu UUID
	 */
	private function _getBinaryFields() {
		if (!empty($this->binFields)) {
			return $this->binFields;
		} else {
			$this->binFields = array();
			foreach ($this->metaData->columns as $column) {
				if (strpos($column->dbType, "uuid") !== false) {
					$this->binFields[] = $column->name;
				}
			}
			
			if (!in_array('id', $this->binFields)) {
				$this->binFields[] = 'id';
			}
			
			return $this->binFields;
		}
	}
	
	protected function beforeDelete() {
		parent::beforeDelete();
		
		self::$userDB->createCommand("PRAGMA foreign_keys = ON;")->execute();

		return true;
	}
	
	/**
	 * Phương thức phục vụ cho reference module
	 * @author huytbt
	 * @date 2011-09-05
	 * @version 1.0
	 */
	protected function query($criteria,$all=false)
	{
		if ($this->enableReference)
		{
			$condition = $criteria->condition;
			if ($param = $this->_getParamString('module_id', $condition)) {
				$module_id = $criteria->params[$param];
				$criteria->condition = str_replace('module_id='.$param, '1=1', $condition);
				unset($criteria->params[$param]);
				if ($all) {
					Yii::import('application.modules.sites.models.ModuleReference');
					$moduleReference = ModuleReference::model()->findAllByAttributes(array('module_id'=>$module_id));
					
					if ($moduleReference)
					{
						$condition = $criteria->condition;
						$condition .= ($condition?' AND ':'');
						foreach ($moduleReference as $index => $reference) {
							$entry_id = $reference->entry_id;
							$condition .= ($index && $condition?' OR ':'') . "(id=:entry_id{$index})";
							$criteria->params[":entry_id{$index}"] = $entry_id;
						}
						$criteria->condition = $condition;
					} else {
						$criteria->condition = str_replace('1=1', '1=0', $condition);
					}
				} else {
					Yii::import('application.modules.sites.models.ModuleReference');
					$moduleReference = ModuleReference::model()->findByAttributes(array('module_id'=>$module_id));
					
					if ($moduleReference)
					{
						$condition = $criteria->condition;
						$condition .= ($condition?' AND ':'') . "(id=:entry_id)";
						$criteria->params[":entry_id"] = $moduleReference->entry_id;
						$criteria->condition = $condition;
					} else {
						$criteria->condition = str_replace('1=1', '1=0', $condition);
					}
				}
			}
		}
		
		return parent::query($criteria,$all);  
	}
	
	private function _getParamString($strField, $condition)
	{
		if (($i = strpos($condition, $strField))!==false) {
			$condition = substr($condition, $i, strlen($condition)-$i);
			preg_match('/[^A-Za-z0-9_:]/', $condition, $matches);
			if (count($matches) && ($i = strpos($condition, $matches[0]))!==false) {
				$i++;
				$condition = substr($condition, $i, strlen($condition)-$i);
				preg_match('/[^A-Za-z0-9_:]/', $condition, $matches);
				if (($i = strpos($condition, $matches[0]))!==false) {
					$condition = substr($condition, 0, $i);
					return $condition;
				}
			}
		}
		return '';
	}
	
	/**
	 * Phương thức phục vụ cho reference module
	 * @author huytbt
	 * @date 2011-09-05
	 * @version 1.0
	 */
	/*public function findByAttributes($attributes,$condition='',$params=array())
	{
		if ($this->enableReference && in_array('module_id', array_keys($attributes))) {
			Yii::import('application.modules.sites.models.ModuleReference');
			$moduleReference = ModuleReference::model()->findByAttributes(array('module_id'=>$attributes['module_id']));
			if ($moduleReference)
			{
				$entry_id = $moduleReference->entry_id;
				unset($attributes['module_id']);
				$attributes['id'] = $entry_id;
				return parent::findByAttributes($attributes,$condition,$params);
			}
				return null;
		} else
			return parent::findByAttributes($attributes,$condition,$params);
	}*/
	
	/**
	 * Phương thức phục vụ cho reference module
	 * @author huytbt
	 * @date 2011-09-05
	 * @version 1.0
	 */
	/*public function findAllByAttributes($attributes,$condition='',$params=array())
	{
		if ($this->enableReference && in_array('module_id', array_keys($attributes))) {
			Yii::import('application.modules.sites.models.ModuleReference');
			$moduleReference = ModuleReference::model()->findAllByAttributes(array('module_id'=>$attributes['module_id']));
			
			if ($moduleReference)
			{
				unset($attributes['module_id']);
				foreach ($moduleReference as $index => $reference) {
					$entry_id = $reference->entry_id;
					$condition .= ($condition?' OR ':'') . "id=:id{$index}";
					$params[":id{$index}"] = $entry_id;
				}
				
				return parent::findAllByAttributes($attributes,$condition,$params);
			}
				return null;
		} else
			return parent::findAllByAttributes($attributes,$condition,$params);
	}*/
	
	/**
	 * Phương thức phục vụ cho reference module
	 * @author huytbt
	 * @date 2011-09-05
	 * @version 1.0
	 */
	public function count($condition='',$params=array())
	{
		if ($this->enableReference) {
			Yii::import('application.modules.sites.models.ModuleReference');
			return ModuleReference::model()->count($condition, $params);
		} else
			return parent::count($condition,$params);
	}
	
	/**
	 * Phương thức phục vụ cho reference module
	 * @author huytbt
	 * @date 2011-09-05
	 * @version 1.0
	 */
	public function countByAttributes($attributes,$condition='',$params=array())
	{
		if ($this->enableReference) {
			Yii::import('application.modules.sites.models.ModuleReference');
			return ModuleReference::model()->countByAttributes($attributes,$condition,$params);
		} else
			return parent::countByAttributes($attributes,$condition,$params);
	}
	
	/**
	 * Phương thức phục vụ cho reference module
	 * @author huytbt
	 * @date 2011-09-05
	 * @version 1.0
	 */
	public function save($runValidation=true,$attributes=null)
	{
		$save = parent::save($runValidation,$attributes);
		
		if ($save && $this->enableReference) {
			Yii::import('application.modules.sites.models.ModuleReference');
			if (!ModuleReference::model()->countByAttributes(array('module_id'=>$this->module_id,'entry_id'=>$this->id)))
			{
				$moduleReference = new ModuleReference;
				$moduleReference->module_id = $this->module_id;
				$moduleReference->entry_id = $this->id;
				$moduleReference->save();
			}
		}
		
		return $save;
	}
	
	/**
	 * Phương thức phục vụ cho reference module
	 * @author huytbt
	 * @date 2011-09-05
	 * @version 1.0
	 */
	public function delete()
	{
		if ($this->enableReference) {
			Yii::import('application.modules.sites.models.ModuleReference');
			$result = ModuleReference::model()->deleteAll('(module_id=:module_id) AND (entry_id=:entry_id)', array(':module_id'=>$this->module_id,':entry_id'=>$this->id));
			if (!ModuleReference::model()->countByAttributes(array('entry_id'=>$this->id)))
				return parent::delete();
			return $result;
		} else
			return parent::delete();
	}
}
