<?php
class ZoneLoginForm extends CFormModel
// class ZoneLoginForm extends GNLoginForm
{

	public $email;
	public $password;
	public $rememberMe;
	private $_identity;
	public $user = null;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('email, password', 'required'),
			array('email', 'email'),
			array('password', 'length', 'max' => 64, 'min' => 6),
			array('password', 'authenticate'),
			array('rememberMe', 'numerical', 'integerOnly'=>true),
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'email' => UsersModule::t('Email'),
			'password' => UsersModule::t('Password'),
			'rememberMe' => UsersModule::t('Remember me on this computer'),
		);
	}
	/**
	 * This method is used to authenticate
	 */
	public function authenticate($attributes, $params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new GNUserIdentity($this->email,$this->password);
			if (!$this->_identity->authenticate()) {
				$this->addError($attributes, 'Your email or password doesn’t exist in our system.');
			} else {
				$this->user = $this->_identity->user;
			}
		}
	}

}