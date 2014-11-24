<?php

class ZoneUserPropertiesTmp extends JLActiveRecord
{
	const CATEGORY_SUMARY = "sumary";
	const CATEGORY_EXPERIENCE = "experience";
	const CATEGORY_EDUCATION = "education";
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GNSocial the static model class
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
		return 'user_properties_tmp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'length', 'max'=>16),
			// array('alias, name', 'length', 'max'=>48),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id', 'safe', 'on'=>'search'),
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
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'alias' => 'Alias',
			'name' => 'Name',
		);
	}
	public function removePropertyOfCategory($category = null, $binUserId = null){
		$this->deleteAllByAttributes(array(
			'user_id'=>$binUserId,
			'category'=>$category
		));
	}
	public function getSummary($binUserId = null){
		return $this->findByAttributes(array(
			'user_id'=>$binUserId,
			'category'=>self::CATEGORY_SUMARY
		));
	}
	public function getExperience($binUserId = null){
		return $this->findAllByAttributes(array(
			'user_id'=>$binUserId,
			'category'=>self::CATEGORY_EXPERIENCE
		),array(
			'order'=>'created desc'
		));
	}
	
	public function getEducation($binUserId = null){
		return $this->findAllByAttributes(array(
			'user_id'=>$binUserId,
			'category'=>self::CATEGORY_EDUCATION
		),array(
			'order'=>'created desc'
		));
	}
}