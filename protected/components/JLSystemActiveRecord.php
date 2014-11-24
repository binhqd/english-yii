<?php

/**
 * @ingroup components
 * Base class of a data record
 */
class JLSystemActiveRecord extends JLActiveRecord
{
	private static $systemDB = null;
	public function getDbConnection() {
		if (self::$systemDB !== null)
			return self::$systemDB;
		else
		{
			self::$systemDB = Yii::app()->systemDB;
			if (self::$systemDB instanceof CDbConnection)
			{
				self::$systemDB->setActive(true);
				return self::$systemDB;
			} else
				throw new CDbException(Yii::t('justlook','Active Record requires a "systemDB" CDbConnection application component.'));
		}
	}
}
