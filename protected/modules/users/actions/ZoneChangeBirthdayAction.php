<?php
/**
 * @author Chu Tieu
 * @version 1.0
 */
class ZoneChangeBirthdayAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run() {
		try {
			if (!empty($_GET['Birthday'])) {
				$monthbirth = $_GET['Birthday']['month'];
				$daybirth = $_GET['Birthday']['day'];
				$yearbirth = $_GET['Birthday']['year'];
				
				if(!checkdate(intval($monthbirth), intval($daybirth), intval($yearbirth))){
					ajaxOut(array(
						'error' => true,
						'message' => UsersModule::t('Birthday invalid.')
					));
				} else {
					$time = strtotime(intval($yearbirth) . '-' . intval($monthbirth) . '-' . intval($daybirth));
					if ($time > time()) {
						ajaxOut(array(
							'error' => true,
							'message' => UsersModule::t('Birthday invalid.')
						));
					}
					Yii::import('greennet.modules.users.models.GNUserProfile');
					$model = GNUserProfile::model()->findByAttributes(array('user_id'=>currentUser()->id));
					
					$model->birth = $yearbirth . '-' . $monthbirth . '-' . $daybirth;
					$strToken = md5(uniqid(32));
						// dump($strToken);
					if($model->save()){
						
						/** Save neo4j */
						$objNode = ZoneInstanceRender::get(currentUser()->hexID);
						$Manager = new ZoneInstanceManager('/people/user');
						$properties = $Manager->properties();
						
						$items = array();
						foreach($properties as $key=>$property){
							if($key == '/people/person/date_of_birth'){
								$items['/people/person/date_of_birth'] = $model->birth;
							}
						}
						
						$data = array(
							'zone_id'	=> $objNode->zone_id,
							'name'		=> currentUser()->displayname
						);
						
						$Manager->save($data, $items, $_GET['token']);
						
						$Manager = new ZoneInstanceManager('/people/user');
						$results = $Manager->values($objNode);
						
						/** End : Save neo4j */
						
						ajaxOut(array(
							'error'		=> false,
							'message'	=> UsersModule::t('Birthday has been saved successful.'),
							'value'		=> date('M d, Y', strtotime($model->birth)),
							'newtoken'		=> $results['token'],
						));
						
						
						
					} else {
						ajaxOut(array(
							'error' => true,
							'message' => UsersModule::t('Birthday has not been saved successful.')
						));
					}
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