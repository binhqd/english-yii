<?php
/**
 * This action is used to display profile home of user
 */
class GNProfileHomeAction extends CAction {
	public $viewFile = 'greennet.modules.users.views.profile.home';
	
	public function run($name = null)
	{
		$controller = $this->controller;
		if (empty($name)) {
			$user = currentUser();
			$profile = $user->profile;
			
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'		=> false,
					'user'		=> $user->toArray()
				));
			} else {
				$controller->render($this->viewFile, array('user' => $user));
			}
		}
	}
} 