<?php
Yii::import('greennet.modules.ratings.models.GNRatingObjectAlias');
class ZoneLikeObjectAlias extends GNRatingObjectAlias {
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getName() {
		return __CLASS__;
	}
	public function tableName()
	{
		return 'zone_like_object_aliases';
	}
}
