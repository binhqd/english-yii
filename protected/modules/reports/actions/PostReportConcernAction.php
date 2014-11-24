<?php
Yii::import('application.modules.reports.models.*');
class PostReportConcernAction extends GNAction {
	/**
	 * The main action that handles the list photo request.
	 */
	public function run() {
		
		$controller = $this->controller;
		
		$objectID	= Yii::app()->request->getParam('id',null);
		$objectType	= Yii::app()->request->getParam('type',null);
		$content	= Yii::app()->request->getParam('message',null);
		$verifyCode	= Yii::app()->request->getParam('verifyCode',null);
		
		if(currentUser()->isGuest){
			$model = new ZoneReportConcern();
		} else {
			$model = new ZoneReportConcern();
		}
		
		$model->object_id	= IDHelper::uuidToBinary($objectID);
		$model->object_type	= $objectType;
		$model->content		= $content;
		$model->verifyCode	= $verifyCode;
		
		$isReport	= ZoneReportConcern::model()->isReport($objectID, $objectType);
		if($isReport){
			ajaxOut(array(
				'error'		=> true,
				'message'	=> Yii::t("Youlook", 'This {object_type} has been reported.', array('{object_type}' => $objectType))
			));
		}
		
		try{
			$model->validate();
			
			$browser = ZoneReportConcern::model()->getBrowser();
			
			$model->IP = ZoneReportConcern::model()->getClientIP();
			$model->user_agent = $browser['userAgent'];
			$model->user_id = currentUser()->id;
			$model->created = strtotime(date('Y-m-d H:m:s'));
			
			if($model->save()){
				ajaxOut(array(
					'error'		=> false,
					'message'	=> Yii::t("Youlook", 'This object has been reported successful.'),
				));
			} else {
				ajaxOut(array(
					'error'		=> true,
					'message'	=> Yii::t("Youlook", 'This object has not been reported.'),
				));
			}
		} catch(Exception $e){
			ajaxOut(array(
				'error'		=> true,
				'message'	=> $e->getMessage()
			));
		}
	}
	
}