<?php


class DebugController extends GNController
{
	//public $layout = '//layouts/backend';
	public $layout = '//layouts/debug';
	
	public function allowedActions() {
		return '*';
	}
	
	public function actionIndex() {
		$watches = file(Yii::app()->runtimePath . "/watches.txt");
		$watches = array_map("base64_decode", $watches);
		$watches = array_map("unserialize", $watches);
		$this->render('index', array(
			'watches'	=> $watches
		));
	}
	
	public function actionClean() {
		file_put_contents(Yii::app()->runtimePath . "/watches.txt", "");
		header("Location: /debug");
	}
}