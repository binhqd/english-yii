<?php
/**
 * GNChangePasswordForm - This class is used to support change password
 *
 * @author Ngocnm
 * @version 1.0
 * @created 01-Feb-2013
 * @modified 
 */
class GNChangePasswordForm extends GNFormModel
{
	public $password;
	public $confirmPassword;

	/**
	 * Validate in scenario is 'NotForgotPassword'
	 */
	public $currentPassword;
	private $__user;

	/**
	 * Thiet lap cac quy tac xac thuc
	 */
	public function rules()
	{
		return array(
			array('currentPassword', 'required','on'=>'fullchange'),
			array('currentPassword', 'length', 'max'=>128, 'min' => 6,'on'=>'fullchange'),
			array('currentPassword', 'checkOldPassword', 'on'=>'fullchange'),
			array('password', 'required'),
			array('password', 'length', 'max'=>128, 'min' => 6),
			array('confirmPassword', 'required'),
			array('confirmPassword', 'length', 'max'=>128, 'min' => 6),
			 array('confirmPassword', 'compare', 'compareAttribute'=>'password'),
		);
	}

	/**
	 * This method is used to check old password
	 * 
	 * @param string $attribute
	 * @param string $params
	 * @return boolean
	 */
	public function checkOldPassword($attribute, $params)
	{
		$currentUser = GNUser::model()->findByEmail(currentUser()->email);
		$strPassword = GNUser::encryptPassword($this->$attribute, $currentUser->saltkey);
		
		if ($strPassword != $currentUser->password) {
			$this->addError($attribute, Yii::t("greennet", 'The current password is incorrect.'));
			return false;
		} else {
			return true;
		}
	}

	/**
	 * This method is used to set model user for form
	 * 
	 * @param object $user
	 */
	public function setUser($user)
	{
		$this->__user = $user;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'currentPassword' => Yii::t("greennet", 'Current Password'),
			'password' => Yii::t("greennet", 'New Password'),
			'confirmPassword' => Yii::t("greennet", 'Confirm New Password'),
		);
	}

}