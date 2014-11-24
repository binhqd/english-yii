<?php
/**
 * @author minhnc
 * @version 1.0
 * @created 03-May-2012 5:25:30 PM
 * @modified 03-May-2012 5:55:13 PM
 */
Yii::import('application.modules.validation_codes.models.*');
class TestController extends GNController
{
	public function allowedActions() {
		return '*';
	}
	
	public function actionCreateCode() {
		$expiredTime = Yii::app()->getModule('validation_codes')->intExpiryDate_Invite_Friend;
		$type = ValidationCode::TYPE_INVITE_FRIEND;
		$code = ValidationCode::createCode($expiredTime, $type);
		
		dump($code);
	}
	
	public function actionDeleteCode() {
		$code = "20888ff49ce07e51c5a4f1c99f4b6a3947d386b8";
		$deleteCode = ValidationCode::deleteCode($code);
	
		dump($deleteCode);
	}
	
	public function actionIsCodeValidate() {
		$code = "4675b108d49417b13624029b07ae795e5e972113";
		$isCodeValidate = ValidationCode::isCodeValidate($code);
	
		dump($isCodeValidate);
	}
	
	public function actionCleanExpiredCodes() {
		$cleanExpiredCode = ValidationCode::cleanExpiredCodes();
		
		dump($cleanExpiredCode);
	}
}
	