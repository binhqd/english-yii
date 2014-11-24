<?php
Yii::import('api_base.modules.english.models.APICollection');
Yii::import('api_base.modules.english.models.APIWord');
class CollectionsController extends GNStandardApiController {
	
	/**
	 * Description of this method
	 * @return void
	 */
	public function allowedActions()
	{
		return '*';
	}
	
	public function init() {
		$this->model = 'APICollection';
		$this->hasMany = array(
			'words'	=> array(
				'class'	=> 'APIWord',
				'key'	=> 'user_data_id'
			)
		);
		
		parent::init();
	}
	
	
}