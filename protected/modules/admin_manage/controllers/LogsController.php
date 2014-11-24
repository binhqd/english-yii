<?php
class LogsController extends JLController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';
	public $defaultAction = "index";
	//List data type of jl attributes

	public function allowedActions() {
		return '*';
	}
	
	public function init() {
		Yii::import('application.components.db.JLLog');
		parent::init();
// 		Yii::import('application.modules.feedback.models.JLFeedback');
// 		$this->outputDir = Yii::getPathOfAlias('feedback.data');
	}
	public function actionMassDelete() {
		if(!empty($_POST['cid'])){
			$ids = $_POST['cid'];
			$arrIds = array();
			foreach ($ids as $item) {
				$arrIds[] = new MongoId($item);
			}
			$criteria = array(
				'conditions'	=> array(
					'_id'	=> array("in" => $arrIds)
				),
			);
			
			$criteria = new EMongoCriteria($criteria);
			JLLog::model()->deleteAll($criteria);
		}
		$this->redirect('/admin_manage/logs');
		
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionIndex()
	{
		//$log = Yii::app()->log->routes[0];
		
		$log = JLLog::model();

// 		debug($log->routes[0]);
		$pages			=	new CPagination();
		$pages->pageSize=	30;
		
		$_criteria = array(
			'select'	=> array('id', 'message', 'category', 'uri', 'user', 'ip', 'logtime', 'referrer', 'request_method', 'user_agent', 'stack_trace'),
			'limit'		=> $pages->limit,
			'offset'	=> $pages->offset,
			'sort'		=> array(
				'logtime'	=> EMongoCriteria::SORT_DESC
			)
		);
		
		$criteria = new EMongoCriteria($_criteria);
		$pages->itemCount = $log->count();
		
		$pages->applyLimit($criteria);
		
		//$logs = $log->findAll($criteria);
		$logs = new EMongoDocumentDataProvider('JLLog', array(
			'criteria'	=> $_criteria,
			'pagination'	=> array(
				'pageSize'	=> $pages->limit,
			)
		));
		
		$this->render('index', array(
			'pages' => $pages,
			'logs'	=> $logs,
			'item_count'	=> $pages->pageSize,
			'model'			=> $log
		));
	}
	
	public function actionDelete() {
		$id = $_GET['id'];
		$log = JLLog::model()->findByPk($id);
		
		if (!empty($log)) {
			$log->delete();
			
			$this->redirect(Yii::app()->request->urlReferrer);
		} else {
			$this->redirect(Yii::app()->request->urlReferrer);
		}
	}
	
	public function actionRemoveGoogleBot() {
		$criteria = array(
			'conditions'	=> array(
				'browser_name'	=> array('==' => 'GoogleBot 2.1')
			)
		);
		$criteria = new EMongoCriteria($criteria);
		JLLog::model()->deleteAll($criteria);
		
		jsonOut(array(
			'error'		=> false,
			'message'	=> 'Remove GoogleBot successful'
		));
	}
	
	public function actionCategory() {
		$arrMap = array(
			'js'	=> 'JS Error',
			'404'	=> 'exception.CHttpException.404'
		);
		$c = $_GET['c'];
		
		$log = JLLog::model();
		
		// 		debug($log->routes[0]);
		$pages				= new CPagination();
		$pages->pageSize	= 50;
		
		$_criteria = array(
			'select'	=> array('id', 'message', 'category', 'uri', 'user', 'ip', 'logtime', 'referrer', 'request_method', 'user_agent'),
			'limit'		=> $pages->limit,
			'offset'	=> $pages->offset,
			'sort'		=> array(
				'logtime'	=> EMongoCriteria::SORT_DESC
			),
			'conditions'	=> array(
				'category'	=> array('==' => $arrMap[$c])
			)
		);
		$criteria = new EMongoCriteria($_criteria);
		$pages->itemCount = $log->count(array(
			'conditions'	=> array(
				'category'	=> array('==' => $arrMap[$c])
			)
		));
		
		$pages->applyLimit($criteria);
		
		//$logs = $log->findAll($criteria);
		$logs = new EMongoDocumentDataProvider('JLLog', array(
			'criteria'	=> $_criteria,
			'pagination'	=> array(
				'pageSize'	=> $pages->limit,
			)
		));
		
		$this->render('index', array(
			'pages' => $pages,
			'logs'	=> $logs,
			'item_count'	=> $pages->pageSize,
			'model'			=> $log
		));
	}
	
	public function actionUpdateBrowser() {
		Yii::import("application.extensions.browser.CBrowserComponent");
		$browserComponent = new CBrowserComponent();
		
		$page = 1;
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		}
		$limit = 60;
		$offset = ($page - 1) * $limit;
		$criteria = array(
			'conditions'	=> array(),
			'sort'	=> array(
				'logtime'	=> EMongoCriteria::SORT_DESC
			),
			'limit'			=> $limit,
			'offset'		=> $offset
		);
		
		$criteria = new EMongoCriteria($criteria);
		$logs = JLLog::model()->findAll($criteria);
		
		if (count($logs) == 0) {
			$this->redirect('/admin_manage/logs');
			Yii::app()->end();
		}
		
		$total = JLLog::model()->count();
		
		$cnt = 0;
		foreach ($logs as $item) {
			$browserComponent->setUserAgent($item->user_agent);
			$browser = $browserComponent->getBrowser() . " " . $browserComponent->getVersion();
			$item->browser_name = $browser;
			$item->update(array('browser_name'), true);
			$cnt++;
		}
		$nextPage = $page + 1;
		echo "<html>
		<head>
		<meta http-equiv=\"refresh\" content=\"3; URL=/admin_manage/logs/updateBrowser?page=$nextPage&time=".uniqid() . "\"/>
		</head>
		<body>
		<h1>Updating browser for error logs</h1>
		Updating {$offset} to ".($offset + $limit)." of total {$total} items
		</body>
		</html>";
		exit;
	}
}
