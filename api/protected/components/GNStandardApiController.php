<?php
class GNStandardApiController extends GNApiController {
	protected $_model;
	protected $_hasMany;
	
	public function setModel($model) {
		$this->_model = new $model;
	}
	
	public function getModel() {
		return $this->_model;
	}
	
	public function setHasMany($components) {
		/*foreach ($components as $key => $component) {
			
		}*/
		$this->_hasMany = $components;
	}
	
	public function getHasMany() {
		return $this->_hasMany;
	}
	
	public function init() {
		parent::init();
	}
	
	public function actionListSubItems() {
		$parentId = Yii::app()->request->getParam('parentId');
		$sub = Yii::app()->request->getParam('sub');
		
		$subDefinition = $this->hasMany[$sub];
		try {
			$model = new $subDefinition['class'];
			
			$total = $model->getTotal("{$subDefinition['key']}=:foreign_key", array(
				":foreign_key"	=> IDHelper::uuidToBinary($parentId)
			));
			$pages = new CPagination($total);
			$pages->pageSize = ApiAccess::$limit;
			
			$records = $model->getList($pages, array(
				'conditions'	=> "{$subDefinition['key']}=:foreign_key",
				'params'		=> array(
					":foreign_key"	=> IDHelper::uuidToBinary($parentId)
				)
			));
			
			$arr = array();
			foreach ($records as $record) {
				$arr[] = $model->parse($record);
			}
			$records = $arr;
			
		} catch (Exception $ex) {
			if ($ex->getCode() == 42) {
				Yii::app()->response->send(400, array(), Yii::t('apicore', "Invalid request. Unknown column."));
			}
		}

		// response
		$out = array(
			"items" => $records,
			"pages" => array(
				'total' => (int)$pages->itemCount,
				'limit' => (int)$pages->limit,
				'page' => (int)$pages->currentPage + 1,
			),
		);
		
		Yii::app()->response->send(200, $out);
	}
	
	public function createAction($actionID)
	{
		parent::createAction($actionID);
		switch (ApiAccess::$method) {
			case 'GET':
// 				dump($actionID);
				if ((empty($actionID) || $actionID == $this->defaultAction)) {
					$this->getList();
				} else {
					
					if(method_exists($this,'action'.$actionID) && strcasecmp($actionID,'s')) {
						return parent::createAction($actionID);
					} else {
						$id = $actionID;
						// get detail
						$this->getDetail($id);
					}
				}
				break;
			case 'POST':
				$this->createNewRecord();
				break;
			case 'PUT':
				$this->updateRecord();
				break;
			case 'DELETE':
				$id = $actionID;
				$this->deleteRecord($id);
				break;
		}
		
	}
	public function actionTest() {
		
	}
	
	public function missingAction($actionID) {
		if (ApiAccess::$method == "GET") {
			if ((empty($actionID) || $actionID == $this->defaultAction)) {
				$this->getList();
			} else {
				$id = $actionID;
			}
		} else {
			// response
			$out = array();
			Yii::app()->response->send(404, $out, "Invalid Request");
		}
	}
	public function run($actionID) {
		parent::run($actionID);
// 		dump($actionID);
	}
	public function runAction($action) {
		parent::runAction($action);
	}
	
	protected function updateRecord() {
		// set attributes
		ApiAccess::allow("PUT");
		
		parse_str(file_get_contents("php://input"), $putVars);
		
		if (!isset($putVars)) {
			throw new Exception("Invalid record ID");
		}
		$putVars['id'] = IDHelper::uuidToBinary($putVars['id']);
		
		$model = $this->model->updateRecord($putVars);
		//response
		
		$record = $this->model->parse($model->attributes);
		$out = array(
			$this->id => $record
		);
		
		Yii::app()->response->send(200, $out);
	}
	
	protected function deleteRecord($id) {
		// set attributes
		ApiAccess::allow("DELETE");
		
		if ($this->model->deleteRecord(IDHelper::uuidToBinary($id))) {
			Yii::app()->response->send(200, array(), Yii::t("Record deleted"));
		} else {
			throw new Exception("Can't delete record");
		}
	}
	
	protected function createNewRecord() {
		// set attributes
		ApiAccess::allow("POST");
		
		if ($this->model->createNewRecord($_POST)) {
			//response
			
			$record = $this->model->parse($this->model->attributes);
			$out = array(
				$this->id => $record
			);
			Yii::app()->response->send(200, $out);
		} else {
			Yii::app()->response->send(401, $out, "Invalid request");
		}
	}
	
	public function getList() {
		$total = $this->model->total;
		$pages = new CPagination($total);
		$pages->pageSize = ApiAccess::$limit;
		
		try {		
			$records = $this->model->getList($pages);
			
			$arr = array();
			foreach ($records as $record) {
				$arr[] = $this->model->parse($record);
			}
			$records = $arr;
			
		} catch (Exception $ex) {
			if ($ex->getCode() == 42) {
				Yii::app()->response->send(400, array(), Yii::t('apicore', "Invalid request. Unknown column."));
			}
		}	
		// response
		$out = array(
			"items" => $records,
			"pages" => array(
				'total' => (int)$pages->itemCount,
				'limit' => (int)$pages->limit,
				'page' => (int)$pages->currentPage + 1,
			)
		);
		
		Yii::app()->response->send(200, $out);
	}
	
	public function getDetail($id) {
		try {
			$record = $this->model->findByKey(IDHelper::uuidToBinary($id));
		} catch (Exception $ex) {
			if ($ex->getCode() == 42) {
				Yii::app()->response->send(400, array(), Yii::t('apicore', "Invalid request. Unknow column."));
			}
		}
		
		if (empty($record)) {
			Yii::app()->response->send(404, array(), Yii::t('apicore', "Record not found."));
		} else {
			$out = array(
				$this->id	=> $record
			);
			Yii::app()->response->send(200, $out);
		}
		
	}
}