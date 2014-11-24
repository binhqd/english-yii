<?php

class GNReportConcern extends GNActiveRecord {
	public $verifyCode;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ZoneFeedback the static model class
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
		return 'zone_report_concerns';
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t("Youlook", 'ID'),
			'content' => Yii::t("Youlook", 'Content'),
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

		$criteria->compare('id',$this->id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('object_id',$this->object_id,true);
		$criteria->compare('user_id',$this->user_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * This method is used to get records that group by object ID
	 * @param unknown_type $binObjectID
	 */
	public function groupReportsByObjectId($intIsArchived = 0) {
		if ($intIsArchived != 0 && $intIsArchived != 1)
			$intIsArchived = 0;
		
		$results = Yii::app()->db->createCommand()
		->select('object_id, count(object_id) as total, report.object_type, report.object_type')
		->from($this->tableName() . ' as report')
		->where("report.is_archived={$intIsArchived}")
		->order('total DESC')
		->group('object_id')
		->queryAll();
		
		return ($results);
	}
	
	public function getReportMessages($binReportID) {
		$command = Yii::app()->db->createCommand()
		->select('user_id, content, created')
		->from($this->tableName() . ' as report')
		->where("report.object_id=:object_id")
		->order('created DESC');
		
		$command->bindParam(":object_id", $binReportID);
		$results = $command->queryAll();
		
		return ($results);
	}
	/**
	/* This method is used to get all feedback
	/* @author: Chu Tieu
	*/
	public function getAll($mediaType=null, $limit=50){
		$criteria = new CDbCriteria();
		if(isset($mediaType)){
			$criteria->condition = 'media_type=:mediaType and is_archived = 0';
			$criteria->params = array(
				':mediaType' => $mediaType
			);
		}
		$criteria->order = 'created desc';
		$pages = new CPagination(count(self::model()->findAll($criteria)));
		
		$pages->pageSize=$limit;
		$pages->applyLimit($criteria);
		
		return array(
			'records'	=> self::model()->findAll($criteria),
			'pages'		=> $pages
		);
	}
	public function getBrowser(){
		$u_agent = $_SERVER['HTTP_USER_AGENT']; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
		
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE"; 
		} 
		elseif(preg_match('/Firefox/i',$u_agent)) 
		{ 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		} 
		elseif(preg_match('/Chrome/i',$u_agent)) 
		{ 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		} 
		elseif(preg_match('/Safari/i',$u_agent)) 
		{ 
			$bname = 'Apple Safari'; 
			$ub = "Safari"; 
		} 
		elseif(preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		} 
		elseif(preg_match('/Netscape/i',$u_agent)) 
		{ 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		} 
		
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
		
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
		
		return array(
			'userAgent'	=> $u_agent,
			'name'		=> $bname,
			'version'	=> $version,
			'platform'	=> $platform,
			'pattern'	=> $pattern
		);
	}
	public function get($objectID=null, $ip=null ){
		
	}
	
	public function getClientIP() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	// Check is report
	public function isReport($objectID=null, $objectType=null ){
		if(empty($objectID) || empty($objectType)) return false;
		
		$criteria = new CDbCriteria();
		
		$criteria->condition = 'IP=:IP and user_id=:userID and object_id=:objectID and object_type=:objectType';
		$criteria->params = array(
			':IP'			=> $_SERVER['REMOTE_ADDR'],
			':userID'		=> currentUser()->id,
			':objectID'		=> IDHelper::uuidToBinary($objectID),
			':objectType'	=> $objectType
		);
		return self::model()->count($criteria);
	}
}