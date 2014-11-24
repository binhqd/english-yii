<?php
/**
 * @author Chu Tieu
 * @version 1.0
 */
class ZoneChangeLanguageAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run() {
		try {
			if (!empty($_GET['language'])) {
				$model = ZoneUserProfile::model()->findByAttributes(array('user_id'=>currentUser()->id));
				$model->prefer_language_id = $_GET['language'];
				
				if($model->save()){
					
					ajaxOut(array(
						'error' => false,
						'message' => UsersModule::t('Language has been saved successful.'),
						'value'	=> $model->language->name
					));
				} else {
					ajaxOut(array(
						'error' => true,
						'message' => UsersModule::t('Language has not been saved successful.')
					));
				}
			}
		} catch (Exception $e) {
			ajaxOut(array(
				'error' => true,
				'type' => 'error',
				'autoHide' => true,
				'message' => $e->getMessage()
			));
		}
	}

}