<?php
/**
 * This action is used to edit profile of user
 */
class GNProfileEditAction extends GNAction {
	public $viewFile = 'greennet.modules.users.views.profile.edit';
	public $_onUpdated = array();
	private $_uploader;
	
	public function setUploader($config) {
		$this->_uploader = Yii::createComponent($config);
	}
	
	/**
	 *
	 * @param handlers $handlers
	 */
	public function setOnUpdated($handlers) {
		// TODO : May check if handler is an object
		if (is_array($handlers)) {
			for ($i = 0; $i < count($handlers); $i++) {
				$handler = $handlers[$i];

				if (!empty($handler[0])) {
					$className = Yii::import($handler[0]);
				}
				$handlers[$i][0] = $className;
			}
				
			$this->_onUpdated = $handlers;
		}
	}

	public function run() {
		$controller = $this->controller;

		$user = GNUser::model()->findByEmail(currentUser()->email);
		//$user = currentUser();
		$userProfile = $user->profile;

		if (!empty($_POST['GNUser']) || !empty($_POST['GNUserProfile'])) {
			//get data
			$user->attributes = $_POST['GNUser'];
			if (empty($userProfile)) { // User does not profile => create profile
				$userProfile = new GNUserProfile;
				$createProfile = $userProfile->createProfile($user->id);
				if (!$createProfile) throw new Exception(Yii::t("greennet", 'Cannot create user profile'));
			}
			
			$image = $_POST['GNUserProfile']['image'];
			unset($_POST['GNUserProfile']['image']);
			
			$userProfile->attributes = $_POST['GNUserProfile'];
			$userProfile->image = $image;
				
			// Check validate user and userProfile
			try {
				$valid = $user->validate() && $userProfile->validate();
			} catch (Exception $ex) {
				$url = GNRouter::createUrl('/profile/edit');
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> true,
						'message'	=> $ex->getMessage(),
						'url'		=> $url
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> true,
						'type' 		=> 'success',
						'autoHide'	=> true,
						'message'	=> $ex->getMessage(),
					));

					$controller->refresh();
				}
			}

			if ($valid) {
				$transaction = $user->dbConnection->beginTransaction();
				try {
					// set event handlers
					foreach ($this->_onUpdated as $event) {
						$user->onUpdated = $event;
					}
					
					if (!$user->save()) throw new Exception(Yii::t("greennet", 'Cannot save user information'));
						
					$oldImage = $userProfile->image;
					
					if (empty($this->_uploader)) {
						$config = array(
							'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
							'uploadPath'	=> "upload/user-photos/{$user->hexID}/"
						);
						
						$this->_uploader = Yii::createComponent($config);
					}
					
					$image = $this->_uploader->upload($userProfile, 'image');
					
					// If file has been saved
					if (!empty($image)) {
						$userProfile->image = $image['filename'];
					}
						
					if (!$userProfile->save()) throw new Exception(Yii::t("greennet", 'Cannot save user profile information'));
						
					// Delete old image if existed and new image has been uploaded
					if (!empty($image) && !empty($oldImage)) {
						//$this->_uploader->remove($oldImage, true);
					}
						
					// Commit transaction
					$transaction->commit();

					if ($controller->isJsonRequest) {
						ajaxOut(array(
							'error'	=> false,
							'message'	=> Yii::t("greennet", 'Your information has been saved'),
							// 'url'	=> GNRouter::createUrl('/profile'),
						));
					} else {
						Yii::app()->jlbd->dialog->notify(array(
							'type'		=> 'success',
							'autoHide'	=> true,
							'message'	=> Yii::t("greennet", 'Your profile has been saved sucessful'),
						));
					}
				} catch (Exception $ex) {
					// Rollback transaction
					$transaction->rollback();
					if ($controller->isJsonRequest) {
						ajaxOut(array(
						'error'	=> true,
						'message'	=> Yii::t("greennet", $ex->getMessage()),
						));
					} else {
						Yii::app()->jlbd->dialog->notify(array(
						'error'	=> true,
						'type'		=> 'error',
						'autoHide'	=> true,
						'message'	=> Yii::t("greennet", $ex->getMessage()),
						));
					}
				}
			} else {
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'	=> true,
						'message'		=> Yii::t("greennet", 'Your information is invalid'),
						'user_errors'	=> $user->errors,
						'profile_errors' => $userProfile->errors,
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'	=> true,
						'type'		=> 'error',
						'autoHide'	=> true,
						'message'	=> Yii::t("greennet", 'Your information is invalid'),
					));
				}
			}
		}

		if (empty($userProfile)) {
			// User does not profile => create profile
			$userProfile = GNUserProfile::model();
		}
		$controller->render($this->viewFile, array(
			'user'			=> $user,
			'userProfile'	=> $userProfile
		));
	}
}