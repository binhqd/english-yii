<?php

/**
 * This is the model class for table "i18n".
 *
 * The followings are the available columns in table 'i18n':
 * @property string $id
 * @property string $table_name
 * @property string $object_id
 * @property string $field
 * @property string $value
 * @property string $locale
 */
class GNI18n extends GNActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GNI18n the static model class
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
		return 'i18n';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('table_name, object_id, field', 'required'),
			array('id, object_id', 'length', 'max'=>16),
			array('table_name, field', 'length', 'max'=>64),
			array('locale', 'length', 'max'=>2),
			array('value', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, table_name, object_id, field, value, locale', 'safe', 'on'=>'search'),
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
			'table_name' => 'Table Name',
			'object_id' => 'Object',
			'field' => 'Field',
			'value' => 'Value',
			'locale' => 'Locale',
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
		$criteria->compare('table_name',$this->table_name,true);
		$criteria->compare('object_id',$this->object_id,true);
		$criteria->compare('field',$this->field,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('locale',$this->locale,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * This method is used to save i18n
	 */
	public function saveI18n($table_name, $object_id, $field, $value, $locale)
	{
		$model = self::model()->findByAttributes(array(
			'table_name'	=> $table_name,
			'object_id'		=> $object_id,
			'field'			=> $field,
			'locale'		=> $locale,
		));
		if (empty($model)) {
			$className = __CLASS__;
			$model = new $className;
			$model->table_name = $table_name;
			$model->object_id = $object_id;
			$model->field = $field;
			$model->locale = $locale;
		}
		$model->value = $value;
		return $model->save();
	}

	/**
	 * This method is used to get fields by object table
	 */
	public function getI18n($table_name, $object_id, $locale)
	{
		return self::model()->findAllByAttributes(array(
			'table_name'	=> $table_name,
			'object_id'		=> $object_id,
			'locale'		=> $locale,
		));
	}

	/**
	 * This method is used to get fields by object table
	 */
	public function deleteI18n($table_name, $object_id, $locale)
	{
		return self::model()->deleteAllByAttributes(array(
			'table_name'	=> $table_name,
			'object_id'		=> $object_id,
			'locale'		=> $locale,
		));
	}
}