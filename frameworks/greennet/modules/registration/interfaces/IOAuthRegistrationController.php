<?php
/**
 * @author BinhQD
 * @version 1.0
 * @created 23-Feb-2013 11:50:29 AM
 */
interface IOAuthRegistrationController
{
	public function actionIndex();
	
	public function actionConnect();
	
	public function actionRevoke();
	
	public function actionCheckConnection();
}