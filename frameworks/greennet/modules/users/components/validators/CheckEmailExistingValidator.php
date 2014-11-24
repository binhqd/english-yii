<?php
/**
 * This class is used to validate lastname
 *
 * @author HuyTBT
 * @created 2013-01-31 3:30 PM
 * @version 1.0
 */
class CheckEmailExistingValidator extends CValidator
{
	public $skipOnError=true;
	public function validateAttribute($object, $attribute)
	{
		if (!$object->hasErrors()) {
			if (GNTmpUser::model()->findByEmail($object->{$attribute}) || GNUser::model()->findByEmail($object->{$attribute})) {
				$object->addError($attribute, Yii::t("greennet", 'Your email already existed in our system.'));
			}
		}
	}
}