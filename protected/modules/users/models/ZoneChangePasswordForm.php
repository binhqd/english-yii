<?php

class ZoneChangePasswordForm extends GNChangePasswordForm
{

	public $id;
	public $email;
	public $username;
	public $verifyCode;
	private $_identity;
	/**
	* @return array customized attribute labels (name=>label)
	*/

	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('email', 'required'),
			array('email', 'email'),
			
			array('email', 'check'),
			// email has to be a valid email address
			//array('email', 'email'=>UsersModule::t(' Email failed')),
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	public function attributeLabels()
	{
		return array(
			'email' => UsersModule::t('Email'),
		);
	}
	public function check($attribute,$params){
		$user = GNUser::model()->findByAttributes(array('email'=>$this->email));
		
		if(empty($user)){
			$this->addError('email', "This email doesn't  exist in our system.");
		}
	}

}