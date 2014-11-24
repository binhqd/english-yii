<?php
class MigrateProfileHandler extends CComponent {
	public static function MigrateProfile($user) {
		// return;
		// $tmpUser = currentUser();
		
		$profileTmp = ZoneUserTmpProfile::model()->findByAttributes(array(
			'user_id'=>$user->id
		));
		GNUserProfile::model()->deleteAllByAttributes(array(
			'user_id'=>$user->id
		));
		$modelZoneUserProfile = new GNUserProfile;
		$modelZoneUserProfile->attributes = $profileTmp->attributes;
		$modelZoneUserProfile->user_id = $user->id;
		$modelZoneUserProfile->birth = $profileTmp->birth;
		
		if($modelZoneUserProfile->validate()){
			if($modelZoneUserProfile->save()){
				
			}else{
				$errors  = $modelZoneUserProfile->getErrors();
				list ($field, $_errors) = each ($errors);
				throw new Exception($_errors[0]);
			}
		}else{
			$errors  = $modelZoneUserProfile->getErrors();
			list ($field, $_errors) = each ($errors);
			throw new Exception($_errors[0]);
		}
		
		
	}
}