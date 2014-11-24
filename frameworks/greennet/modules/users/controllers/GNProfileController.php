<?php
/**
 * ProfileController - This controller is used to contain actions support for profile
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 23-Jan-2013 5:01:31 PM
 * @modified 29-Jan-2013 11:09:18 AM
 *
 */
Yii::import('application.modules.users.models.GNActivationCode');
class GNProfileController extends GNController
{
	/**
	 * This method is used to allow actions
	 */
	public function allowedActions()
	{
		return '';
	}

	public function filters()
	{
		return array(
			array(
				// Validate code if code is invalid or expired
				'greennet.modules.validation_codes.filters.ValidationCodeFilter + confirm_change_email, do_change_email'
			)
		);
	}

	public function actions(){
		return array(
			'index'	=> array(
				'class'			=> 'greennet.modules.users.actions.profile.GNProfileHomeAction'
				//'viewFile'	=> 'login'
			),
			'edit'	=> array(
				'class'			=> 'greennet.modules.users.actions.profile.GNProfileEditAction',
				'onUpdated'		=> array(
					//array("application.modules.users.events.UpdateUserNodeHandler", "UpdateUserNode"),
					//array("application.modules.users.events.UpdateUserNodeHandler", "TestMore")
				),
				'uploader'		=> array(
					'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
					'uploadPath'	=> 'upload/user-photos/' . currentUser()->hexID,
					'storageEngines'	=> array(
						'mongo'	=> array(
							'class'			=> 'greennet.components.GNUploader.components.engines.mongo.GNGridFSEngine',
// 							'serverInfo'	=> array(
// 								'server'	=> '192.168.1.110',
// 								'port'		=> 27019,
// 								'dbname'	=> 'myzonedev'
// 							)
						)
					)
				)
				//'viewFile'	=> 'login'
			),
			'change_password'	=> array(
				'class'			=> 'greennet.modules.users.actions.profile.GNChangePasswordAction'
				//'viewFile'	=> 'login'
			),
			'change_email'	=> array(
				'class'			=> 'greennet.modules.users.actions.profile.GNChangeEmailAction'
				//'viewFile'	=> 'login'
			),
			'confirm_change_email'	=> array(
				'class'			=> 'greennet.modules.users.actions.profile.GNConfirmChangeEmailAction'
				//'viewFile'	=> 'login'
			),
			'do_change_email'	=> array(
				'class'			=> 'greennet.modules.users.actions.profile.GNDoChangeEmailAction'
				//'viewFile'	=> 'login'
			)
		);
	}
}