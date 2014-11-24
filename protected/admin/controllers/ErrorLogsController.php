<?php
class ErrorLogsController extends GNController {
	public function allowedActions() {
		return '*';
	}
	
	public function actionIndex() {
		exit('test hshsa');
	}
}