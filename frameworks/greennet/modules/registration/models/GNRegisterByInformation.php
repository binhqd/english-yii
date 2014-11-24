<?php
/**
 * Form Model GNRegisterByEmail Dùng để hỗ trợ cho việc register by email
 *
 * @author phihx
 * @date 05-02-2013
 */
class GNRegisterByInformation extends GNFormModel
{
	public $firstname;
	public $lastname;
	public $password;
	public $confirmPassword;
	public $confirmEmail;
	public $email;
	
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
	* @return array customized attribute labels (name=>label)
	*/
	public function rules()
	{
		return array(
			array('email, confirmEmail', 'email'),
			array('email, confirmEmail', 'required'),
			array('confirmEmail', 'compare','compareAttribute'=>'email'),
			array('email', 'greennet.modules.users.components.validators.CheckEmailExistingValidator'),
			array('password,confirmPassword', 'required'),
			array('confirmPassword','compare','compareAttribute'=>'password'),
			array('firstname, lastname', 'required'),
			array('firstname', 'length', 'max'=>30),
			array('lastname', 'length', 'max'=>20),
			// array('firstname', 'greennet.modules.users.components.validators.FirstnameValidator'),
			// array('lastname', 'greennet.modules.users.components.validators.LastnameValidator'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'firstname' => Yii::t("greennet", 'First Name'),
			'lastname' => Yii::t("greennet", 'Last Name'),
			'password' => Yii::t("greennet", 'Password'),
			'confirmPassword' => Yii::t("greennet", 'Confirm Password'),
			'confirmEmail' => Yii::t("greennet", 'Confirm Email'),
			'email' => Yii::t("greennet", 'Email'),
		);
	}
}