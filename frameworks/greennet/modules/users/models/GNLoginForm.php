<?php
/**
 * GNLoginForm - This model is used to support login
 *
 * @author Thanhngt
 * @version 1.0
 * @created 25-Jan-2013 9:59:02 AM
 * @modified 29-Jan-2013 11:09:18 AM
 */
class GNLoginForm extends GNFormModel
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
			array('email', 'email'),
			array('email', 'required'),
			array('password', 'length', 'max' => 64, 'min' => 6),
			array('password', 'required'),
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
			'email' => Yii::t("greennet", 'Email'),
			'password' => Yii::t("greennet", 'Password'),
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
				$this->addError($attributes, Yii::t("greennet", 'Your email or password doesn’t exist in our system.'));
				return false;
			} else {
				$this->user = $this->_identity->user;
			}
		} else {
			$errors = $this->errors;
			throw new Exception(current($errors));
		}
	}

}