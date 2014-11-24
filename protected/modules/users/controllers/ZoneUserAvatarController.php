<?php
/**
 * ArticlesController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Apr 5, 2013 10:59:48 AM
 */
class ZoneUserAvatarController extends GNController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}
	
	public function filters()
	{
		return array(
			array(
				// Validate code if code is invalid or expired
				'greennet.modules.users.filters.ValidUserFilter + upload',
				'out'	=> array("files" =>
					array(
						array(
							"error"			=> true,
							"message"		=> "You need to login before continue"
						)
					)
				)
			),
			array(
				'greennet.modules.users.filters.ValidUserFilter + delete-image',
				'out'	=> array(
					"error"			=> true,
					"message"		=> "You need to login before continue"
				)
			)
		);
	}
	
	public function actions(){
		return array(
			'upload'			=> array(
				'class'			=> 'greennet.modules.gallery.actions.GNUploadGalleryItemAction',
				'model'			=> 'application.modules.users.models.ZoneUserAvatar',
				'fieldName'		=> 'image',
				'uploadPath'	=> 'upload/user-photos/' . currentUser()->hexID . '/'
			),
			'delete-image'	=> array(
				'class'			=> 'greennet.modules.gallery.actions.GNDeleteGalleryItemAction',
				'model'			=> 'application.modules.users.models.ZoneUserAvatar',
				'uploadPath'	=> 'upload/user-photos/' . currentUser()->hexID . '/'
			)
		);
	}
}