<?php
class UserNodeInfo extends CFormModel {
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
			
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'/people/s_id' => "Messenger ID:"
		);
	}
}