<?php
/**
 * GNChangeEmailForm - This model is used to change email
  *
  *@author	: Chu Tieu
  *@Version	: 1.0
  *@Create	: 01-02-2013
  *
  */
class GNChangeEmailForm extends GNFormModel
{

	public $email;
	public $confirmEmail;
	/**
	 *@Usage	: This method used to validate.
	 *@author	: Chu Tieu
	 *@Version	: 1.0
	 *@Create	: 01-02-2013
	 */
	public function rules()
	{
		return array(
			array('email', 'email'),
			array('email', 'required'),
			array('email', 'checkEmail'),
		);
	}
	
	/**
	 * This method is used to authenticate
	 */
	public function checkEmail($attribute, $params)
	{
		if(!$this->hasErrors())
		{
			if ($this->$attribute == currentUser()->email) {
				$this->addError($attribute, Yii::t("greennet", "Your email currently is {$this->email}. You need to enter another email."));
			} else {
			
				$checkEmailUser = GNUser::model()->findByEmail($this->$attribute);
				//$checkEmailUserTmp = GNTmpUser::model()->findByEmail($this->$attribute);
				if (!empty($checkEmailUser))
					$this->addError($attribute, Yii::t("greennet", 'This email has already existed in system'));
			}
		}
	}
	
}