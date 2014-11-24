<?php
Yii::import('greennet.modules.social.models.GNLinkedAccount');
class ZoneLinkedAccount extends GNLinkedAccount {
	
	public static function isCreatePassword($binID = null){
		if(!empty($binID)){
			return self::model()->findByAttributes(array('user_id'=>$binID));
		}
	}
}