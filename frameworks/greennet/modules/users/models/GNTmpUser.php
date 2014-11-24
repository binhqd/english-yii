<?php
/**
 * GNTmpUser - This model is used to process the data on the table userbase_tmp_users
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 24-Jan-2013 2:34:44 PM
 * @modified 29-Jan-2013 11:09:18 AM
 */
class GNTmpUser extends GNUser
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

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'userbase_tmp_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email', 'email'),
// 			array('email', 'greennet.modules.users.components.validators.CheckEmailExistingValidator'),
			array('email, password', 'required'), // 
			
			array('email', 'unique', 'message' => Yii::t("greennet", "Your email address already registered on our system.")),
			array('created', 'numerical', 'integerOnly'=>true),
			array('password', 'length', 'max' => 64, 'min' => 6),
			array('saltkey', 'length', 'max'=>8),
			array('firstname, lastname', 'required'),
			array('firstname', 'length', 'max'=>30),
			array('lastname', 'length', 'max'=>20),
			// array('firstname', 'greennet.modules.users.components.validators.FirstnameValidator'),
			// array('lastname', 'greennet.modules.users.components.validators.LastnameValidator'),
			//array('firstname, lastname', 'safe', 'on'=>'updateBasicInfo'),
		);
	}
	
	/**
	 * This method is used to create a member.
	 * Return false if cannot create user
	 *
	 * @param arrInformation    Array of Information of User
	 */
	public function createUser($arrInformation, $className=__CLASS__)
	{
		// Set information
		$user = $this;
		
		$username = Sluggable::slug($arrInformation['email']);
		$username = preg_replace("/@/", '.', $username);
		$username = preg_replace("/(\.[a-z0-9]+)$/", '', $username);
					
		$user->username = $username;
		$user->displayname = self::createDisplayName($arrInformation['firstname'], $arrInformation['lastname']);
		
		$user->firstname = ucfirst(strtolower($arrInformation['firstname']));
		$user->lastname = ucfirst(strtolower($arrInformation['lastname']));
		$user->email = $arrInformation['email'];
		$user->created = time();
		$user->password = $arrInformation['password'];
		$strSalt = GNUser::createSalt();
		$user->saltkey = $strSalt;
		// Begin transaction
		$transaction = Yii::app()->db->beginTransaction();
		
		// Validate information and create user
		if ($user->validate()) {
			$user->password = GNUser::encryptPassword($user->password, $strSalt);
			
			if ($user->save()) {
				$transaction->commit(); // commit transaction
				// return GNUser
				return $user;
			} else {
				$transaction->rollback(); // rollback transaction
				return false;
			}
		} else {
			$transaction->rollback(); // rollback transaction
			return false;
		}
	}
	
	public function createValidationCode() {
		Yii::import('greennet.modules.validation_codes.models.*');
		Yii::import('greennet.modules.users.models.GNRegistrationValidation');
		
		
		$validationCode = GNRegistrationValidation::model()->createCode($this->id);
		
		return $validationCode;
	}
}