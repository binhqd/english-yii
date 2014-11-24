<?php
Yii::import('api_base.modules.english.models.APIWord');
class WordsController extends GNStandardApiController {
	
	/**
	 * Description of this method
	 * @return void
	 */
	public function allowedActions()
	{
		return '*';
	}
	
	public function init() {
		$this->model = 'APIWord';
		parent::init();
	}
	
}