<?php
/**
 * This form is used to support forgot password
 *
 * @author ngocnm
 * @date 2011-02-01
 * @version 1.0
 */
class GNForgotPasswordForm extends GNFormModel
{

	public $id;
	public $email;
	public $username;
	public $displayname;
	public $verifyCode;
	private $_identity;
	/**
	* @return array customized attribute labels (name=>label)
	*/

	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('email', 'email'),
			array('email', 'required'),
			// email has to be a valid email address
			//array('email', 'email'=>Yii::t("greennet", ' Email failed')),
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	public function attributeLabels()
	{
		return array(
			'email' => Yii::t("greennet", 'Email'),
		);
	}

}