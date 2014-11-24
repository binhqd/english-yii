<?php
/**
 * GNActiveRecord - This class is the customized base model class.
 * All model classes for this application should extend from this base class.
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 24-Jan-2013 2:32:30 PM
 * @modified 29-Jan-2013 11:09:18 AM
 */
class GNActiveRecord extends CActiveRecord
{
	public $enableLanguage = false;
	public $localeFields = array();
	protected $_belongsTo = array();
	private $_oldAttributes = array();

	/**
	 * This method is invoked before saving a record (after validation, if any).
	 * @return boolean whether the saving should be executed. Defaults to true.
	 */
	public function beforeSave()
	{
		parent::beforeSave();

		// Manage Primary Key with UUID
		if ($this->getIsNewRecord()) {
			if (!isset($this->id)) {
				$pk = $this->getTableSchema()->primaryKey;

				$pattern = "/(\\0|\\r|\\n|\\\\)/";
				// Perform remove null charactor
				do {
					$uuid = IDHelper::uuidToBinary(IDHelper::uuid());
					$uuid = preg_replace($pattern, "jl", $uuid);
				} while (preg_match("/(\\0|\\r|\\n|\\\\)/", $uuid));

				$this->setAttribute('id', $uuid);
			}

			// Multi Language
			if ($this->enableLanguage) {
				$tableSchema = $this->getTableSchema();
				if (isset($tableSchema->columns['locale'])) {
					$this->setAttribute('locale', Yii::app()->language);
				}
			}
		}

		// Multi Language on same record
		if ($this->enableLanguage && count($this->localeFields) > 0) {
			$tableSchema = $this->getTableSchema();
			if (isset($tableSchema->columns['locale']) && $this->locale != Yii::app()->language) {
				Yii::import('greennet.models.GNI18n');

				foreach ($this->localeFields as $field) {
					GNI18n::model()->saveI18n($tableSchema->name, $this->id, $field, $this->$field, Yii::app()->language);
					// Reset attributes
					$this->setAttribute($field, $this->oldAttributes[$field]);
				}
			}
		}

		return true;
	}

	/**
	 * This method is invoked before an AR finder executes a find call.
	 */
	protected function beforeFind()
	{
		parent::beforeFind();

		// Multi Language
		if ($this->enableLanguage) {
			$tableSchema = $this->getTableSchema();
			if (count($this->localeFields)==0) { // Multi Language with new record
				if (isset($tableSchema->columns['locale'])) {
					$criteria = new CDbCriteria;
					$criteria->condition = "locale = '".Yii::app()->language."'";
					$this->dbCriteria->mergeWith($criteria);
				}
			} else { // Multi Language on same record
				Yii::import('greennet.models.GNI18n');
				$this->metaData->addRelation('tbl_i18n', array(self::HAS_MANY, 'GNI18n', 'object_id'));
				$criteria = new CDbCriteria;
				$criteria->together = true;
				$criteria->with = array('tbl_i18n');
				foreach ($this->localeFields as $field) {
					if (!empty($this->$field)) {
						$criteria->compare($field . ' NOT', $this->$field, true);
						$criteria->compare('tbl_i18n.field', $field, false);
						$criteria->compare('tbl_i18n.value', $this->$field, true);
						$criteria->compare('tbl_i18n.locale', Yii::app()->language, false);
					}
				}
				$this->dbCriteria->mergeWith($criteria, 'OR');
			}
		}
	}

	/**
	 * This method is invoked after each record is instantiated by a find method.
	 */
	protected function afterFind()
	{
		// Multi Language on same record
		if ($this->enableLanguage && count($this->localeFields)>0) {
			// Save old values
			$this->setOldAttributes($this->getAttributes());

			$tableSchema = $this->getTableSchema();

			if (isset($tableSchema->columns['locale']) && $this->locale != Yii::app()->language) {
				Yii::import('greennet.models.GNI18n');
				$arrLocales = GNI18n::model()->getI18n($tableSchema->name, $this->id, Yii::app()->language);
				foreach ($arrLocales as $i18n)
					$this->setAttribute($i18n->field, $i18n->value);
			}
		}
		parent::afterFind();
	}

	/**
	 * This method is invoked after deleting a record.
	 */
	protected function afterDelete()
	{
		// Multi Language on same record
		if ($this->enableLanguage && count($this->localeFields)>0) {
			$tableSchema = $this->getTableSchema();

			if (isset($tableSchema->columns['locale']) && $this->locale != Yii::app()->language) {
				Yii::import('greennet.models.GNI18n');
				GNI18n::model()->deleteI18n($tableSchema->name, $this->id, Yii::app()->language);
			}
		}
		parent::afterDelete();
	}

	/**
	 * Finds the number of rows satisfying the specified query condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed $condition query condition or criteria.
	 * @param array $params parameters to be bound to an SQL statement.
	 * @return string the number of rows satisfying the specified query condition. Note: type is string to keep max. precision.
	 */
	public function count($condition='',$params=array())
	{
		/**
		 * HuyTBT added
		 * Multi Language
		 */
		if ($this->enableLanguage && count($this->localeFields)>0) {
			$this->beforeFind();
		}
		return parent::count($condition,$params);
	}

	/**
	 * This method is used to get old attributes
	 */
	public function getOldAttributes()
	{
		return $this->_oldAttributes;
	}

	/**
	 * This method is used to set old attributes
	 */
	public function setOldAttributes($value)
	{
		$this->_oldAttributes=$value;
	}

	/**
	 * This method is uses to check if content contain word length too long
	 */
	public function checkWordCount($attribute, $params)
	{
		$length = isset($params['length']) ? $params['length'] : 30;
		$content = preg_replace('/\n/', ' ', $this->$attribute);
		
		//preg_match_all("/[ ]+/"
		$arrWords = explode(' ', $content);
		$arrWords = array_unique($arrWords);
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		
		foreach ($arrWords as $word) {
			if (strlen(trim($word)) > $length && !preg_match($reg_exUrl, $word, $url)) {
				$this->addError($attribute, 'The length of each word should not exceed '.$length.' characters');
				break;
			}
		}
	}

	public function handleErrors() {
		$errors = $this->errors;

		if (!empty($errors)) {
			list($index, $error) = each($errors);
			if (!empty($error)) {
				throw new Exception($error[0]);
			} else {
				$msg = "Error occurs while validating on ".get_class($this).". Please try again or contact our Administrator for more support.";
				Yii::log($msg, CLogger::LEVEL_ERROR, 'Unidentified error');
				throw new Exception($msg);
			}
		}
	}

	public function afterValidate($event = null) {
		parent::afterValidate($event);
		$this->handleErrors();
	}

	/**
	 * This method is used to set classes that belongs to current acticle
	 * @param array $arrClasses
	 */
	public function setBelongsTo($arrClasses) {
		if (is_array($arrClasses)) {
			foreach ($arrClasses as $name => $config) {
				$this->_belongsTo[$name] = Yii::createComponent($config);
			}
		}
	}

	/**
	 * This method is used to get all instances that associated with current article
	 */
	public function getBelongsTo() {
		return $this->_belongsTo;
	}

	/**
	 * This method is used to return an instance that associated with current article
	 * @param string $name
	 */
	public function __get($name) {
		try {
			return parent::__get($name);
		} catch (Exception $ex) {
			if (!empty($this->_belongsTo[$name])) {
				return $this->_belongsTo[$name];
			}
		}
	
	}

	/**
	 * ===== Event Handlers =======
	 */
	/**
	 * Add event handler when new record has created
	 * @param event $event
	 */
	public function onCreated($event)
	{
		$this->raiseEvent('onCreated',$event);
	}

	/**
	 * Add event handler when new record has updated
	 * @param event $event
	 */
	public function onUpdated($event)
	{
		$this->raiseEvent('onUpdated',$event);
	}

	/**
	 * Add event handler when new record has deleted
	 * @param event $event
	 */
	public function onDeleted($event)
	{
		$this->raiseEvent('onDeleted',$event);
	}
}
