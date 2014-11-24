<?php
class ValidationCodeFilter extends CFilter {
	protected function preFilter($filterChain)
	{
		$controller = $filterChain->controller;
		
		// Display error if code is not valid
		if (!isset($_GET['code'])) {
			Yii::app()->jlbd->dialog->notify(array(
				'error'		=> true,
				'type'		=> 'error',
				'autoHide'	=> true,
				'message'	=> Yii::t("greennet", "Confirmation code is invalid or expired"),
			));
			$controller->redirect('/');
			Yii::app()->end();
		}
		$code = $_GET['code'];
		
		Yii::import('greennet.modules.validation_codes.models.ValidationCode');
		
		if (!ValidationCode::isCodeValidate($code)) {
			$strMessage = Yii::t("greennet", "Activation code is invalid or expired");
		
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'		=> true,
					'type'			=> 'error',
					'message'		=> $strMessage,
					'urlRedirect'	=> GNRouter::createUrl('/'),
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error'		=> true,
					'type'		=> 'error',
					'autoHide'	=> true,
					'message'	=> $strMessage,
				));
				$controller->redirect('/');
			}
		}
		
		// logic being applied before the action is executed
		return true; // false if the action should not be executed
	}
	
	protected function postFilter($filterChain)
	{
		// logic being applied after the action is executed
	}
}