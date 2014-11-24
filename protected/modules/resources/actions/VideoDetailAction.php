<?php
class VideoDetailAction extends GNAction {
	/**
	 * The main action that handles the list photo request.
	 */
	public function run() {
		$controller = $this->controller;
		
		// Check if photo id is not exist
		if (!isset($_GET['id'])) {
			$strMessage = "Invalid Object ID";
			$backUrl = GNRouter::createUrl('/');
		
			
			ajaxOut(array(
				'error'		=> true,
				'type'		=> 'error',
				'message'	=> $strMessage,
				'url'		=> $backUrl,
			));
		}
		
		Yii::import('application.modules.resources.models.ZoneResourceVideo');
		
		$id = $_GET['id'];
		
		$videoDetail = ZoneResourceVideo::model()->getVideoDetail($id);
		
		//If request by ajax
		ajaxOut($photo);
	}
}