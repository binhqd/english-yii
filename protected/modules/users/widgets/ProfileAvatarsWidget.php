<?php
class ProfileAvatarsWidget extends GNWidget {
	/**
	 * 
	 */
	private $_user;
	/**
	 * This method is used to get user object for display
	 * @param ZoneUser $user
	 * @throws Exception Invalid user
	 */
	public function setUser($user) {
		if (empty($user) || $user->isGuest) {
			$msg = "Invalid user";
			Yii::log("{$msg} at " . __FILE__ . " (".__LINE__.")", CLogger::LEVEL_ERROR, "Widget Variable");
			throw new Exception($msg);
		}
		$this->_user = $user;
	}
	
	public function init() {
		GNAssetHelper::init(array(
			'image'		=> 'img',
			'css'		=> 'css',
			'script'	=> 'js',
		));
		
		
	}
	
	public function run () {
		Yii::import('application.modules.users.models.ZoneUserAvatar');
		$avatars = ZoneUserAvatar::model()->getAvatars($this->_user->hexID, 4);
		
		$this->render('profile-avatar', array(
			'user'		=> $this->_user,
			'avatars'	=> $avatars
		));
	}
}