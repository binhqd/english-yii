<?php

/**
 * @ingroup components
 * Base class of a data record
 */
class GNLog extends EMongoDocument
{
	public static $logDB;
	public $browserComponent;
	public $collection;
	public $db;
	public $level;
	public $category;
	public $logtime;
	public $message;
	public $uri;
	public $stack_trace;
	public $referrer;
	public $user;
	public $ip;
	public $user_agent;
	public $request_method;
	public $id;
	public $browser_name;
	public $_c;
	
	public function init() {
		$connectionString = Yii::app()->mongodb->connectionString;
		$dbName = Yii::app()->mongodb->dbName;
// 		debug(array($connectionString, $dbName));
		
		$m = new Mongo($connectionString,  array("connect" => TRUE)); // connect
		$this->db = $m->selectDB($dbName);
		try {
			$this->collection = new MongoCollection($this->db, 'jl_error_logs');
			
		} catch (Exception $ex) {
			debug($ex->getMessage());
		}
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
// 	public function rules()
// 	{
// 		// NOTE: you should only define rules for those attributes that
// 		// will receive user inputs.
// 		return array(
// 			array('user_id', 'safe', 'on'=>'search'),
// 				//array('username, displayname, firstname, lastname', 'safe', 'on'=>'updateBasicInfo'),
// 		);
// 	}
	/**
	 * (non-PHPdoc)
	 * @see CDbLogRoute::getDbConnection()
	 */
	public function getDbConnection() {
		return $this->db;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getCollectionName()
	{
		return 'jl_error_logs';
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'error';
	}
	
	public function search() {
		$criteria = new CDbCriteria;
	
		$provider = new CActiveDataProvider(get_class($this), array(
			'criteria'		=> $criteria,
			'pagination'	=> array(
				'pageSize'	=> 30
			)
		));
	
		return $provider;
	}
	
	public function getBrowser($userAgent) {
		Yii::import("application.extensions.browser.CBrowserComponent");
		$this->browserComponent = new CBrowserComponent();
		
		$this->browserComponent->setUserAgent($userAgent);
		$browser = $this->browserComponent->getBrowser() . " " . $this->browserComponent->getVersion();
		return $browser;
	}
	
	public function renderMessage($data) {
		$mesage = array();
		$message[] = "<p><i>{$data->message}</i></p>";
		if ($data->stack_trace != "") {
			$message[] = "<div class='lnkviewstacktrace'><a href='#' class='showstack'>View stack trace</a></div>
			<div class='stacktrace'>".nl2br($data->stack_trace)."
			<br/>
			<a href='#' class='hidestack'>Hide stack trace</a>
			</div>
			";
		}
		$message[] = "<p>(".$this->getBrowser($data->user_agent).") {$data->request_method}: <a href=\"http://{$_SERVER["HTTP_HOST"]}{$data->uri}\">{$data->uri}</a></p>";
		$message[] = "<p>Referrer: <a href=\"{$data->referrer}\">{$data->referrer}</a></p>";
		
		return implode("\n", $message);
	}
}
