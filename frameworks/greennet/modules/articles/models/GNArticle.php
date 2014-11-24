<?php
class GNArticle extends GNObject {
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
		return 'articles';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description, content', 'required')
		);
	}
	
	public function behaviors()
	{
		return array(
			'slug'	=> array(
				'class'	=> 'greennet.modules.object.components.behaviors.SluggableBehavior',
				'name'	=> 'title'
			)
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//'profile' => array(self::HAS_ONE, 'GNUserProfile', 'user_id'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
// 				'id'			=> UsersModule::t('ID'),
// 				'name' 			=> UsersModule::t('name'),
// 				'description' 	=> UsersModule::t('Description'),
// 				'alias' 		=> UsersModule::t('Alias'),
// 				'content' 		=> UsersModule::t('Content'),
// 				'created' 		=> UsersModule::t('Created'),
// 				'image'			=> UsersModule::t('Image'),
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('image',$this->image,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>'10',
			)
		));
	}
}