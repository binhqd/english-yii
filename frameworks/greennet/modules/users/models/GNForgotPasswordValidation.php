<?php
/**
 * This model is used to interact with forgot password request
 * 
 * @author BinhQD
 *
 */
class GNForgotPasswordValidation extends GNValidation {
	public $_key = 'email';
	
	
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
		return 'forgot_password_validation';
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'codeRecord' => array(self::BELONGS_TO, 'ValidationCode', 'code'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'	=> 'ID',
			'email'	=> 'Email',
			'code'	=> 'Code'
		);
	}
	
	public function findRequestByEmail($email) {
		$record = $this->find("{$this->_key}=:email", array(
			':email'	=> $email
		));
		
		return $record;
	}
	
	public function cleanOldRequest($email) {
		$record = $this->find("{$this->_key}=:email", array(
			':email'	=> $email
		));
		
		if (!empty($record)) {
			$codeRecord = $record->codeRecord;
			$codeRecord->delete();
		}
	}
	
}