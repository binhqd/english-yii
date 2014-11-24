<?php

Yii::import('application.modules.social.models.*');
class ManageNetworksController extends GNController
{
	public $layout = '//layouts/homepage';

	public function allowedActions() {
		return '*';
	}
	
	
}
