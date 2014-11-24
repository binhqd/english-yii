<?php

/**
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 2.0
 */
class APIGetUserInfoAction extends GNAction
{
	/**
	 * This method is used to run action
	 * @author ngocnm
	 * @param string $user_id Id user
	 * @return void
	 */
	public function run($user_id)
	{
		ApiAccess::allow("GET");
		$user = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($user_id));
		if ($user->id !== -1) {
			$userInfo = ZoneApiResourceFormat::formatData('user', $user->toArray(true));
			$userInfo['stats'] = $user->stats;
			unset($userInfo['email']);
			Yii::app()->response->send(200, array(
				'user'	=> $userInfo,
			));
		} else {
			throw new Exception(null, 400);
		}
	}

}