<?php
/**
 * This class is used to validate lastname
 *
 * @author HuyTBT
 * @created 2013-01-31 3:30 PM
 * @version 1.0
 */
class LastnameValidator extends CValidator
{
	public $skipOnError=true;
	protected function validateAttribute($object, $attribute)
	{
		if(strlen($object->{$attribute}) < 2) {
			$object->addError($attribute, Yii::t("greennet", 'Lastname is too short. Lastname must be at least 2 characters'));
		} else if(strlen($object->{$attribute}) > 24) {
			$object->addError($attribute, Yii::t("greennet", 'Lastname is too long. Lastname must be less than 25 characters'));
		} else {
			if (!preg_match("/^[a-zA-Z]{1}[a-z A-Z]+$/", $object->{$attribute})) {
				$object->addError($attribute, Yii::t("greennet", 'We only allow a-z, A-Z and space in your lastname'));
			}
		}
	}
}