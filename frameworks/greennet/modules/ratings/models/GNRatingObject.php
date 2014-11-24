<?php
/**
 * @author MinhNC
 * @version 1.0
 * @created 12-Mar-2013 3:30:27 PM
 */

class GNRatingObject extends GNActiveRecord
{
	
	private $_modelRating;
	private $_modelRatingObject;
	
	public function setModels($modelRating, $modelRatingObject, $modelRatingValue) {
		$this->_modelRating				= $modelRating;
		$this->_modelRatingObject		= $modelRatingObject;
		$this->_modelRatingValue		= $modelRatingValue;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RatingObjects the static model class
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
		return 'rating_objects';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('object_id, user_id, ratings_id, rating_value_id, value', 'required'),
				array('value', 'numerical', 'integerOnly'=>true),
				array('id, object_id, user_id, ratings_id, rating_value_id', 'length', 'max'=>16),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, object_id, user_id, ratings_id, rating_value_id, value, created', 'safe', 'on'=>'search'),
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
				'object_id' => 'Object',
				'user_id' => 'User',
				'ratings_id' => 'Rating',
				'rating_value_id' => 'Rating Value',
				'value' => 'Value',
				'created' => 'Created',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('ratings_id',$this->ratings_id,true);
		$criteria->compare('rating_value_id',$this->rating_value_id,true);
		$criteria->compare('value',$this->value);
		$criteria->compare('created',$this->created,true);
	
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * This method is used to get a rating object with params is: $binRatingObjectId: varBinary.
	 * 
	 * @param binRatingObjectId
	 */
	public function getRatingObjectById($binRatingObjectId) {
		
		$modelRatingObject		= $this->_modelRatingObject;
		
		if (isset($binRatingObjectId)) {
			$modelratingObject = $modelRatingObject::model()->findByPk($binRatingObjectId);
			
			return (!empty($modelratingObject)) ? $modelratingObject : array();
		}
		
		return array();
	}

	/**
	 * This method is used to check a object has rate, methods include the argument:
	 * String, binObjectId: VarBinary, binUserId: VarBinary, binRatingValueId:
	 * VarBinary.
	 * 
	 * @param binObjectId
	 * @param binUserId
	 * @param binRatingValueId
	 */
	public function hasRate($binObjectId, $binUserId, $binRatingsId) {

		$modelRatingObject		= $this->_modelRatingObject;
		
		if (isset($binObjectId) && isset($binUserId) && isset($binRatingsId)) {
			$modelRatingObjectData = $modelRatingObject::model()->findByAttributes(array(
				'object_id'					=> $binObjectId,
				'user_id'					=> $binUserId,
				'ratings_id'				=> $binRatingsId
			));
			
			return (!empty($modelRatingObjectData)) ? $modelRatingObjectData : array();
		}
		
		return array();
	}

	/**
	 * This method is used to check a object has rate with rating value, methods include the argument:
	 * String, binObjectId: VarBinary, binUserId: VarBinary, binRatingValueId:
	 * VarBinary.
	 *
	 * @param binObjectId
	 * @param binUserId
	 * @param binRatingValueId
	 */
	public function hasRateWithRatingValue($binObjectId, $binUserId, $binRatingsId, $binRatingValueId) {

		$modelRatingObject		= $this->_modelRatingObject;
		
		if (isset($binObjectId) && isset($binUserId) && isset($binRatingsId) && isset($binRatingValueId)) {
			$modelRatingObjectData = $modelRatingObject::model()->findByAttributes(array(
				'object_id'					=> $binObjectId,
				'user_id'					=> $binUserId,
				'ratings_id'				=> $binRatingsId,
				'rating_value_id'			=> $binRatingValueId
			));
				
			return (!empty($modelRatingObjectData)) ? $modelRatingObjectData : array();
		}
		
		return array();
	}
	
	/**
	 * This method is used to process rating for object.
	 * 
	 * @param binObjectId
	 * @param binUserId
	 * @param binRatingValueId
	 * @param intRateValue
	 */
	public function rate($binObjectId, $binUserId, $binRatingValueId, $intRateValue) {

		$modelRatingObject		= $this->_modelRatingObject;
		$modelRatingValue		= $this->_modelRatingValue;
		
		if (!isset($binObjectId) || !isset($binUserId) || !isset($binRatingValueId) ||!isset($intRateValue)) {
			throw new Exception(Yii::t("greennet", "Can't rate for object, because params is invalid."));
		}
		
		$modelRatingValueData = $modelRatingValue::model()->getRatingValueById($binRatingValueId);
		
		//Rating value is empty
		if (empty($modelRatingValueData)) {
			throw new Exception(Yii::t("greennet", "Can't rate for object, because rating value is empty."));
		}
		$binRatingsId = $modelRatingValueData->ratings_id;
		$hasRate = $modelRatingObject::model()->hasRate($binObjectId, $binUserId, $binRatingsId);
		
		//Has'nt rate
		if (empty($hasRate)) {
			$modelRatingObjectNew = new $modelRatingObject;
			$modelRatingObjectNew->object_id = $binObjectId;
			$modelRatingObjectNew->user_id = $binUserId;
			$modelRatingObjectNew->ratings_id = $binRatingsId;
			$modelRatingObjectNew->rating_value_id = $binRatingValueId;
			$modelRatingObjectNew->value = $intRateValue;
			$modelRatingObjectNew->created = date("Y-m-d H:i:s");

			//Validate is invalid
			if (!$modelRatingObjectNew->validate()) {
				throw new Exception(Yii::t("greennet", "Can't rate for object, because data attibutes is not validate."));
			}
		
			$saveRate = $modelRatingObjectNew->save();
			
			if ($saveRate) {
				return $modelRatingObjectNew;
			}
				
			$err_saveRate = $modelRatingObjectNew->errors;
			
			if(empty($err_saveRate)){
				throw new Exception(Yii::t("greennet", "Can't save rate for object. Because error by system."));
			} else {
				list($field, $_err) = each($err_saveRate);
				throw new Exception(sprintf(Yii::t("greennet", "Can't save rate for object. Because errors is : %s"), $_err[0]));
			}
		} else {
			
			return $modelRatingObject::model()->updateValueRateObject($hasRate->id, $binRatingValueId, $intRateValue);
		}
	}
	
	/**
	 * This method is used to get count rate for object
	 * @param unknown_type $binObjectId
	 * @param unknown_type $binRatingsId
	 */
	public function getCountRate($binObjectId, $binRatingValueId) {

		$modelRatingObject		= $this->_modelRatingObject;
		
		if (!isset($binObjectId) || !isset($binRatingValueId)) {
			throw new Exception(Yii::t("greennet", "Can't rate for object, because data attibutes is not validate."));
		}
		
		$cdbCriteria = new CDbCriteria();
		$cdbCriteria->condition = 'object_id = :binObjectId AND rating_value_id = :binRatingValueId';
		$cdbCriteria->params = array(
			':binObjectId'				=> $binObjectId,
			':binRatingValueId'			=> $binRatingValueId,
		);
		
		$countRate = $modelRatingObject::model()->count($cdbCriteria);
		
		return $countRate;
	}
	
	/**
	 * This method is used to get count rate by rating id for object
	 * @param unknown_type $binObjectId
	 * @param unknown_type $binRatingsId
	 */
	public function getCountRateByRatingId($binObjectId, $binRatingsId) {

		$modelRatingObject		= $this->_modelRatingObject;
		
		if (!isset($binObjectId) || !isset($binRatingsId)) {
			throw new Exception(Yii::t("greennet", "Can't rate for object, because data attibutes is not validate."));
		}
	
		$cdbCriteria = new CDbCriteria();
		$cdbCriteria->condition = 'object_id = :binObjectId AND ratings_id = :binRatingsId';
		$cdbCriteria->params = array(
			':binObjectId'				=> $binObjectId,
			':binRatingsId'				=> $binRatingsId,
		);
	
		$countRate = $modelRatingObject::model()->count($cdbCriteria);
	
		return $countRate;
	}
	
	/**
	 * This method is used to get average rate for object
	 * @param unknown_type $binObjectId
	 * @param unknown_type $binRatingsId
	 */
	public function getAverageRate($binObjectId, $binRatingsId, $binRatingValueId) {

		$modelRatingObject		= $this->_modelRatingObject;
		
		if (!isset($binObjectId) || !isset($binRatingValueId) || !isset($binRatingsId)) {
			throw new Exception(Yii::t("greennet", "Can't rate for object, because data attibutes is not validate."));
		}
		
		//Get count rating for object
		$count = $modelRatingObject::model()->getCountRateByRatingId($binObjectId, $binRatingsId);
		
		$average = 0;
		
		if ($count > 0) {
			$model = Yii::app()->db->createCommand()
				->select('sum(value) as sumrate')
				->from($modelRatingObject::model()->tableName())
				->group('object_id, ratings_id')
				->limit('1')
				->where('object_id = :binObjectId AND ratings_id = :binRatingsId', 
						array(
							':binObjectId'			=> $binObjectId,
							':binRatingsId'			=> $binRatingsId
						))
				->queryAll();

			$sum = (int)$model[0]['sumrate'];

			// Calculate Average of rate
			$average = 1.0 * $sum / $count;
		}
		
		return $average;
	}
	
	/**
	 * This method is used to update value rate for object
	 * @param unknown_type $binRatingObject
	 * @param unknown_type $intRateValue
	 * @throws Exception
	 * @return Ambigous <CActiveRecord, mixed, NULL, multitype:, multitype:unknown Ambigous <CActiveRecord, NULL> , multitype:unknown >
	 */
	public function updateValueRateObject($binRatingObject, $binRatingValueId, $intRateValue) {

		$modelRatingObject		= $this->_modelRatingObject;
		
		if (!isset($binRatingObject) || !isset($intRateValue) || !isset($binRatingValueId)) {
			throw new Exception(Yii::t("greennet", "Can't update value rate for object. Because data attibutes is not validate."));
		}
		
		$modelRatingObjectData = $modelRatingObject::model()->findByPk($binRatingObject);
		
		if (empty($modelRatingObjectData)) {
			throw new Exception(Yii::t("greennet", "Can't update value rate for object. Because rating object id is invalid."));
		}
		
		$modelRatingObjectData->rating_value_id = $binRatingValueId;
		$modelRatingObjectData->value = $intRateValue;
		//$modelRatingObject->created = date("Y-m-d H:i:s");
		
		//Validate is invalid
		if (!$modelRatingObjectData->validate()) {
			throw new Exception(Yii::t("greennet", "Can't update value rate for object, because data attibutes is not validate."));
		}
			
		$updateRate = $modelRatingObjectData->save();

		if ($updateRate) {
			return $modelRatingObjectData;
		}
		
		$err_updateRate = $modelRatingObjectData->errors;
			
		if(empty($err_updateRate)){
			throw new Exception(Yii::t("greennet", "Can't update value rate for object. Because error by system."));
		} else {
			list($field, $_err) = each($err_updateRate);
			throw new Exception(sprintf(Yii::t("greennet", "Can't update value rate for object. Because errors is : %s"), $_err[0]));
		}
	}
	
	/**
	 * This method is used to get all list rating for object by rating value id
	 * @param unknown_type $binObjectId
	 * @param unknown_type $binRatingValueId
	 * @return multitype:
	 */
	public function getAllListRated($binObjectId, $binRatingValueId) {

		$modelRatingObject		= $this->_modelRatingObject;
		
		if (!isset($binObjectId) || !isset($binRatingValueId)) {
			return array();
		}

		$modelRatingObjectData = $modelRatingObject::model()->findAllByAttributes(array(
			'object_id'					=> $binObjectId,
			'rating_value_id'			=> $binRatingValueId
		));

		return (!empty($modelRatingObjectData)) ? $modelRatingObjectData : array();
	}
	
	/**
	 * This method is used to get all list rated for object  by rating id
	 * @param unknown_type $binObjectId
	 * @param unknown_type $binRatingsId
	 * @return multitype:|Ambigous <multitype:, mixed, CActiveRecord, NULL, multitype:unknown Ambigous <CActiveRecord, NULL> , multitype:unknown >
	 */
	public function getAllListRatedByRatingsId($binObjectId, $binRatingsId) {

		$modelRatingObject		= $this->_modelRatingObject;
		
		if (!isset($binObjectId) || !isset($binRatingsId)) {
			return array();
		}
	
		$modelRatingObjectData = $modelRatingObject::model()->findAllByAttributes(array(
			'object_id'					=> $binObjectId,
			'ratings_id'				=> $binRatingsId
		));
	
		return (!empty($modelRatingObjectData)) ? $modelRatingObjectData : array();
	}
	
	/**
	 * This method is used to get my rating for current user
	 * @param unknown_type $strRatingAlias
	 * @param unknown_type $binUserId
	 * @throws Exception
	 * @return Ambigous <multitype:, unknown>
	 */
	public function getMyRating($strRatingAlias, $binUserId, $limit = null, $offset = 0) {

		$modelRating			= $this->_modelRating;
		$modelRatingObject		= $this->_modelRatingObject;
		
		if(!isset($strRatingAlias) || !isset($binUserId)) {
			throw new Exception(Yii::t("greennet", "Can\'t get my rating. Because params is invalid")); 
		}

		$modelRatingData = $modelRating::model()->getRatingsByAlias($strRatingAlias);
		
		if (empty($modelRatingData)) {
			throw new Exception(Yii::t("greennet", "Can\'t get my rating. Because alias rating is empty"));
		}
		
		$cdbCriteria = new CDbCriteria();
		$cdbCriteria->condition = 'ratings_id = :binRatingsId and user_id = :binUserId and value <> 0';
		$cdbCriteria->params = array(
			':binRatingsId'			=> $modelRatingData->id,
			':binUserId'			=> $binUserId,
		);
		
		$cdbCriteria->order = 'created ASC';
		if(!empty($limit)) {
			$cdbCriteria->limit = $limit;
			$cdbCriteria->offset = $offset;
		}
		
		$myRating = $modelRatingObject::model()->findAll($cdbCriteria);
		
		return (!empty($myRating)) ? $myRating : array();
	}
	
	/**
	 * This method is used to count my rating for current user
	 * @param unknown_type $strRatingAlias
	 * @param unknown_type $binUserId
	 * @throws Exception
	 * @return unknown
	 */
	public function getCountMyRating($strRatingAlias, $binUserId) {

		$modelRating			= $this->_modelRating;
		$modelRatingObject		= $this->_modelRatingObject;
		
		if(!isset($strRatingAlias) || !isset($binUserId)) {
			throw new Exception(Yii::t("greennet", "Can\'t get count my rating. Because params is invalid"));
		}
		
		$modelRatingData = $modelRating::model()->getRatingsByAlias($strRatingAlias);
		
		if (empty($modelRatingData)) {
			throw new Exception(Yii::t("greennet", "Can\'t get my rating. Because alias rating is empty"));
		}
		
		$cdbCriteria = new CDbCriteria();
		$cdbCriteria->condition = 'ratings_id = :binRatingsId and user_id = :binUserId';
		$cdbCriteria->params = array(
			':binRatingsId'=> $modelRatingData->id,
			':binUserId'=> $binUserId
		);
		
		
		$countMyRating = $modelRatingObject::model()->count($cdbCriteria);
		
		return $countMyRating;
	}
}