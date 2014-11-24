<?php
Yii::import('greennet.modules.gallery.models.GNGalleryItem');
class ArticleImage extends GNGalleryItem {
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
		return 'images';
	}
	
	public function getImages($id){
		return self::model()->findAllByAttributes(array('object_id'=>$id));
	}
}