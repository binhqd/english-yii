<?php
/**
 * ApiChangePasswordForm - This model is used to change password user
 * 
 * @author NgocNM
 * @version 1.0
 */
class APIChangePasswordForm extends CFormModel
{
	/**
	 * @var string New password
	 */
	public $newPassword;
	/**
	 * @var string Old password
	 */
	public $oldPassword;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('oldPassword', 'required', 'message'=>Yii::t('Youlook', 'Current password can not blank.')),
			array('oldPassword', 'checkOldPassword'),
			array('newPassword', 'required', 'message'=>Yii::t('Youlook', 'New password can not blank.')),
			array('newPassword', 'length', 'min' => 6),
		);
	}

	/**
	 * This method is used to check old password
	 * @param string $attribute
	 * @param string $params
	 * @return boolean
	 */
	public function checkOldPassword($attribute, $params)
	{
		$currentUser = ZoneUser::model()->findByEmail(currentUser()->email);
		$strPassword = GNUser::encryptPassword($this->$attribute, $currentUser->saltkey);

		if ($strPassword != $currentUser->password) {
			$this->addError($attribute, Yii::t("Youlook", 'Current password is incorrect.'));
			return false;
		} else {
			return true;
		}
	}
}