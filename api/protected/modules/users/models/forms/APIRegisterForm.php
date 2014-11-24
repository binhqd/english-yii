<?php
/**
 * APIRegisterForm - This model is used to change password user
 * 
 * @author NgocNM
 * @version 1.0
 */
class APIRegisterForm extends ZoneRegisterForm
{
	/**
	 * @var string first name
	 */
	public $firstname;
	/**
	 * @var string last name
	 */
	public $lastname;
	/**
	 * @var string password
	 */
	public $password;
	/**
	 * @var string email 
	 */
	public $email;
	/**
	 * @var string location 
	 */
	public $location;
	/**
	 * @var string location 
	 */
	public $birthday;
	/**
	 * @var string gender 
	 */
	public $gender;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('email', 'email'),
			array('firstname,lastname,email,password,birthday,location,gender', 'required'),
			array('email', 'greennet.modules.users.components.validators.CheckEmailExistingValidator'),
			array('password', 'length', 'min' => 6),
			array('firstname', 'length', 'max' => 30),
			array('lastname', 'length', 'max' => 20),
			array('birthday, location, gender', 'safe'),
			// array('birth', ''),
			array('gender', 'genderValid'),
			array('location', 'locationValid'),
		);
	}

}