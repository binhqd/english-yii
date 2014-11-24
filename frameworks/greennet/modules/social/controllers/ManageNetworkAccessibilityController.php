<?php

Yii::import('application.modules.social.models.*');
class ManageNetworkAccessibilityController extends GNController
{
	public $layout = '//layouts/modal';

	public function allowedActions() {
		return '*';
	}
	
	public function actionIndex() {
		if (empty($_POST)) {
			$accessibilityOptions = GNNetworkAccessibilityOption::model()->findAll();
			$networks = GNNetwork::model()->findAll();
			$this->render('index', compact('networks', 'accessibilityOptions'));
		} else {
			$name = $_POST['name'];
			$description = $_POST['description'];
			$network = $_POST['network'];
			
			$obj = new GNNetworkAccessibilityOption();
			$obj->name = $name;
			$obj->description = $description;
			$obj->network_id = IDHelper::uuidToBinary($network);
			
			if ($obj->save()) {
				$this->redirect('/social/manageNetworkAccessibility');
			} else {
				debug($obj->errors);
			}
		}
	}
}
