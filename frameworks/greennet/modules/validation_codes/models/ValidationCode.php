<?php
/**
 * @author minhnc
 * @version 1.0
 * @created 25-Feb-2013 8:43:28 AM
 * @modified 25-Feb-2013 8:43:28 AM
 * 
 * This is the model class for table "activation_codes".
 *
 * The followings are the available columns in table 'activation_codes':
 * @property string $id
 * @property string $code
 * @property string $expiry_date
 * @property string $created
 */
class ValidationCode extends GNActiveRecord
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
	 * Type of invite friend
	 */
	const TYPE_INVITE_FRIEND = 3;
	
	const TYPE_ACTIVE_EMAIL = 4;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ActivationCode the static model class
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
		return 'activation_codes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, expiry_date', 'required'),
			array('id', 'length', 'max'=>16),
			array('code', 'length', 'max'=>128),
			array('created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, expiry_date, created', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'expiry_date' => 'Expiry Date',
			'created' => 'Created',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('expiry_date',$this->expiry_date,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * This method is used to generate new code
	 * @param unknown_type $strCode
	 */
	public static function createCode($expiryTime = NULL) {
		if (isset($expiryTime)) {
			$codeActivation = GNUser::encryptPassword(microtime(), GNUser::createSalt());
			
			$modelValidationCode = new ValidationCode();
			$modelValidationCode->code = $codeActivation;
			$modelValidationCode->expiry_date = date("Y-m-d H:i:s", time() + $expiryTime);
			$modelValidationCode->created = date("Y-m-d H:i:s");
			
			if (!$modelValidationCode->save()) {
				throw new Exception(Yii::t("greennet", "Could not create confirmation code"));
			}
			
			return  $codeActivation;
		}
	}
	
	/**
	 * This method is used to delete a code
	 * @param unknown_type $strCode
	 */
	public static function deleteCode($strCode = NULL) {
		if (isset($strCode)) {

			$modelValidationCode = self::model()->findByAttributes(array(
				'code'	=> $strCode
			));
			
			if (!empty($modelValidationCode)) {
				
				$deleteCoded = $modelValidationCode->delete();
				
				if ($deleteCoded) {
					return true;
				}

				$err_remove  = $deleteCoded->errors;
			
				if(empty($err_remove)){
					throw new Exception(Yii::t("greennet", "Can't remove activation code."));
				} else {
					list($field, $_err) = each($err_remove);
					throw new Exception(sprintf(Yii::t("greennet", "Can't remove activation code, errors is : %s"), $_err[0]));
				}
			}
		}
		return false;
	}
	
	/**	
	 * This method is used to check if code is validated
	 * @param unknown_type $strCode
	 */
	public static function isCodeValidate($strCode = NULL) {
		if (isset($strCode)) {
			
			$today = date("Y-m-d H:i:s");
			
			$checkCode = self::model()->findByAttributes(array(
				'code' => $strCode
			),array(
				'condition'=>'expiry_date>=:date',
				'params'=>array('date' => $today),
			));
			
			return !empty($checkCode);
		}
	}
	
	/**
	 * This method is used to clean all expried codes
	 */
	public static function cleanExpiredCodes() {
		$today = date("Y-m-d H:i:s");
		
		$cdbCriteria = new CDbCriteria();
		$cdbCriteria->condition = 'expiry_date<:date';
		$cdbCriteria->params = array(
			'date'	=> $today
		);

		$deleteAllCode = self::model()->deleteAll($cdbCriteria);

		return $deleteAllCode;
	}
}