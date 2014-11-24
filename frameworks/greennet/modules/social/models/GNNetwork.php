<?php

/**
 * This is the model class for table "friend_invitations".
 * 
 * @author binhqd
 * @date 2013-02-25
 * @version 1.0
 * 
 */
class GNNetwork extends GNActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
		 * @param $className
	 * @return GNUser the static model class
	 */
	public $email;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'networks';
	}
	/**
	 * (non-PHPdoc)
	 * @see yii/framework/base/CModel::behaviors()
	 */
	public function behaviors()
	{
		return array(
			/*array('email', 'email', 'message' => UserModule::t("Invalid user email.")),
			array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),
			*/
		);
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
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//'senderInfo' => array(self::BELONGS_TO, 'GNUser', 'sender_id'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			
		);
	}
	
}
