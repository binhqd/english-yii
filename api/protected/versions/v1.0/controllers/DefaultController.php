<?php
/**
 * DefaultController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created May 15, 2014 9:15:17 AM
 */
//Yii::import('import something here');
class DefaultController extends GNApiController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}

	public function actions(){
		return array(
			
		);
	}
	
	public function actionIndex() {
		exit('version 1.0');
	}
}