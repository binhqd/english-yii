<?php
class MyTestController extends GNController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}
	
	public function actionIndex() {
		exit('they come here');
	}
}