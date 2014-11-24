<?php
/**
 * This class is used to validate firstname
 *
 * @author HuyTBT
 * @created 2013-01-31 3:28 PM
 * @version 1.0
 */
class FirstnameValidator extends CValidator
{
	public $skipOnError=true;

	protected function validateAttribute($object, $attribute)
	{
		if(strlen($object->{$attribute}) < 2) {
			$object->addError($attribute, Yii::t("greennet", 'Firstname is too short. Firstname must be at least 2 characters'));
		} else if(strlen($object->{$attribute}) > 12) {
			$object->addError($attribute, Yii::t("greennet", 'Firstname is too long. Firstname must be less than 13 characters'));
		} else {
			if (!preg_match("/^[a-z A-Z]+$/", $object->{$attribute})) {
				$object->addError($attribute, Yii::t("greennet", 'We only allow a-z, A-Z characters in your firstname'));
			} else {
				$arrName = array('greennet', 'admin', 'administrator', 'user', 'supporter', 'contact');
				if (in_array(strtolower($object->{$attribute}), $arrName)) {
					$object->addError($attribute, Yii::t("greennet", 'This name is forbiden. Please choose another name'));
				}
			}
		}
	}
}