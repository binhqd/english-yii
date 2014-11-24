<?php
Yii::import('application.modules.reports.models.*');
class StatReportConcernAction extends GNAction {
	/**
	 * The main action that handles the list photo request.
	 */
	public function run() {
		
		$controller = $this->controller;
		
		$objectID	= Yii::app()->request->getParam('id',null);
		$objectType	= Yii::app()->request->getParam('type',null);
		
		$isReport	= ZoneReportConcern::model()->isReport($objectID, $objectType);
		if($isReport){
			ajaxOut(array(
				'error'		=> true,
				'message'	=> 'This ' . $objectType . ' has been reported.'
			));
		} else {
			ajaxOut(array(
				'error'		=> false,
				'message'	=> 'This ' . $objectType . ' has not been reported.'
			));
		}
	}
	
}