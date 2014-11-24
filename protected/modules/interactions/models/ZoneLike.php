<?php
Yii::import('greennet.modules.ratings.models.GNRating');
class ZoneLike extends GNRating {
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getName() {
		return __CLASS__;
	}
	
	public function tableName()
	{
		return 'zone_likes';
	}
}
