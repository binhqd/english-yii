<?php
// namespace \ExtendedYii\Article\Category;
class ArticleCategory extends GNObject {
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
		return 'article_categories';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required')
		);
	}
	
	public function behaviors()
	{
		return array(
			'slug'	=> array(
				'class'	=> 'greennet.modules.object.components.behaviors.SluggableBehavior',
				'name'	=> 'name'
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
			'id'			=> UsersModule::t('ID'),
			'name' 			=> UsersModule::t('name'),
			'description' 	=> UsersModule::t('Description'),
		);
	}
	
	public function getAll() {
		$this->criteria = new CDbCriteria();
		if(isset($dataStatus)){
			$criteria->condition = 'data_status=:dataStatus';
			$criteria->params = array(
				':dataStatus' => $dataStatus
			);
		}
		$criteria->order = 'created desc';
		
		$pages = new CPagination(count(self::model()->findAll($criteria)));
		
		$pages->pageSize=$limit;
		$pages->applyLimit($criteria);
	}
}