<?php
Yii::import('greennet.modules.articles.models.GNArticle');
class DNTArticle extends GNArticle {
	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	

	public function getName() {
		return __CLASS__;
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dnt_articles';
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
// 	public function attributeLabels()
// 	{
// 		return array(
			
// 		);
// 	}
}