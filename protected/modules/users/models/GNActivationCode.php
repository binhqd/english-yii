<?php
/**
 * GNActivationCode - This model is used to process the data on the table userbase_activation_codes
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 24-Jan-2013 3:25:15 PM
 * @modified 29-Jan-2013 11:09:18 AM
 */
class GNActivationCode extends JLActiveRecord
{

	/**
	 * Type of activation code
	 */
	const TYPE_ACTIVATE_ACCOUNT = 0;
	/**
	 * Type of activation code
	 */
	const TYPE_FORGOT_PASSWORD = 1;
	/**
	 * Type of activation code
	 */
	const TYPE_CHANGE_EMAIL = 2;

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
		return 'userbase_activation_codes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, code, type, created, expiry_date', 'required'),
			array('type, created, expiry_date', 'numerical', 'integerOnly'=>true),
			array('id, user_id', 'length', 'max'=>16),
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
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'code' => 'Code',
			'type' => 'Type',
			'created' => 'Created',
			'expiry_date' => 'Expiry Date',
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
	public function createCode($binUserId, $intType)
	{
		$codeActivation = GNUser::encryptPassword(microtime(), GNUser::createSalt());

		// Delete old code has the same type too
		self::model()->deleteAllByAttributes(array(
			'user_id' => $binUserId,
			'type' => $intType,
		));

		$modelUserActivation = new GNActivationCode();
		$modelUserActivation->user_id = $binUserId;
		$modelUserActivation->code = $codeActivation;
		$modelUserActivation->type = $intType;
		
		if ($modelUserActivation->type===self::TYPE_ACTIVATE_ACCOUNT) {
			$expiry = Yii::app()->getModule('users')->intExpiryDate;
		} else {
			$expiry = Yii::app()->getModule('users')->intExpiryDateForgotPassword;
		}

		$modelUserActivation->expiry_date = time() + ($expiry*24*60*60);
		$modelUserActivation->created = time();
		
		if (!$modelUserActivation->save()) {
			throw new Exception("Could not create confirmation code");
		}

		return $codeActivation;
	}

	/**
	 * This action is used to check an activation code.
	 * Return GNActivationCode if code is valid.
	 * Return false if code is invalid.
	 */
	public function checkCode($strCode)
	{
		$today = time();
		$checkCode = self::model()->findByAttributes(array(
			'code' => $strCode
		),array(
			'condition'=>'expiry_date>=:date',
			'params'=>array('date'=>$today),
		));

		return !empty($checkCode) ? $checkCode : false;
	}

	/**
	 * This method is used to delete code
	 *
	 * @param binCodeId    Binary of code
	 */
	public function deleteCode($binCodeId)
	{
		if (isset($binCodeId)) {
			$modelCode = self::model()->findbyPk($binCodeId);
			
			if (!empty($modelCode))
				return $modelCode->delete();
		}
		return false;
	}

}