<?php
/**
 * MigrateController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Jul 24, 2013 7:04:09 PM
 */
//Yii::import('import something here');
class MigrateController extends GNController {
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
		Yii::import('greennet.helpers.Sluggable');
		
		$users = ZoneUser::model()->findAll();
		
		foreach ($users as $user) {
			$username = preg_replace("/@/", '.', $user->email);
			$username = preg_replace("/(\.[a-z0-9]+)$/", '', $username);
			$username = Sluggable::slug($username);
			
			$user->username = $username;
			
			$user->save();
		}
		
		exit('Finished');
	}
}