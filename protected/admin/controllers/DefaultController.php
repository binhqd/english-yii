<?php
class TestController extends GNController {
	public function allowedActions() {
		return '*';
	}
	
	public function actionIndex() {
		exit('test hshsa');
	}
}