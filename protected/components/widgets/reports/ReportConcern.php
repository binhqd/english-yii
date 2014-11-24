<?php
/**
 * This class is used to call feedback
 * @author Chu Tieu
 */
Yii::import('application.modules.reports.models.ZoneReportConcern');
class ReportConcern extends GNWidget {
	public function init() {
// 		GNAssetHelper::init(array(
// 			'image'		=> 'img',
// 			'css'		=> 'css',
// 			'script'	=> 'js',
// 		));
// 		GNAssetHelper::setBase('application.components.widgets.feedback.assets');
		
// 		GNAssetHelper::cssFile('feedback');
		
// 		GNAssetHelper::scriptFile('jquery.magnific-popup.min', CClientScript::POS_HEAD);
// 		GNAssetHelper::scriptFile('common-feedback', CClientScript::POS_HEAD);
	}
	
	public function run() {
		if(currentUser()->isGuest){
			$model = new ZoneReportConcern();
		} else {
			$model = new ZoneReportConcern();
		}
		
		$this->render('reportconcern',compact('model'));
	}
	
}