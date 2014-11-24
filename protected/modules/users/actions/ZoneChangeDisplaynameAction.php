<?php
/**
 * @author Chu Tieu
 * @version 1.0
 */
class ZoneChangeDisplaynameAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run() {
		try {
			if (!empty($_GET['User'])) {
				
				if(empty($_GET['User']['firstname']) || empty($_GET['User']['lastname'])){
					ajaxOut(array(
						'error' => true,
						'message' => UsersModule::t('Firstname and Lastname cannot be blank.')
					));
				}
				$model = ZoneUser::model()->findByPk(currentUser()->id);
				$model->scenario  = 'edituserinfo';
				$model->firstname = trim($_GET['User']['firstname']);
				$model->lastname = trim($_GET['User']['lastname']);
				$model->displayname = $model->firstname . ' ' . $model->lastname;
				
				$model->validate();
				
				if($model->save()){
					ajaxOut(array(
						'error' => false,
						'message' => UsersModule::t('Name has been saved successful.'),
						'value'	=> $model->displayname
					));
				} else {
					ajaxOut(array(
						'error' => true,
						'message' => UsersModule::t('Name has not been saved successful.')
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