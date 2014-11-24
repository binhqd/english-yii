<?php
/**
 * @author Chu Tieu
 * @version 1.0
 */
class ZoneChangeLocationAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run() {
		try {
			if (!empty($_GET['location'])) {
				
				Yii::import('greennet.modules.users.models.GNUserProfile');
				$model = GNUserProfile::model()->findByAttributes(array('user_id'=>currentUser()->id));
				$model->location = $_GET['location'];
				
				$locations = ZoneRegisterForm::getLocations();
				
				$namelocation = null;
				foreach($locations as $key=>$location){
					if($model->location == $key){
						$namelocation = $location;
						break;
					}
				}
				
				if($model->save()){
					ajaxOut(array(
						'error' => false,
						'message' => UsersModule::t('Location has been saved successful.'),
						'value'	=> $namelocation
					));
				} else {
					ajaxOut(array(
						'error' => true,
						'message' => UsersModule::t('Location has not been saved successful.')
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
	
	function dateValid($monthbirth, $daybirth, $yearbirth) {
		if (!checkdate(intval($monthbirth), intval($daybirth), intval($yearbirth))) {
			return false;
		} else {
			$time = strtotime(intval($yearbirth) . '-' . intval($monthbirth) . '-' . intval($daybirth));
			if ($time > time()) {
				return false;
			}
			return $time;
		}
	}

}