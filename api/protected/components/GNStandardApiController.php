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
			
			$total = $model->count("{$subDefinition['key']}=:foreign_key", array(
				":foreign_key"	=> IDHelper::uuidToBinary($parentId)
			));
			$pages = new CPagination($total);
			$pages->pageSize = ApiAccess::$limit;
			
			$records = Yii::app()->db->createCommand()
			->select($this->model->getFields(ApiAccess::getFields()))
			->from($model->tableName())
			->where("{$subDefinition['key']}=:foreign_key", array(
				":foreign_key"	=> IDHelper::uuidToBinary($parentId)
			))
			->limit($pages->limit)
			->offset($pages->offset)
			->queryAll();
			
			$arr = array();
			foreach ($records as $record) {
				$arr[] = $model->parse($record);
			}
			$records = $arr;
			
		} catch (Exception $ex) {
			if ($ex->getCode() == 42) {
				Yii::app()->response->send(400, array(), Yii::t('apicore', "Invalid request. Unknow column."));
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
				
				break;
			case 'PUT':
				
				break;
			case 'DELETE':
				
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
			$out = array(
				
			);
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
	
	public function getList() {
		$total = $this->model->count();
		$pages = new CPagination($total);
		$pages->pageSize = ApiAccess::$limit;
		
		if (method_exists($this->model, 'getList')) {
			$records = $this->model->getList($pages);
		} else {
			try {
				$records = Yii::app()->db->createCommand()
			    ->select($this->model->getFields(ApiAccess::getFields()))
			    ->from($this->model->tableName())
			    ->limit($pages->limit)
			    ->offset($pages->offset)
			    ->queryAll();
				
			} catch (Exception $ex) {
				if ($ex->getCode() == 42) {
					Yii::app()->response->send(400, array(), Yii::t('apicore', "Invalid request. Unknow column."));
				}
			}
		}
		
		$arr = array();
		foreach ($records as $record) {
			$arr[] = $this->model->parse($record);
		}
		$records = $arr;
		
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