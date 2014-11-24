<?php

/**
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 1.0
 */
class APIUserRegisterAction extends GNAction
{

	/**
	 * This method is used to run action
	 * @author ngocnm
	 * @return void
	 */
	public function run()
	{
		ApiAccess::allow("POST");

		if (empty($_POST)) {
			throw new Exception(null, 400);
		}

		// validate form register
		Yii::import('api_app.modules.users.models.forms.APIRegisterForm');
		$model = new APIRegisterForm();
		$model->attributes = array(
			'firstname'	=> Yii::app()->request->getPost('firstname'),
			'lastname'	=> Yii::app()->request->getPost('lastname'),
			'email'		=> Yii::app()->request->getPost('email'),
			'password'	=> Yii::app()->request->getPost('password'),
			'birthday'	=> Yii::app()->request->getPost('birthday'),
			'location'	=> Yii::app()->request->getPost('location'),
			'gender'	=> Yii::app()->request->getPost('gender'),
		);
		if (!$model->validate()) { 
			$errors = array_shift(array_values($model->errors));
			if (isset($errors[0])) {
				throw new Exception($errors[0], 400);
			}
		}

		$modelGNTmpUser = new GNTmpUser;
		if (!$modelGNTmpUser->createUser($model->attributes)) {
			throw new Exception(Yii::t("Youlook", 'Cannot create user!'), 500);
		}

		// create gn user
		$user = new ZoneUser();
		$user->createUser($modelGNTmpUser->attributes);
		$location = Yii::app()->request->getPost('location');
		$modelProfile = new GNUserProfile;
		if ($location) {
			try {
				if (preg_match('/^[a-z0-9]{32}$/i', $location)) {
					$node = ZoneInstanceRender::get($location);
					$location = $node->node->name;
				}
			} catch (Exception $e) {
				$location = '';
			}
		}
		$arrInfo = array(
			'location'	=> $location,
			'birth'		=> Yii::app()->request->getPost('birthday'),
		);
		if (!$modelProfile->createProfile($user->id, $arrInfo)) {
			throw new Exception(Yii::t("Youlook", 'Cannot create profile'), 500);
		}

		// Assign Permissions
		Rights::assign(Yii::app()->params['roles']['AWAITING'], $user->id);

		// hack the old code
		Yii::import("application.modules.users.events.UpdateUserNodeHandler");
		UpdateUserNodeHandler::UpdateUserNode($user);
		$user->forceLogin();
		$userInfo = ZoneUser::model()->getUserInfo($user->id);
		$userInfo = ZoneApiResourceFormat::formatData('user', $user->toArray(true));
		$userInfo['stats'] = $user->stats;

		// create new access-token
		$accessToken = ApiAccess::generate();
		$out = array(
			'user'			=> $userInfo,
			'access_token'	=> $accessToken,
		);
		Yii::app()->response->send(200, $out, "Register successful");
	}

}