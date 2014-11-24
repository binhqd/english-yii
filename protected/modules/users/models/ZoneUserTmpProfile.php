<?php
/**
 * GNUserProfile - This model is used to process the data on the table userbase_profiles
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 25-Jan-2013 4:21:04 PM
 * @modified 04-Feb-2013 9:37:01 AM
 * 
 * This is the model class for table "userbase_profiles".
 *
 * The followings are the available columns in table 'userbase_profiles':
 * @property string $id
 * @property string $user_id
 * @property integer $gender
 * @property string $location
 * @property string $phone
 * @property string $status_text
 *
 * The followings are the available model relations:
 * @property UserbaseUsers $user
 */
class ZoneUserTmpProfile extends GNActiveRecord
{
	const TYPE_GENDER_MALE = 1;
	const TYPE_GENDER_FEMALE = 0;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GNUserProfile the static model class
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
		return 'userbase_tmp_profiles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('gender', 'numerical', 'integerOnly'=>true),
			array('id, user_id, phone', 'length', 'max'=>16),
			array('phone','numerical'),
			array('phone','length','min'=>9),
			array('location', 'length', 'max'=>255),
			array('status_text', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, gender, location, phone, status_text', 'safe', 'on'=>'search')
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
			'user' => array(self::BELONGS_TO, 'GNTmpUser', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'gender' => 'Gender',
			'location' => 'Location',
			'phone' => 'Phone',
			'status_text' => 'Status Text',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('status_text',$this->status_text,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * This method is used to create user profile
	 *
	 * @param binUserId    Binary ID of user
	 */
	public function createProfile($binUserId, $arrInfo = array())
	{
		$model = self::model()->findByAttributes(array('user_id' => $binUserId));
		if (empty($model)) {
			$className = __CLASS__;
			$model = new $className;
			foreach ($arrInfo as $key => $value) {
				if (!empty($value)) $model->$key = $value;
			}
			$model->user_id = $binUserId;
			if ($model->save()) {
				return $model;
			} else {
				return null;
			}
		}
		return $model;
	}
}