<?php

/**
 * This class is used to get info user
 * @author ngocnm
 * @version 2.0
 */
class APIGetMeInfoAction extends GNAction
{

	/**
	 * This method is used to run action
	 * @author ngocnm
	 * @return void
	 */
	public function run()
	{
		ApiAccess::allow("GET");

		if (currentUser()->id !== -1) {
			$user = currentUser();
			$userInfo = ZoneApiResourceFormat::formatData('user', $user->toArray(true));
			$userInfo['stats'] = currentUser()->stats;
			Yii::app()->response->send(200, array(
				'user'	=> $userInfo,
			));
		} else {
			throw new Exception(null, 403);
		}
	}

}