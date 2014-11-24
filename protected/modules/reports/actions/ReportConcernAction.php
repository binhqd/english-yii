<?php
Yii::import('application.modules.reports.models.*');
class ReportConcernAction extends GNAction {
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
			$model = new ZoneReportConcern('concern');
		} else {
			$model = new ZoneReportConcern();
		}
		
		$model->object_id	= IDHelper::uuidToBinary($objectID);
		$model->object_type	= $objectType;
		$model->content		= $content;
		
		try{
			$model->validate();
			
			$isReport	= ZoneReportConcern::model()->isReport($objectID, $objectType);
			
			if($isReport){
				ajaxOut(array(
					'error'		=> true,
					'message'	=> 'This ' . $objectType . ' has been reported.'
				));
			}
			
			$browser = ZoneReportConcern::model()->getBrowser();
			
			$model->IP = ZoneReportConcern::model()->getClientIP();
			$model->user_agent = $browser['userAgent'];
			$model->user_id = currentUser()->id;
			
			if($model->save()){
				ajaxOut(array(
					'error'		=> false,
					'message'	=> Yii::t("Youlook", 'This object has been saved successful.'),
				));
			} else {
				ajaxOut(array(
					'error'		=> true,
					'message'	=> Yii::t("Youlook", 'This object has not been saved successful.'),
				));
			}
		} catch(Exception $e){
			ajaxOut(array(
				'error'		=> true,
				'message'	=> Yii::t("Youlook", $e->getMessage())
			));
		}
	}
	
}