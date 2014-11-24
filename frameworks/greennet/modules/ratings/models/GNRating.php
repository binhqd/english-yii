<?php

/**
 * @author MinhNC
 * @version 1.0
 * @created 12-Mar-2013 3:30:26 PM
 */

class GNRating extends GNActiveRecord
{
	private $_modelRating;
	private $_modelRatingObject;
	private $_modelRatingStatistic;
	private $_modelRatingValue;
	
	public function setModels($modelRating, $modelRatingObject, $modelRatingStatistic, $modelRatingValue) {
		$this->_modelRating				= $modelRating;
		$this->_modelRatingObject		= $modelRatingObject;
		$this->_modelRatingStatistic	= $modelRatingStatistic;
		$this->_modelRatingValue		= $modelRatingValue;
	}
	
	/**
	 * This const is used to define type rating is average.
	 */
	const TYPE_RATING_AVERAGE = 1;
	/**
	 * This const is used to define type rating is count.
	 */
	const TYPE_RATING_COUNT = 0;
	
	/**
	 * Define const variale type rating
	 */
	const TYPE_RATING_LIKE = 'like';
	const TYPE_RATING_OK = 'ok';
	const TYPE_RATING_STAR = 'star';
	const TYPE_RATING_HELPFUL = 'helpful';
	const TYPE_RATING_RECOMMEND = 'recommned';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Rating the static model class
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
		return 'ratings';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('ratings_alias, name', 'required'),
				array('ratings_alias', 'unique'),
				array('type', 'numerical', 'integerOnly'=>true),
				array('id', 'length', 'max'=>16),
				array('ratings_alias, name', 'length', 'max'=>255),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, ratings_alias, name, type', 'safe', 'on'=>'search'),
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
			'ratingValues' => array(self::HAS_MANY, 'GNRatingValues', 'ratings_id'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ratings_alias' => 'Ratings Alias',
			'name' => 'Name',
			'type' => 'Type',
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
		$criteria->compare('ratings_alias',$this->ratings_alias,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type);
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	/**
	 * This method is used to check rating type by rating id.
	 * 
	 * @param binRatingsId
	 */
	public function isTypeRating($binRatingsId) {
		
		$modelRating			= $this->_modelRating;
		
		if (isset($binRatingsId)) {
			$modelRatingData = $modelRating::model()->findByPk($binRatingsId);
			
			if (!empty($modelRatingData)) {
				return isset($modelRatingData->type) ? $modelRatingData->type : -1;
			}
		}
		return -1;
	}

	/**
	 * This method is used to get a rating by rating alias.
	 * 
	 * @param strAlias
	 */
	public function getRatingsByAlias($strAlias) {

		$modelRating			= $this->_modelRating;
		
		if (isset($strAlias)) {
			$modelRatingData = $modelRating::model()->findByAttributes(array(
				'ratings_alias'		=> $strAlias
			));
			
			return (!empty($modelRatingData)) ? $modelRatingData : array();
		}
		return array();
	}

	/**
	 * This method is used to get a rating by rating id.
	 * 
	 * @param binRatingsId
	 */
	public function getRatingsById($binRatingsId) {

		$modelRating			= $this->_modelRating;
		
		if (isset($binRatingsId)) {
			$modelRatingData = $modelRating::model()->findByPk($binRatingsId);
			
			return (!empty($modelRatingData)) ? $modelRatingData : array();
		}
		
		return array();
	}
	
	/**
	 * This method is used to get rate for object before load rating and after rated
	 * @param unknown_type $strRatingAlias
	 * @param unknown_type $binObjectId
	 */
	public function getRate($strRatingAlias, $binObjectId, $getPeople = false) {

		$modelRating			= $this->_modelRating;
		$modelRatingObject		= $this->_modelRatingObject;
		$modelRatingStatistic	= $this->_modelRatingStatistic;
		$modelRatingValue		= $this->_modelRatingValue;
		

		if (!isset($binObjectId) || !isset($strRatingAlias)) {
			return array();
		}
		
		$ratingAlias	= $modelRating::model()->getRatingsByAlias($strRatingAlias);
		$getPeople		= ($getPeople == 'true') ? true : false;
		
		if (!empty($ratingAlias)) {
			
			$results = array();
			
			$ratingValue = $modelRatingValue::model()->getRatingByGroupId($ratingAlias->id);
			
			if (!empty($ratingValue)) {
				
				$default = array();
				$statistic = array();
				$rated = -1;
				
				foreach ($ratingValue as $key=>$value) {
					
					$default[] = array(
						'id'					=> IDHelper::uuidfrombinary($value->id),
						'rating_value_alias'	=> $value->rating_value_alias,
						'name'					=> $value->name,
						'value'					=> $value->value
					);
					
					$_statistic[$value->rating_value_alias] = array(
						'value'	=> 0
					);
				}

				$statistic = $modelRatingStatistic::model()->getStatisticForObject($binObjectId, $ratingAlias->id, $getPeople);

				if (empty($statistic)) {
					$statistic = $_statistic;
				}
				
				if(currentUser()->id == -1) {
					$rated = array(
						'value'		=> -1,
						'alias'		=> ''
					);
				} else {
					$hasRated = $modelRatingObject::model()->hasRate($binObjectId, currentUser()->id, $ratingAlias->id);
					
					if (!empty($hasRated)) {
						$rating_value_alias = isset($hasRated->ratingValue->rating_value_alias) ? $hasRated->ratingValue->rating_value_alias : '';
						
						$rated = array(
							'value'		=> isset($hasRated->value) ? $hasRated->value : -1,
							'alias'		=> $rating_value_alias
						);
					} else {
						$rated = array(
							'value'		=> -1,
							'alias'		=> ''
						);
					}
				}
				
				$options = array(
					'rating_alias'	=> $strRatingAlias,
					'object_id'		=> IDHelper::uuidFromBinary($binObjectId),
					'get_people'	=> $getPeople
				);
				
				$results = array(
					//'rating_alias'	=> $strRatingAlias,
					//'object_id'		=> IDHelper::uuidFromBinary($binObjectId),
					'default'		=> $default,
					'statistic'		=> $statistic,
					'rating'		=> $rated,
					'options'		=> $options
				);
			}
			
			return $results;
		}
	}
	
	/**
	 * This method is used to get rating for object: This method is extends from method getRate and call thi method to return result
	 * @param unknown_type $strRatingAlias
	 * @param unknown_type $binObjectId
	 * @param unknown_type $getPeople
	 */
	public function getRatingForObject($strRatingAlias, $binObjectId, $getPeople = false) {

		$modelRating			= $this->_modelRating;
		
		$rating = $modelRating::model()->getRate($strRatingAlias, $binObjectId, $getPeople);
		
		$result = array(
			'error'		=> false,
			'rated'		=> $rating
		);
		
		$encode_rating = CJSON::encode($result);
		return $encode_rating;
	}
	
	/**
	 * This is method is used to created new rating
	 * @param unknown_type $arrAttributes
	 * @throws Exception
	 * @return GNRating
	 */
	public function createRating($arrAttributes) {

		$modelRating			= $this->_modelRating;
		
		if (empty($arrAttributes)) {
			throw new Exception(Yii::t("greennet", "Can't create rating. Because params attibutes is invalid."));
		}
		
// 		$modelRating = new self();
		
		$modelRating->attributes = $arrAttributes;
		if (!$modelRating->validate()) {
			throw new Exception(Yii::t("greennet", "Can't create rating. Because data attibutes is not validate."));
		}
		
		$saveRating = $modelRating->save();
		if ($saveRating) {
			return $modelRating;
		}
		
		$err_saverating = $modelRating->errors;
		
		if(empty($err_saverating)){
			throw new Exception(Yii::t("greennet", "Can't create rating. Because error by system."));
		} else {
			list($field, $_err) = each($err_saverating);
		
			throw new Exception(sprintf(Yii::t("greennet", "Can't create rating. Because errors is : %s"), $_err[0]));
		}
	}
	
	/**
	 * This method is used to remove rating and all rating value is children of current rating
	 * @param unknown_type $binRatingsId
	 * @throws Exception
	 * @return boolean
	 */
	public function removeRating($binRatingsId) {

		$modelRating			= $this->_modelRating;
		$modelRatingValue		= $this->_modelRatingValue;
		
		if (!isset($binRatingsId) || empty($binRatingsId)) {
			throw new Exception(Yii::t("greennet", "Can\'t create rating. Because params attibutes is invalid."));
		}
		
		$transaction = Yii::app()->db->beginTransaction();
		
		try {
			
			$removeAllRatingValue = $modelRatingValue::model()->removeAllByRatingId($binRatingsId);
			
			if (!$removeAllRatingValue) {
				throw new Exception(Yii::t("greennet", "Can\'t remove rating. Because can\'t remove all rating value."));
			}
			
			$modelRatingData = $modelRating::model()->findByPk($binRatingsId);
			
			if (empty($modelRatingData)) {
				throw new Exception(Yii::t("greennet", "Can\'t remove rating. Because model rating is empty."));
			}
			
			$removeRating = $modelRatingData->delete();
			
			if ($removeRating) {
				$transaction->commit();
				
				return true;
			}
			
			$err_removeRating = $modelRating->errors;
			
			if(empty($err_removeRating)){
				
				throw new Exception(Yii::t("greennet", "Can\'t remove rating. Because error by system."));
				
			} else {
				list($field, $_err) = each($err_removeRating);
			
				throw new Exception(sprintf(Yii::t("greennet", "Can\'t remove rating. Because errors is : %s"), $_err[0]));
			}
		} catch (Exception $e) {
			$transaction->rollback();
			
			throw new Exception($e->getMessage());
		}
	}
}