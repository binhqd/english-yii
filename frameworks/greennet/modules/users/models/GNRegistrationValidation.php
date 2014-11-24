<?php
/**
 * GNActivationCode - This model is used to process the data on the table userbase_activation_codes
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 24-Jan-2013 3:25:15 PM
 * @modified 29-Jan-2013 11:09:18 AM
 */
Yii::import('greennet.modules.validation_codes.models.ValidationCode');
class GNRegistrationValidation extends GNValidation {
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
		return 'userbase_registration_activations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, code', 'required'),
			array('code', 'length', 'max'=>64),
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
			'tmpUser' => array(self::BELONGS_TO, 'GNTmpUser', 'user_id'),
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
	 * This method is used to get user by code
	 * Return tmpUser object if code is valid or still not expire
	 * 
	 * @param string $strCode
	 */
	public function getUserByCode($strCode) {
		if (ValidationCode::isCodeValidate($strCode)) {
			$activation = $this->find('code=:code', array(':code' => $strCode));
			
			return $activation->tmpUser;
		} else {
			throw new Exception(Yii::t("greennet", "Code is invalid"));
		}
	}
}