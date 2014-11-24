<?php
class GNComment extends GNActiveRecord {

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
		return 'zone_comments';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
		);
	}

	public function behaviors()
	{
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
			'user' => array(self::BELONGS_TO, 'GNUser', 'user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
// 			'id' => UsersModule::t('ID'),
// 			'username' => UsersModule::t('Username'),
// 			'password' => UsersModule::t('Password'),
		);
	}
	
	/**
	 * This method is used to create a comment
	 * @param text $content
	 * @param binary $binObjectID
	 * @throws Exception User need to login before posting comment
	 * @return boolean
	 */
	public function createComment($content, $binObjectID) {
		if (currentUser()->isGuest) {
			throw new Exception(Yii::t("greennet", "Please login to be able to post new comment"));
		}
		
		// trim content
		$content = trim($content);
		$content = nl2br($content);
		
		
		$this->content = $content;
		$this->object_id = $binObjectID;
		$this->date = date('Y-m-d H:i:s');
		
		$this->user_id = currentUser()->id;
		
		if ($this->save()) {
			return $this;
		} else {
			$errors = $this->getErrors();
			
			$_errors = array();
			if (!empty($errors)) {
				$_errors = current($errors);
			}
			
			throw new Exception(sprintf(Yii::t("greennet", "Can't create new comment. Error: %s"), current($_errors)));
		}
	}
	/**
	 * This method is used to save comments
	 * @author Chu Tieu
	 * @param $model
	 * @param $content
	 * @param $objectId
	 * @return boolean
	 */
	public function saveComment($model, $content, $objectId){
		$content = trim($content);
		$content = nl2br(htmlentities($str_dataArea, ENT_QUOTES));
		if (!empty($model)) {
			$model->content = $content;
			$model->object_id = $objectId;
			$model->date = date('Y-m-d H:i:s');
			$model->user_id = currentUser()->id;
			if ($model->save()) {
				return true;
			} else return false;
		} else return false;
	}
	
	/**
	 * This method is used to count comments.
	 * @author Chu Tieu
	 * @param unknown_type $strObjectId
	 */
	public function countComments($strObjectId=null){
		if(!empty($strObjectId)){
			$binObjectId = IDHelper::uuidToBinary($strObjectId);
			
			return $this->countByAttributes(array('object_id' => $binObjectId));
		}
	}
	
	/**
	 * This method will return comment object as an array
	 * 
	 * @param unknown_type $getPoster
	 */
	public function toArray($getPoster = true) {
		$attr = $this->attributes;
		$attr['id'] = IDHelper::uuidFromBinary($attr['id'], true);
		$attr['object_id'] = IDHelper::uuidFromBinary($attr['object_id'], true);
		$attr['created'] = date(DATE_ISO8601, strtotime($attr['date']));
		
		if ($getPoster) {
			$attr['poster'] = GNUser::model()->get(IDHelper::uuidFromBinary($attr['user_id'], true));
			unset($attr['user_id']);
		} else {
			$attr['user_id'] = IDHelper::uuidFromBinary($attr['user_id'], true);
		}
		
		return $attr;
	}
	
	/**
	 * This method is used to remove all comments by object id
	 * @param binary $binObjectID
	 */
	public function removeAllByObjectID($binObjectID) {
		$criteria = new CDbCriteria();
		$criteria->condition = 'object_id=:object_id';
		$criteria->params = array(
			':object_id'	=> $binObjectID
		);
		
		$this->deleteAll($criteria);
	}
}