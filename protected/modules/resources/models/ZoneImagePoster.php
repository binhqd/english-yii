<?php
class ZoneImagePoster extends GNActiveRecord {
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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zone_image_poster';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
					
		);
	}
	
	public function behaviors()
	{
		return array(
			
		);
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return CMap::mergeArray(
			parent::relations(),
			array(
				'user' => array(self::BELONGS_TO, 'ZoneUser', 'holder_id'),
			)
		);
	}
	public function createPoster($attributes = null){
		$model = new ZoneImagePoster;
		$model->image_id = $attributes['image_id'];
		$model->holder_id = $attributes['holder_id'];
		
		if(!$model->save()){
			$errors  = $model->getErrors();
			list ($field, $_errors) = each ($errors);
			
			throw new Exception($_errors[0]);
		}
	}
}