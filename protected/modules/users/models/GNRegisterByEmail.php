<?php
/**
 * Form Model GNRegisterByEmail Dùng để hỗ trợ cho việc register by email
 *
 * @author phihx
 * @date 04-02-2013
 */
class GNRegisterByEmail extends GNTmpUser
{
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
			array('email', 'email'),
			array('email', 'required'),
			array('email', 'checkRegistered'),
		);
	}

	/**
	 * This method is used to create a member.
	 * Return false if cannot create user
	 */
	public function createUser($arrInformation)
	{
		// Set information
		$user = $this;
		$user->email = $arrInformation['email'];
		$user->created = time();
		// Begin transaction
		$transaction = Yii::app()->db->beginTransaction();
		// Validate information and create user
		if ($user->validate()) {
			if ($user->save()) {
				$transaction->commit(); // commit transaction
				// return GNUser
				return $user;
			} else {
				$transaction->rollback(); // rollback transaction
				return false;
			}
		} else {
			$transaction->rollback(); // rollback transaction
			return false;
		}
	}
}