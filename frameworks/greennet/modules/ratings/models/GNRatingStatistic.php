<?php
/**
 * @author MinhNC
 * @version 1.0
 * @created 12-Mar-2013 3:30:27 PM
 */

class GNRatingStatistic extends GNActiveRecord
{
	private $_modelRating;
	private $_modelRatingObject;
	private $_modelRatingStatistic;
	
	public function setModels($modelRating, $modelRatingObject, $modelRatingStatistic) {
		$this->_modelRating				= $modelRating;
		$this->_modelRatingObject		= $modelRatingObject;
		$this->_modelRatingStatistic	= $modelRatingStatistic;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RatingStatistics the static model class
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
		return 'rating_statistics';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('object_id, rating_value_id, created', 'required'),
				array('count, type', 'numerical', 'integerOnly'=>true),
				array('average', 'numerical'),
				array('id, object_id, rating_value_id, ratings_id', 'length', 'max'=>16),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, object_id, rating_value_id, ratings_id, average, count, type, created', 'safe', 'on'=>'search'),
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
			'ratingValue' => array(self::BELONGS_TO, 'GNRatingValue', 'rating_value_id'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id' => 'ID',
				'object_id' => Yii::t("greennet", 'Object'),
				'rating_value_id' => Yii::t("greennet", 'Rating Value'),
				'ratings_id' => Yii::t("greennet", 'Ratings'),
				'average' => Yii::t("greennet", 'Average'),
				'count' => Yii::t("greennet", 'Count'),
				'type' => Yii::t("greennet", 'Type'),
				'created' => Yii::t("greennet", 'Created'),
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
		$criteria->compare('object_id',$this->object_id,true);
		$criteria->compare('rating_value_id',$this->rating_value_id,true);
		$criteria->compare('ratings_id',$this->ratings_id,true);
		$criteria->compare('average',$this->average);
		$criteria->compare('count',$this->count);
		$criteria->compare('type',$this->type);
		$criteria->compare('created',$this->created,true);
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	/**
	 * This method is used to get average rating for object, methods include th
	 * argument: strAlias: String, binObjectId: VarBinary, binRatingsId: varBinary.
	 * 
	 * @param binObjectId
	 * @param binRatingsId
	 */
	public function getAverageRate($binObjectId, $binRatingsId, $type) {

		$modelRatingStatistic	= $this->_modelRatingStatistic;
		
		if(!isset($binObjectId) || !isset($binRatingsId) || !isset($type)) {
			throw new Exception(Yii::t("greennet", "Can't get average rating statistic. Because params value is empty."));
		}
		
		$modelRatingStatisticData = $modelRatingStatistic::model()->findByAttributes(array(
			'object_id'					=> $binObjectId,
			'ratings_id'				=> $binRatingsId,
			'type'						=> $type
		));
		
		return (!empty($modelRatingStatisticData) && isset($modelRatingStatisticData->average)) ? $modelRatingStatisticData->average : 0;
	}

	/**
	 * This method is used to get count rating for object, methods include the
	 * argument: strAlias: String, binObjectId: VarBinary, binRatingsId: varBinary.
	 * 
	 * @param binObjectId
	 * @param binRatingValueId
	 * @param binRatingsId
	 */
	public function getCountRate($binObjectId, $binRatingValueId, $type) {

		$modelRatingStatistic	= $this->_modelRatingStatistic;
		
		if(!isset($binObjectId) || !isset($binRatingValueId) || !isset($type)) {
			throw new Exception(Yii::t("greennet", "Can't count rating statistic. Because params value is empty."));
		}
		
		$modelRatingStatisticData = $modelRatingStatistic::model()->findByAttributes(array(
			'object_id'					=> $binObjectId,
			'rating_value_id'			=> $binRatingValueId,
			'type'						=> $type
		));
		
		return (!empty($modelRatingStatisticData) && isset($modelRatingStatisticData->count)) ? $modelRatingStatisticData->count : 0;
	}

	/**
	 * This method is used to get a rating statistic for object.
	 * 
	 * @param binRatingStatisticId
	 */
	public function getRatingStatistic($binRatingStatisticId) {

		$modelRatingStatistic	= $this->_modelRatingStatistic;
		
		if (isset($binRatingStatisticId)) {
			$modelRatingStatisticData = $modelRatingStatistic::model()->findByPk($binRatingStatisticId);
			
			return (!empty($modelRatingStatisticData)) ? $modelRatingStatisticData : array();
		}
		
		return array();
	}

	/**
	 * This method is used to check a object has rating statistic, methods include the
	 * argument: strAlias: String, binObjectId: VarBinary, binRatingValueId: VarBinary.
	 * 
	 * 
	 * @param binObjectId
	 * @param binRatingValueId
	 */
	public function hasRatingStatistic($binObjectId, $binRatingsId, $binRatingValueId) {

		$modelRating			= $this->_modelRating;
		$modelRatingStatistic	= $this->_modelRatingStatistic;
				
		if(!isset($binObjectId) || !isset($binRatingValueId) || !isset($binRatingsId)) {
			return array();
		}
		
		$typeRating = $modelRating::model()->isTypeRating($binRatingsId);
		
		$arrQuery = array();
		
		if($typeRating == $modelRating::TYPE_RATING_COUNT) {
			$arrQuery = array(
				'object_id'					=> $binObjectId,
				'rating_value_id'			=> $binRatingValueId,
				'type'						=> $typeRating
			);
		} else if($typeRating == $modelRating::TYPE_RATING_AVERAGE) {
			$arrQuery = array(
				'object_id'					=> $binObjectId,
				'ratings_id'				=> $binRatingsId,
				'type'						=> $typeRating
			);
		}
		
		if (!empty($arrQuery)) {
			$hasRatingStatistic = $modelRatingStatistic::model()->findByAttributes($arrQuery);
		}
		
		return (isset($hasRatingStatistic) && !empty($hasRatingStatistic)) ? $hasRatingStatistic : array();
	}

	/**
	 * This method is used to save a rating statistic for object. Methods include th
	 * argument: strAlias: String, binObjectId: VarBinary, binRatingsId: VarBinary,
	 * biRatingValueId: VarBinary.
	 * 
	 * @param binObjectId
	 * @param binRatingsId
	 * @param biRatingValueId
	 */
	public function saveRatingStatistic($binObjectId, $binRatingsId, $binRatingValueId) {

		$modelRating			= $this->_modelRating;
		$modelRatingObject		= $this->_modelRatingObject;
		$modelRatingStatistic	= $this->_modelRatingStatistic;
		
		if (!isset($binObjectId) || !isset($binRatingsId) || !isset($binRatingValueId)) {
			throw new Exception(Yii::t("greennet", "Can't save rating statistic. Because params value is empty."));
		}
		//Check has rating statistic for object
		$hasRatingStatistic = $modelRatingStatistic::hasRatingStatistic($binObjectId, $binRatingsId, $binRatingValueId);
		
		//Get type rating for object
		$typeRating = $modelRating::model()->isTypeRating($binRatingsId);
		
		//If has rating satistic
		if (!empty($hasRatingStatistic)) {
			$binRatingStatisticId = $hasRatingStatistic->id;
			if ($typeRating == $modelRating::TYPE_RATING_COUNT) {
				//Update count rate statistic for object
				return $modelRatingStatistic::model()->updateCountRate($binRatingStatisticId, $binObjectId, $binRatingsId, $binRatingValueId);
				
			} elseif($typeRating == $modelRating::TYPE_RATING_AVERAGE) {
				//Update average rate statistic for object
				return $modelRatingStatistic::model()->updateAverageRate($binRatingStatisticId, $binObjectId, $binRatingsId, $binRatingValueId);
				
			}
		} else {
			//If has'nt rating satistic
			
// 			$modelRatingStatistic = new self();
			$modelRatingStatistic->object_id				= $binObjectId;
			$modelRatingStatistic->rating_value_id			= $binRatingValueId;
			$modelRatingStatistic->ratings_id				= $binRatingsId;
			$modelRatingStatistic->type						= $typeRating;
			$modelRatingStatistic->created					= date("Y-m-d H:i:s");
			
			if ($typeRating == $modelRating::TYPE_RATING_COUNT) {
				$modelRatingStatistic->count = 1;
			} elseif($typeRating == $modelRating::TYPE_RATING_AVERAGE) {
				$modelRatingStatistic->average = $modelRatingObject::model()->getAverageRate($binObjectId, $binRatingsId, $binRatingValueId);
			}
			
			if (!$modelRatingStatistic->validate()) {
				throw new Exception(Yii::t("greennet", "Can't save statistic for object, because data attibutes is not validate."));
			}
			
			$saveStatistic = $modelRatingStatistic->save();
			
			if ($saveStatistic) {
				$modelRatingStatistic::model()->synRatingStatisticByRatingId($binObjectId, $binRatingsId, $binRatingValueId);
				return $modelRatingStatistic;
			}
			
			$err_saveStatistic = $modelRatingStatistic->errors;
				
			if(empty($err_saveStatistic)){
				throw new Exception(Yii::t("greennet", "Can't save rate statistic for object. Because error by system."));
			} else {
				list($field, $_err) = each($err_saveStatistic);
				
				throw new Exception(sprintf(Yii::t("greennet", "Can't save rate statistic for object. Because errors is : %s"), $_err[0]));
			}
		}
	}

	/**
	 * This method is used to update count rating for object
	 * @param binRatingStatisticId
	 */
	public function updateCountRate($binRatingStatisticId, $binObjectId, $binRatingsId, $binRatingValueId, $isSync = true) {

		$modelRatingObject		= $this->_modelRatingObject;
		$modelRatingStatistic	= $this->_modelRatingStatistic;
		
		if (!isset($binRatingStatisticId) || !isset($binObjectId) || !isset($binRatingValueId) || !isset($binRatingsId)) {
			return false;
		}
		
		$modelRatingStatisticData = $modelRatingStatistic::model()->findByPk($binRatingStatisticId);
		
		if (!empty($modelRatingStatisticData)) {
			$count =$modelRatingObject::model()->getCountRate($binObjectId, $binRatingValueId);
			$modelRatingStatisticData->count = $count;
			
			$saveStatistic = $modelRatingStatisticData->save();
				
			if ($saveStatistic) {
				if ($isSync) {
					$modelRatingStatistic::model()->synRatingStatisticByRatingId($binObjectId, $binRatingsId, $binRatingValueId);
				}
				
				return $modelRatingStatisticData;
			}
				
			$err_saveStatistic = $modelRatingStatisticData->errors;
			
			if(empty($err_saveStatistic)){
				throw new Exception(Yii::t("greennet", "Can't update count rate statistic for object. Because error by system."));
			} else {
				list($field, $_err) = each($err_saveStatistic);
			
				throw new Exception(sprintf(Yii::t("greennet", "Can't update count rate statistic for object. Because errors is : %s"), $_err[0]));
			}
		}
		
		return false;
	}

	/**
	 * This method is used to update average rating for object 
	 * @param binRatingStatisticId
	 */
	public function updateAverageRate($binRatingStatisticId, $binObjectId, $binRatingsId, $binRatingValueId) {

		$modelRatingObject		= $this->_modelRatingObject;
		$modelRatingStatistic	= $this->_modelRatingStatistic;
		
		if (!isset($binRatingStatisticId) || !isset($binObjectId) || !isset($binRatingsId) || !isset($binRatingValueId)) {
			return false;
		}
		
		$modelRatingStatisticData = $modelRatingStatistic::model()->findByPk($binRatingStatisticId);
		
		if (!empty($modelRatingStatisticData)) {
			$average =$modelRatingObject::model()->getAverageRate($binObjectId, $binRatingsId, $binRatingValueId);
			$modelRatingStatisticData->average = $average;
				
			$updateStatistic = $modelRatingStatisticData->save();
		
			if ($updateStatistic) {
				return $modelRatingStatisticData;
			}
		
			$err_updateStatistic = $modelRatingStatisticData->errors;
				
			if(empty($err_updateStatistic)){
				throw new Exception(Yii::t("greennet", "Can't update count rate statistic for object. Because error by system."));
			} else {
				list($field, $_err) = each($err_updateStatistic);
					
				throw new Exception(sprintf(Yii::t("greennet", "Can't update count rate statistic for object. Because errors is : %s"), $_err[0]));
			}
		}
		
		return false;
	}
	
	/**
	 * This method is used to sync data rating statistic
	 * @param unknown_type $binObjectId
	 * @param unknown_type $binRatingsId
	 * @throws Exception
	 * @return boolean
	 */
	public function synRatingStatisticByRatingId($binObjectId, $binRatingsId, $binRatingValueId) {

		$modelRating			= $this->_modelRating;
		$modelRatingStatistic	= $this->_modelRatingStatistic;
		
		if (!isset($binObjectId) || !isset($binRatingsId) || !isset($binRatingValueId)) {
			throw new Exception(Yii::t("greennet", "Can't save rating statistic. Because params value is empty."));
		}
		
		$modelRatingStatisticData = $modelRatingStatistic::model()->findAllByAttributes(array(
			'object_id'					=> $binObjectId,
			'ratings_id'				=> $binRatingsId
		));
		
		if (!empty($modelRatingStatisticData)) {
			foreach ($modelRatingStatisticData as $value) {
				if ($value->type == $modelRating::TYPE_RATING_COUNT) {
					if($binRatingValueId != $value->rating_value_id) {
						$modelRatingStatistic::model()->updateCountRate($value->id, $binObjectId, $value->ratings_id, $value->rating_value_id, false);
					}
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * This method is used to get statistic for object
	 * @param unknown_type $binObjectId
	 * @param unknown_type $binRatingsId
	 */
	public function getStatisticForObject($binObjectId, $binRatingsId, $getPeople = false) {

		$modelRating			= $this->_modelRating;
		$modelRatingStatistic	= $this->_modelRatingStatistic;
		
		if (!isset($binObjectId) || !isset($binRatingsId)) {
			throw new Exception(Yii::t("greennet", "Can't get statistic rate for object. Because rating params is invalid."));
		}

		$modelRatingData = $modelRating::model()->getRatingsById($binRatingsId);
		
		if (empty($modelRatingData)) {
			throw new Exception(Yii::t("greennet", "Can't get statistic rate for object. Because rating is emtpy."));
		}
		$type = $modelRatingData->type;
		$ratingAlias = $modelRatingData->ratings_alias;
		$return = array();
		
		$criteria = new CDbCriteria;
		$criteria->together = true;
		$criteria->condition = "object_id = :binObjectId AND ratings_id = :binRatingsId AND type = :TYPE_RATING";
		$criteria->params = array(
			':binObjectId'			=> $binObjectId,
			':binRatingsId'			=> $binRatingsId,
			':TYPE_RATING'			=> $type,
		);
			
		$modelStatistic = $modelRatingStatistic::model()->findAll($criteria);
		
		$people = array();

		//Get statistic if type raring is COUNT
		if ($type == $modelRating::TYPE_RATING_COUNT) {
			if (!empty($modelStatistic)) {
				foreach ($modelStatistic as $value) {
					$rating_value_alias = isset($value->ratingValue->rating_value_alias) ? $value->ratingValue->rating_value_alias : null;
					
					if ($getPeople == true || $getPeople == 'true') {
						$binRatingValueId = $value->rating_value_id;
						$people = $modelRatingStatistic::model()->getAllListUserRatedForObject($binObjectId, $binRatingValueId, null, false);
					}
					
					$return[$rating_value_alias] = array(
						'value'		=> (int)$value->count,
						'people'	=> $people,
						'name'		=> $modelRating->ratings_alias
					);
				}
			}
		} elseif ($type == $modelRating::TYPE_RATING_AVERAGE) {
		//Get statistic if type raring is AVERAGE
			if(empty($modelStatistic)) {
				$return[$ratingAlias] = array(
					'value'		=> 0,
					'people'	=> array(),
					'name'		=> $modelRating->ratings_alias
				);
				
				return $return;
			}
			
			if($modelRatingStatistic::model()->count($criteria) == 1) {
				foreach ($modelStatistic as $value) {
					if ($getPeople == true || $getPeople == 'true') {
						$people = $modelRatingStatistic::model()->getAllListUserRatedForObject($binObjectId, NULL, $binRatingsId, true);
					}

					$return[$ratingAlias] = array(
						'value'		=> $value->average,
						'people'	=> $people,
						'name'		=> $modelRating->ratings_alias
					);
				}
			} else {
				throw new Exception(Yii::t("greennet", "Can't get statistic rate for object. Because rating statistic is multi record."));
			}
		}

		return $return;
	}
	
	/**
	 * This is method is used to get info of all user has rated this object
	 * @param unknown_type $binObjectId
	 * @param unknown_type $binRatingValueId
	 * @return multitype:|multitype:multitype:Ambigous <multitype:, string> NULL
	 */
	public function getAllListUserRatedForObject($binObjectId, $binRatingValueId = NULL, $binRatingsId = NULL, $byRatingsId = false) {

		$modelRatingObject		= $this->_modelRatingObject;
		
		Yii::import('greennet.modules.users.models.GNUser');
		if (!isset($binObjectId)) {
			return array();
		}
		
		if ($byRatingsId && !empty($binRatingsId)) {
			$listRated = $modelRatingObject::model()->getAllListRatedByRatingsId($binObjectId, $binRatingsId);
		} elseif(!$byRatingsId && !empty($binRatingValueId)) {
			$listRated = $modelRatingObject::model()->getAllListRated($binObjectId, $binRatingValueId);
		} else {
			throw new Exception(Yii::t("greennet", "Can't get statistic rate for object. Because params to get user rated is invalid"));
		}

		$people = array();
		
		if (!empty($listRated)) {
			foreach ($listRated as $value) {
				$binUserId = $value->user_id;
				
				if($binUserId != currentUser()->id) {
					$modelUser = GNUser::model()->getUserInfo($binUserId);
					
					if (!empty($modelUser)) {
						$people[] = array(
							'id'			=> IDHelper::uuidFromBinary($modelUser->id),
							'username'		=> $modelUser->username,
							'displayname'	=> $modelUser->displayname
						);
					}
				}
			}
		}
		
		return $people;
	}
}