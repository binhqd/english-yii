<?php
class YourInterestsHandler extends CComponent {
	public static function YourInterests() {
		
		if(!empty($_GET['interests']) && $_GET['interests'] == 1){
			Yii::app()->session['firtLoginedMyZone'] = true;
		}
		$tmpUser = GNRegistrationValidation::model()->getUserByCode($_GET['code']);
		$zoneCategories = ZoneCategories::model()->findAll();
		foreach($zoneCategories as $key=>$value){
			$modelZoneInterestsCategories = new ZoneInterestsCategories;
			$modelZoneInterestsCategories->type_id = $value->id;
			$modelZoneInterestsCategories->user_id = $tmpUser->id;
			if(!$modelZoneInterestsCategories->save()){
				$errors  = $modelZoneInterestsCategories->getErrors();
				// list ($field, $_errors) = each ($errors);
				
			}else{
				
			}
			
		}
		
		
	}
}