<?php
/**
 * GNActivationCode - This model is used to process the data on the table userbase_activation_codes
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 24-Jan-2013 3:25:15 PM
 * @modified 29-Jan-2013 11:09:18 AM
 */
/**
 CREATE TABLE `<tablename>_validation` (
  `id` varbinary(16) NOT NULL,
  `user_id` varbinary(16) NOT NULL,
  `code` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `userbase_registration_activations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userbase_tmp_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `userbase_registration_activations_ibfk_2` FOREIGN KEY (`code`) REFERENCES `activation_codes` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
Yii::import('greennet.modules.validation_codes.models.ValidationCode');
class GNValidation extends GNActiveRecord
{
	public $_key = 'email';
	public $_code = 'code';
	 
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array("{$this->_key}, {$this->_code}", 'required'),
			array("{$this->_code}", 'length', 'max'=>64),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
 			'codeRecord' => array(self::BELONGS_TO, 'ValidationCode', 'code')
			//'tmpUser' => array(self::BELONGS_TO, 'GNTmpUser', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'code' => 'Code'
		);
	}

	/**
	 * This method is used to create activation code.
	 * Return with an activation code.
	 * Throw an exception if cannot create code.
	 *
	 * @param binUserId    Binary of User ID
	 * @param intType    Type of code:
	 * 0: activate account
	 * 1: forgot password
	 * 2: change email
	 */
	
	public function createCode($key)
	{
		$expiry = Yii::app()->getModule('users')->intExpiryDate; // 1 day
		
		$expiry = $expiry*86400;
		
		$validationCode = ValidationCode::createCode($expiry);
		
		$this->isNewRecord = true;
		$this->{$this->_key} = $key;
		$this->{$this->_code} = $validationCode;
		
		//ajaxOut($this);
		if ($this->save()) {
			return $validationCode;
		} else {
			$errMsg = Yii::t("greennet", "Can't create validation code");
			Yii::log($errMsg, CLogger::LEVEL_ERROR, $class);
			throw new Exception($errMsg);
		}
	}
	
	/**
	 * This method is used to retrieve an object by key
	 * @param unknown $key
	 * @return unknown
	 */
	public function findRequestByKey($key) {
		$record = $this->find("{$this->_key}=:key", array(
			':key'	=> $key
		));
	
		return $record;
	}
	/**
	 * This method is used to retrieve an object by code
	 * 
	 * @param $code $code
	 * @return object $record
	 */
	public function findRequestByCode($code) {
		$record = $this->find("{$this->_code}=:code", array(
			':code'	=> $code
		));
		
		return $record;
	}
	
	/**
	 * This method is used to delete code
	 *
	 * @param binCodeId    Binary of code
	 */
	public function deleteCode($strCode)
	{
		ValidationCode::deleteCode($strCode);
		
		$record = $this->findRequestByCode($strCode);
		if (!empty($record))
			$record->delete();
	}
	
	public function cleanOldRequest($key) {
		$record = $this->findRequestByKey($key);
	
		if (!empty($record)) {
			$codeRecord = $record->codeRecord;
			$codeRecord->delete();
		}
	}

}