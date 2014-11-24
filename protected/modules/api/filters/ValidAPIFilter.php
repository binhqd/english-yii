<?php
class ValidAPIFilter extends CFilter {
	public $out;
	protected function preFilter($filterChain)
	{

		if(currentAPIUser()){
			return true;
		}else{
			return false;
		}
		// $controller = $filterChain->controller;
		
		// if (currentUser()->isGuest) {
		// 	$strMessage = "You need to login to continue";
		// 	$redirectUrl = GNRouter::createUrl('/login');
			
		// 	if (empty($this->out)) {
		// 		$this->out = array(
		// 			'error'		=> true,
		// 			'type'			=> 'error',
		// 			'message'		=> $strMessage,
		// 			'code'			=> 11001,
		// 			'urlRedirect'	=> $redirectUrl,
		// 		);
		// 	}
			
		// 	if ($controller->isJsonRequest) {
		// 		ajaxOut($this->out);
		// 	} else {
		// 		Yii::app()->jlbd->dialog->notify($this->out);
		// 		$controller->redirect($redirectUrl);
		// 	}
		// }
		
		// logic being applied before the action is executed
		return true; // false if the action should not be executed
	}
	
	protected function postFilter($filterChain)
	{
		// logic being applied after the action is executed
	}
}