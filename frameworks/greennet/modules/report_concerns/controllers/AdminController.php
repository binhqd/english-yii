<?php
Yii::import('greennet.modules.report_concerns.models.*');
class AdminController extends GNController {
	public static $baseViewPathAlias = 'greennet.modules.report_concerns.views.admin';
	public function allowedActions() {
		return '*';
	}
	
	private static function getView($viewName) {
		return self::$baseViewPathAlias . ".{$viewName}";
	}
	
	public function actionIndex() {
		$records = GNReportConcern::model()->groupReportsByObjectId();
		
		// mining report
		for ($i = 0; $i < count($records); $i++) {
			$item = &$records[$i];
			switch ($item['object_type']) {
				case 'image':
					Yii::import('application.modules.resources.models.ZoneResourceImage');
					$photo = ZoneResourceImage::model()->get(IDHelper::uuidFromBinary($item['object_id'], true));
					$item['related_info'] = $photo['photo'];
					break;
				case 'article':
					Yii::import('application.modules.articles.models.ZoneArticle');
					$article = ZoneArticle::model()->get(IDHelper::uuidFromBinary($item['object_id']));
					$item['related_info'] = $article;
					break;
				case 'video':
					Yii::import('application.modules.resources.models.ZoneResourceVideo');
					$video = ZoneResourceVideo::model()->get(IDHelper::uuidFromBinary($item['object_id']));
					$item['related_info'] = $video['video'];
					break;
			}
			
			$item['messages'] = GNReportConcern::model()->getReportMessages($item['object_id']);
			
		}
		$this->render(self::getView('index'), compact('records'));
	}
	
	public function actionArchived() {
		$records = GNReportConcern::model()->groupReportsByObjectId(1);
		
		// mining report
		for ($i = 0; $i < count($records); $i++) {
			$item = &$records[$i];
			switch ($item['object_type']) {
				case 'image':
					Yii::import('application.modules.resources.models.ZoneResourceImage');
					$photo = ZoneResourceImage::model()->get(IDHelper::uuidFromBinary($item['object_id'], true));
					$item['related_info'] = $photo['photo'];
					break;
				case 'article':
					Yii::import('application.modules.articles.models.ZoneArticle');
					$article = ZoneArticle::model()->get(IDHelper::uuidFromBinary($item['object_id']));
					$item['related_info'] = $article;
					break;
				case 'video':
					Yii::import('application.modules.resources.models.ZoneResourceVideo');
					$video = ZoneResourceVideo::model()->get(IDHelper::uuidFromBinary($item['object_id']));
					$item['related_info'] = $video['video'];
					break;
			}
		}
		
		$this->render(self::getView('archived'), compact('records'));
	}
	
	public function actionArchive() {
		$object_id = Yii::app()->request->getParam('object_id');
		
		$report = GNReportConcern::model()->find('object_id=:object_id', array(
			':object_id'	=> IDHelper::uuidToBinary($object_id)
		));
		
		$report->is_archived = 1;
		$report->save();
		
		$this->redirect('/reports');
	}
	
	public function actionRestore() {
		$object_id = Yii::app()->request->getParam('object_id');
	
		$report = GNReportConcern::model()->find('object_id=:object_id', array(
			':object_id'	=> IDHelper::uuidToBinary($object_id)
		));
		
		$report->is_archived = 0;
		$report->save();
	
		$this->redirect('/reports/archived');
	}
	
	public function actionMarkSpam() {
		$object_id = Yii::app()->request->getParam('object_id');
		$object_type = Yii::app()->request->getParam('object_type');
		
		dump("This method will be developed soon. Please go back");
	}
	
	public function actionSend(){
		
		$object_id = Yii::app()->request->getParam('object_id');
		$object_type = Yii::app()->request->getParam('object_type');
		$message = Yii::app()->request->getParam('message');
		$status = Yii::app()->request->getParam('status');
		
		$report = GNReportConcern::model()->find('object_id=:object_id', array(
			':object_id'	=> IDHelper::uuidToBinary($object_id)
		));
		
		$report->is_archived = 1;
		
		$item['related_info'] = array();
		$owner = array();
		
		switch ($report->object_type) {
			case 'image':
				Yii::import('application.modules.resources.models.ZoneResourceImage');
				$photo = ZoneResourceImage::model()->get(IDHelper::uuidFromBinary($report['object_id'], true));
				if($status=='delete'){
					$model = ZoneResourceImage::model()->findByPk($report['object_id']);
					$model->data_status = ZoneResourceImage::DATA_STATUS_DELETED;
					$model->save();
					$report->is_archived = 0;
				}
				
				$item['related_info'] = $photo['photo'];
				$owner = array(
					'username'		=> $item['related_info']['poster']['username'],
					'displayname'	=> $item['related_info']['poster']['displayname'],
					'email'			=> $item['related_info']['poster']['email']
				);
				break;
			case 'article':
				Yii::import('application.modules.articles.models.ZoneArticle');
				$article = ZoneArticle::model()->get(IDHelper::uuidFromBinary($report['object_id']));
				
				if($status=='delete'){
					$model = ZoneArticle::model()->findByPk($report['object_id']);
					$model->data_status = ZoneResourceImage::DATA_STATUS_DELETED;
					$model->save();
					$report->is_archived = 0;
				}
				$item['related_info'] = $article;
				$owner = array(
					'username'		=> $item['related_info']['author']['username'],
					'displayname'	=> $item['related_info']['author']['displayname'],
					'email'			=> $item['related_info']['author']['email']
				);
				break;
			case 'video':
				Yii::import('application.modules.resources.models.ZoneResourceVideo');
				$video = ZoneResourceVideo::model()->get(IDHelper::uuidFromBinary($report['object_id']));
				
				if($status=='delete'){
					$model = ZoneResourceVideo::model()->findByPk($report['object_id']);
					$model->data_status = ZoneResourceVideo::DATA_STATUS_DELETED;
					$model->save();
					$report->is_archived = 0;
				}
				
				$item['related_info'] = $video['video'];
				$owner = array(
					'username'		=> $item['related_info']['poster']['username'],
					'displayname'	=> $item['related_info']['poster']['displayname'],
					'email'			=> $item['related_info']['poster']['email']
				);
				break;
		}
		if(!empty($item['related_info'])){
			
			if($report->save()){
				$reports = GNReportConcern::model()->findAll('object_id=:object_id', array(
					':object_id'	=> IDHelper::uuidToBinary($object_id)
				));
				
				foreach($reports as $report){
					$report->is_archived = 1;
					$report->save();
				}
				
				$modelActivities = ZoneActivity::model()->findAllByAttributes(array('object_id' => $report['object_id']));
				if(!empty($modelActivities)){
					foreach($modelActivities as $modelActivity){
						$modelActivity->data_status = ZoneActivity::DATA_STATUS_DELETED;
						$modelActivity->save();
					}
				}
				
				ajaxOut(array(
					'error'		=> false,
					'message'	=> 'This object has been saved'
				), false);
				
				
				
				$subject	= 'Warning about reporting with inappropriate content';
				$sendTo		= 'hatn.dn@gmail.com';
				
				$data = array(
					'email' => $sendTo,
					'user'	=> $owner['displayname'],
					'subject' => $subject,
					'message' => $message,
					'object_type'=> $object_type,
					'item'		=> $item['related_info']
				);
				Yii::app()->mail->sendMailWithTemplate($sendTo, $subject, 'sendMailToOwner', $data);
				
			} else {
				ajaxOut(array(
					'error'		=> false,
					'message'	=> 'This object has not been saved'
				));
			}
		} else {
			ajaxOut(array(
				'error'		=> false,
				'message'	=> 'This object has not been saved'
			));
		}
	}
}