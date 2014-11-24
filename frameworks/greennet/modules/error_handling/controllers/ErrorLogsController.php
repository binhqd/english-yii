<?php
Yii::import('greennet.modules.error_handling.models.*');
class ErrorLogsController extends GNController {
	public static $baseViewPathAlias = 'greennet.modules.error_handling.views';
	public function allowedActions() {
		return '*';
	}
	
	private static function getView($viewName) {
		return self::$baseViewPathAlias . ".{$viewName}";
	}
	
	public function actionIndex() {
		$ret = GNMongoErrorLog::groupErrorsByFileAndLine();
		
		$errors = array();
		if (!empty($ret['retval'])) {
			$errors = $ret['retval'];
		}
// 		dump($errors);
		$this->render(self::getView('index'), compact('errors'));
	}
	
	public function actionDeleteErrors() {
		$file = Yii::app()->request->getParam('file');
		$line = Yii::app()->request->getParam('line');
		GNMongoErrorLog::removeErrorsByFileAndLine($file, $line);
		
		$this->redirect('/errors');
	}
	
	public function actionDeleteErrorsByGroup() {
		$file = Yii::app()->request->getParam('file');
		$line = Yii::app()->request->getParam('line');
		$uri = Yii::app()->request->getParam('uri');
		$method = Yii::app()->request->getParam('method');
		GNMongoErrorLog::removeErrorsByGroup($file, $line, $uri, $method);
	
		$this->redirect("/errors/listErrors?file=".urlencode($file)."&line={$line}");
	}
	
	public function actionListErrors() {
		$file = Yii::app()->request->getParam('file');
		$line = Yii::app()->request->getParam('line');
		
		$ret = GNMongoErrorLog::listErrorsByFileAndLine($file, $line);
		
		$errors = array();
		if (!empty($ret['retval'])) {
			$errors = $ret['retval'];
		}
		//dump($errors);
		$this->render(self::getView('list-errors'), compact('errors', 'file', 'line'));
	}
}