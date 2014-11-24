<?php
class UpdateUserNodeHandler extends CComponent {
	public static function UpdateUserNode($user) {
		// @TODO : you cant convert to new conversion, see ZoneUser::createNode
		// return;
		Yii::import('application.modules.zone.models.*');
		// $user = currentUser();
		$profileTmp = $user->profile;
		$userID = IDHelper::uuidFromBinary($user->id,true);
		$displayname = $user->displayname;
		$displayname = ucwords($displayname);
		$email = $user->email;

		$Manager = new ZoneInstanceManager('/people/user');
		$properties = $Manager->properties();
		$gender = array(
			0=>'Female',
			1=>'Male'
		);
		$strGender = null;
		if(!empty($properties['/people/person/gender']['options'])){
			$strGender = array_keys($properties['/people/person/gender']['options'],$gender[$profileTmp->gender]);
		}
		$data = array(
			'name' 		=> $displayname,
			'zone_id'	=> $userID
		);
		$item = array ( 
			'/common/topic/description' => '',
			'/people/user/email'		=> $email,
			'/people/user/username'		=> $displayname,
			'/people/person/gender'		=> $strGender,
			'/people/person/date_of_birth'	=> $profileTmp->birth
		);
		
		$node = ZoneType::initNode('/people/user')->saveNode($data, $item);
		
	}
	public static function TestMore(){
	
	}
}