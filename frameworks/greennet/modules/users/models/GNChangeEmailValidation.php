<?php
/**
 * This model is used to interact with forgot password request
 * 
 * @author BinhQD
 *
 */
class GNChangeEmailValidation extends GNValidation {
	public $_key = 'user_id';
	
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
		return 'change_email_validations';
	}
	
	/**
	 * @return array relational rules.
	 */
// 	public function relations()
// 	{
// 		// NOTE: you may need to adjust the relation name and the related
// 		// class name for the relations automatically generated below.
// 		return array(
// 			'codeRecord' => array(self::BELONGS_TO, 'ValidationCode', 'code'),
// 		);
// 	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'		=> 'ID',
			'user_id'	=> 'User ID',
			'code'	=> 'Code'
		);
	}
}