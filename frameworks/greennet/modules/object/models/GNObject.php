<?php
class GNObject extends GNActiveRecord
{
	protected $_criterias = array();
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
		return 'core_objects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('description', 'safe'),
		);
	}
	
	public function behaviors()
	{
		return array(
			'slug'	=> array(
				'class' => 'greennet.modules.object.components.behaviors.SluggableBehavior',
				'name'	=> 'name',
			),
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
			'id' => Yii::t("greennet", 'ID'),
			'name' => Yii::t("greennet", 'Name'),
			'alias' => Yii::t("greennet", 'Alias'),
			'description' => Yii::t("greennet", 'Description'),
			'created' => Yii::t("greennet", 'Created')
		);
	}
	
	public function criteria($name) {
		if (!isset($this->_criterias[$name]) || empty($this->_criterias[$name])) {
			$criteria = new GNCriteria();
			$criteria->owner = $this;
			$this->_criterias[$name] = $criteria;
		}
		
		return $this->_criterias[$name];
	}
	
}