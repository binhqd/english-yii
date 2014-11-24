<?php
class GNDBLogRoute extends CDbLogRoute {
	public $db;
	public $collection;
	
	public function init() {
		$connectionString = Yii::app()->mongodb->connectionString;
		$dbName = Yii::app()->mongodb->dbName;
// 		debug(array($connectionString, $dbName));

		MongoLog::setLevel(MongoLog::NONE);
		$m = new Mongo($connectionString); // connect
		$this->db = $m->selectDB($dbName);
		try {
			$this->collection = new MongoCollection($this->db, 'jl_error_logs');
			
		} catch (Exception $ex) {
			debug($ex->getMessage());
		}
	}
	/**
	 * (non-PHPdoc)
	 * @see CDbLogRoute::getDbConnection()
	 */
	public function getDbConnection() {
		return $this->db;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CDbLogRoute::processLogs()
	 */
	protected function processLogs($logs)
	{
		Yii::import('greennet.components.db.GNLog');
		foreach($logs as $log)
		{
			$pattern = "/(.*)?\nStack trace:\n(.*)?REQUEST_URI=(.*)/s";
			$match = preg_match($pattern, $log[0], $matches);
			if ($match) {
				$log[] = $matches[1];
				$log[] = $matches[2];
				$log[] = $matches[3];
				
				$data = array(
					"level"				=> $log[1],
					"category"			=> $log[2],
					"logtime"			=> time(),
					"message"			=> $log[4],
					"uri"				=> !empty($log[6]) ? $log[6] : Yii::app()->request->requestUri,
					"stack_trace"		=> $log[5],
					"referrer"			=> Yii::app()->request->urlReferrer,
					"user"				=> currentUser()->displayname,
					"ip"				=> $_SERVER['REMOTE_ADDR'],
					"user_agent"		=> $_SERVER['HTTP_USER_AGENT'],
					"request_method"	=> $_SERVER['REQUEST_METHOD'],
					"browser_name"		=> GNLog::model()->getBrowser($_SERVER['HTTP_USER_AGENT'])
				);
			} else {
				$lines = explode("\n", $log[0]);
				$trace = str_replace($lines[0], "", $log[0]);
				$data = array(
					"level"				=> $log[1],
					"category"			=> $log[2],
					"logtime"			=> time(),
					"message"			=> $lines[0],
					"uri"				=> Yii::app()->request->requestUri,
					"stack_trace"		=> $trace != "" ? $trace : $log[0],
					"referrer"			=> Yii::app()->request->urlReferrer,
					"user"				=> currentUser()->displayname,
					"ip"				=> $_SERVER['REMOTE_ADDR'],
					"user_agent"		=> $_SERVER['HTTP_USER_AGENT'],
					"request_method"	=> $_SERVER['REQUEST_METHOD'],
					"browser_name"		=> GNLog::model()->getBrowser($_SERVER['HTTP_USER_AGENT'])
				);
			}
			
			$this->collection->save($data);
		}
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
}