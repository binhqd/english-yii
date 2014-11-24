<?php
/**
 * LoginController - This controller is used to contain actions support for login
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 23-Jan-2013 4:59:44 PM
 * @modified 29-Jan-2013 11:09:18 AM
 */
class TestController extends ZoneController
{
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}

	/**
	 * This action is used to support user login
	 */
	public function actionMigrateUserProfile(){
		
		$userProfile = GNUserProfile::model()->findAll();
		foreach($userProfile as $key=>$profile){
			$profile->location = '51d54e6dfef44c8882ed75d6ac111364';
			$profile->save();
		}
		
	}
	public function actionIndex()
	{
		debug(Yii::app()->controllerMap);
	}

	/**
	 * This action is used to reindex displayname
	 */
	public function actionReindexDisplayname()
	{
		$users = ZoneUser::model()->findAll();
		foreach ($users as $user) {
			$user->displayname = ZoneUser::createDisplayName($user->firstname, $user->lastname);
			if ($user->save()) {
				echo "<span style='color:green'>". IDHelper::uuidFromBinary($user->id, true) ." - ".$user->username ." - ".$user->displayname.": OK</span><br/>";
			} else {
				echo "<span style='color:red'>". IDHelper::uuidFromBinary($user->id, true) ." - ".$user->username ." - ".$user->displayname.": ERROR</span><br/>";
			}
		}
		echo "Done!";
	}

}