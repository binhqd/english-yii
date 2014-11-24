<?php
Yii::import('greennet.modules.users.controllers.GNProfileController');
class ProfileController extends ZoneProfileController{
	public $layout = '//layouts/master/myzone';
	public $defaultAction = "wall";
	public function actions(){
		return CMap::mergeArray(parent::actions(), array(
			// 'index'	=> array(
				// 'class'			=> 'greennet.modules.users.actions.profile.GNProfileHomeAction',
				// 'viewFile'		=> 'application.modules.users.views.profile.home'
			// ),
			// 'edit'	=> array(
				// 'class'			=> 'greennet.modules.users.actions.profile.GNProfileEditAction',
				// 'onUpdated'		=> array(
					//array("application.modules.users.events.UpdateUserNodeHandler", "UpdateUserNode"),
					//array("application.modules.users.events.UpdateUserNodeHandler", "TestMore")
				// ),
				// 'viewFile'		=> 'application.modules.users.views.profile.edit',
				// 'uploader'		=> array(
					// 'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
					// 'uploadPath'	=> 'upload/user-photos/' . currentUser()->hexID,
					// 'storageEngines'	=> array(
						// 'mongo'	=> array(
							// 'class'			=> 'greennet.components.GNUploader.components.engines.mongo.GNGridFSEngine',
							 	// 'serverInfo'	=> array(
	 								// 'server'	=> '54.215.136.218',
									// 'port'		=> 27017,
									// 'dbname'	=> 'myzonedev'
	 							// )
						// )
					// )
				// )
			// ),
		));
	}
	
	public function filters()
	{
		return array(
			array(
				// Validate code if code is invalid or expired
				'greennet.modules.users.filters.ValidUserFilter + saveNodeInfo',
				'out'	=> array("files" =>
					array(
						array(
							"error"			=> true,
							"message"		=> "You need to login before continue"
						)
					)
				)
			)
		);
	}
	
	public function actionWall(){
		if(currentUser()->isGuest){
			$this->redirect('/');
		}
		if (!isset($_GET['old'])) {
			$this->redirect('/user/profile');
		}
		
		$this->renderHtml = true;
		

		$name = Yii::app()->request->getParam('name');
		$sort = Yii::app()->request->getParam('sort',null);
		$strSort = "created desc";
		$filter = array(ZoneActivity::OBJECT_TYPE_NODE,ZoneActivity::OBJECT_TYPE_ARTICLE,ZoneActivity::OBJECT_TYPE_ALBUM,ZoneActivity::OBJECT_TYPE_STATUS);
		if($sort == "top"){
			$strSort = "created desc";
			$filter = array(ZoneActivity::OBJECT_TYPE_ARTICLE,ZoneActivity::OBJECT_TYPE_ALBUM,ZoneActivity::OBJECT_TYPE_STATUS);
		}
		if($sort == "old"){
			$strSort = "created asc";
			$filter = array(ZoneActivity::OBJECT_TYPE_ARTICLE,ZoneActivity::OBJECT_TYPE_ALBUM,ZoneActivity::OBJECT_TYPE_STATUS);
		}
		
		$avatars = null;
		
		if (empty($name)) {
			$user = currentUser();
			//$avatars = ZoneUserAvatar::model()->getAvatars($user->hexID, 5);
			$profile = $user->profile;
			
		} else {
			// TODO:
		}
		
		$activities = ZoneActivity::getActivities(null, true, $user->id,$filter ,10,$strSort);


		
		if($this->isJsonRequest){
			$this->renderPartial('application.views.common.activity.wall',array(
				'activities'=>$activities['data'],
				'user'=>$user
			));
		} else {
			$this->render('application.modules.users.views.profile.home',array(
				'user'		=>$user,
				'profile'	=>$profile,
				//'avatars'	=>$avatars,
				'activities'=>$activities['data'],
				'pages'		=>$activities['pagination'],
				
			));
		}
	}
	public function actionViewByUsername() {
		
		$username = Yii::app()->request->getParam('username',null);
		
		// dump(Yii::app()->controller);
		// dump($username);
		if(strtolower($username) == strtolower(Yii::app()->controller->id)){
			$this->actionWall();
			
		}else{
		
			$user = ZoneUser::model()->findByUsername($username);
			
			if (empty($user)) {
				$strMessage = "Invalid Username";
				$backUrl = GNRouter::createUrl('/profile');
				
				if ($this->isJsonRequest) {
					ajaxOut(array(
						'error'		=> true,
						'type'		=> 'error',
						'message'	=> $strMessage,
						'url'		=> $backUrl,
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> true,
						'type'		=> 'error',
						'autoHide'	=> true,
						'message'	=> $strMessage,
					));
					$this->redirect('/profile');
				}
			}
			
			if ($user->id == currentUser()->id) {
				$this->redirect('/profile');
			}
			
	// 		dump($user);
			$profile = $user->profile;
			$node = $user->node;
			
			Yii::import('application.modules.users.models.ZoneUserAvatar');
			
			$avatars = ZoneUserAvatar::model()->getAvatars("avatar_" . $user->hexID, 4);
			
			$sort = Yii::app()->request->getParam('sort',null);
			$strSort = "created desc";
			$filter = array(ZoneActivity::OBJECT_TYPE_NODE,ZoneActivity::OBJECT_TYPE_ARTICLE,ZoneActivity::OBJECT_TYPE_ALBUM,ZoneActivity::OBJECT_TYPE_STATUS);
			if($sort == "top"){
				$strSort = "created desc";
				$filter = array(ZoneActivity::OBJECT_TYPE_ARTICLE,ZoneActivity::OBJECT_TYPE_ALBUM,ZoneActivity::OBJECT_TYPE_STATUS);
			}
			if($sort == "old"){
				$strSort = "created asc";
				$filter = array(ZoneActivity::OBJECT_TYPE_ARTICLE,ZoneActivity::OBJECT_TYPE_ALBUM,ZoneActivity::OBJECT_TYPE_STATUS);
			}
			
			
			$activities = ZoneActivity::getActivities(null,true,$user->id,$filter, 10,$strSort);
			
			
			if($this->isJsonRequest){
				$this->renderHtml = true;
				$this->renderPartial('application.views.common.activity.wall',array(
					'activities'=>$activities['data'],
					'user'=>$user
				));
			}else{
				$this->render('application.modules.users.views.profile.home',array(
					'user'=>$user,
					'profile'=>$profile,
					'node'=>$node,
					'avatars'=>$avatars,
					'activities'=>$activities['data'],
					'pages'=>$activities['pagination'],
				));
			}
		}
	}
	
	public function actionView() {
		$id = $_GET['id'];
	}
	
	/**
	 * This method is used to save information of user as node system
	 * Author: binhqd
	 */
	public function actionSaveNodeInfo()
	{
		$username = currentUser()->username;
		if (isset($_POST['ZoneUser'])) {
			$model = ZoneUser::model()->findByUsername($username);
			$model->scenario = 'edituserinfo';
			
			$model->attributes = $_POST['ZoneUser'];
			try {
				if ($model->validate()) {
					$model->save();
				}
			} catch (Exception $ex) {
				$message = array();
				foreach ($model->errors as $field => $error)
					$message[] = array("ZoneUser[{$field}]" => $error[0]);
				ajaxOut(array(
					'error'			=> true,
					'type'			=> 'validate',
					'message'		=> $message,
				));
			}
		}
		if(isset($_POST['UserNodeInfo'])) {
			/** 
			 * Save to database
			 * @author : Chu Tieu
			 */
			$modelProfile = ZoneUserProfile::model()->findByAttributes(array('user_id'=>currentUser()->id));
			$modelProfile->birth = $_POST['UserNodeInfo']['/people/person/date_of_birth'];
			$modelProfile->birth;
			$modelProfile->save();
			/** End : Save to database */
			
			
			$objNode = ZoneInstanceRender::get(currentUser()->hexID);
			$Manager = new ZoneInstanceManager('/people/user');
			$properties = $Manager->properties();
			
			$items = array();
			foreach($properties as $key=>$property){
				if(!is_numeric( @key($property) )) {
					if (isset($_POST['UserNodeInfo'][$key])) {
						if ($property['isUnique']) {
							$items[$key] = $_POST['UserNodeInfo'][$key];
						} else {
							$items[$key][] = $_POST['UserNodeInfo'][$key];
						}
					}
				}
			}
			
			$data = array(
				'zone_id'	=> $objNode->zone_id,
				'name'		=> currentUser()->displayname
			);
			
			// Update username also
			$items['/people/user/username'] = currentUser()->displayname;
			
			$Manager->save($data, $items, $_POST['token']);
			
			//$attributes = $_POST['ZoneInfomationForm'.currentUser()->hexID];
			
			$results = $Manager->values($objNode);
			if(!empty($results['value']['/people/person/date_of_birth'][0]['value'])){
					$results['value']['date_of_birth'] = 
						date('F d' , strtotime($results['value']['/people/person/date_of_birth'][0]['value'])); 
			}
			ajaxOut(array(
				'error'			=> false,
				'message'		=> 'Information has been saved successful',
				'attributes'	=> $results,
				//'valueSummary'	=> $attributes['description'],
				'token'			=> !empty($results['token']) ? $results['token'] : "",
			));
		} else{
			$out = array(
				'error'		=> true,
				'message'	=> "Empty POST request"
			);
			ajaxOut($out);
		}
	}
	/**
	 * This action used view page edit profile
	 * Author: thinhpq
	 **/
	
	public function actionEdit(){
		if(currentUser()->isGuest) $this->redirect('/');
		$userProperties = $this->userProperties();
		$user = ZoneUser::model()->findByEmail(currentUser()->email);		
		
		$type = Yii::app()->request->getParam('type',null);
		if(Yii::app()->request->isAjaxRequest && $type == "info"){
			$this->layout = "//layouts/master/ajax";
			$this->renderHtml = true;
			$this->renderPartial('application.modules.users.views.profile._infomation',array(
				'propertiesInfomation'=>$userProperties['propertiesInfomation'],
			));
			exit();
		}
		
		$this->render('application.modules.users.views.profile.edit',array(
			'user'=>$user,
			'constructsBasic'=>$userProperties['constructsBasic'],
			'constructsOther'=>$userProperties['constructsOther'],
			'propertiesInfomation'=>$userProperties['propertiesInfomation'],
			'sumary'=>$userProperties['sumary'],
			'results'=>$userProperties['results'],
			'years'=>$userProperties['years'],
			'days'=>$userProperties['days'],
			'locations'=>$userProperties['locations'],
			'months'=>$userProperties['months'],
			'token'=>!empty($userProperties['token']) ? $userProperties['token'] : "",
		));
	}
	/**
	 * This method used create form validate for  properties & construct for properties
	 * Author: thinhpq
	 **/
	public static function userProperties($userHexID = null){
	
		$days = ZoneRegisterForm::getDays();
		$years = ZoneRegisterForm::getYears();
		$months = ZoneRegisterForm::getMonths();
		$locations = ZoneRegisterForm::getLocations();
	
		$objNode = ZoneInstanceRender::get( !empty($userHexID) ? $userHexID : currentUser()->hexID );
		$Manager = new ZoneInstanceManager('/people/user');
		$results = $Manager->values($objNode);
		
		$properties = $Manager->properties();
		
		$constructsBasic = array();
		$constructsOther = array();
		
		$constFormInfomation = "";
		$rulesInfomation = "";
		$attributeLabelsInfomation = "";
		
		
		if(!empty($properties)){
			foreach($properties as $key=>$property){
				if(is_numeric( @key($property) )){
					$constructsOther[] = $property;	
					/**
					 * Create Form validate for form other.
					 * FormModel save in folder /component/rules/abc.php
					 **/
					if(!empty($property[0]['name'])) {
						$tmpNameType = $property[0]['name'];
						$tmpNameType = str_replace("/","",$tmpNameType);
						
						$file = Yii::getPathOfAlias('rules')."/ZonePropertiesForm".$tmpNameType."".currentUser()->hexID.".php";
						if(!empty($property[2])){
							$constFormProp = "";
							$rulesInfomationProp = "";
							$attributeLabelsProp = "";
							foreach($property[2] as $k=>$prop){
								// const
								$tmpLabel = strtolower($prop['label']);
								$tmpLabel = str_replace(" ","",$tmpLabel);
								$tmpLabel = str_replace("-","",$tmpLabel);
								$constFormProp .= "public $".$tmpLabel." = null;\n";
								
								//rules
								if(!$prop['suggest'] && $prop['isUnique'] && $prop['type'] != "hidden"){
									
									$rulesInfomationProp[] = $tmpLabel;
								}
								
								
								$attributeLabelsProp[$tmpLabel] = $prop['label'];
								
							}
							$datediff = "datediff";
							$textThis = "this";
							$constFormProp .= "public $".$datediff." = 0;\n";
							/**
							 * Create multiple CFormModel for properties of current user.
							 **/
							$_params = "params";
							$_attribute = "attribute";
							$rulesInfomationProp = implode(",", $rulesInfomationProp);
							$attributeLabelsProp = @CJSON::encode($attributeLabelsProp);
							$attributeLabelsProp = str_replace(":","=>",$attributeLabelsProp);
							$attributeLabelsProp = str_replace("{","",$attributeLabelsProp);
							$attributeLabelsProp = str_replace("}","",$attributeLabelsProp);
							$content = "<?php
							class ZonePropertiesForm".$tmpNameType."".currentUser()->hexID." extends CFormModel
							{
								".$constFormProp."
								public function rules()
								{
									return array(
										array('".$rulesInfomationProp."', 'required'),
										array('datediff','validateDate')
									);
								}
								public function validateDate($".$_attribute.",$".$_params."){
									if(!$".$textThis."->hasErrors())
									{
										if(!empty($".$textThis."->to) && !empty($".$textThis."->from)){
											if(MyZoneHelper::checkDate($".$textThis."->to) && MyZoneHelper::checkDate($".$textThis."->from)){
												if( ( (strtotime($".$textThis."->to) + 1) - strtotime($".$textThis."->from)) <=0 ){
													$".$textThis."->addError('from', \"The From couldn't small than  To\");
												}
											}else{
												if( ( (strtotime(MyZoneHelper::strDate($".$textThis."->to)) + 1) - strtotime(MyZoneHelper::strDate($".$textThis."->from))) <=0 ){
													$".$textThis."->addError('from', \"The From couldn't small than  To\");
												}
											}

										}
										
										if(!empty($".$textThis."->enddate) && !empty($".$textThis."->startdate)){
											if(MyZoneHelper::checkDate($".$textThis."->enddate) && MyZoneHelper::checkDate($".$textThis."->startdate)){
												if( ((strtotime($".$textThis."->enddate) + 1) - strtotime($".$textThis."->startdate)) <=0 ){
													$".$textThis."->addError('enddate', \"The Start Date couldn't small than  End Date\");
												}
											}else{
												if( ( (strtotime(MyZoneHelper::strDate($".$textThis."->enddate)) + 1) - strtotime(MyZoneHelper::strDate($".$textThis."->startdate))) <=0 ){
													$".$textThis."->addError('enddate', \"The Start Date couldn't small than  End Date\");
												}
												
											}
										}
									}
								
								}
								public function attributeLabels()
								{
									return array(".$attributeLabelsProp.");
								}
							}
							";
							
							@file_put_contents($file, $content, /*FILE_APPEND | */LOCK_EX);
						
						}
						
					}

				}else{
					
					$constructsBasic[] = $property;
					
					
					// const
					$tmpLabel = strtolower($property['label']);
					$tmpLabel = str_replace(" ","",$tmpLabel);
					$tmpLabel = str_replace(",","",$tmpLabel);
					$tmpLabel = str_replace("-","",$tmpLabel);
					$constFormInfomation .= "public $".$tmpLabel." = null;\n";
					
					//rules
					if($property['isUnique']) $rulesInfomation[] = $tmpLabel;
					$attributeLabels[$tmpLabel] = $property['label'];
					
				}
			}
		}
		
		/**
		 * Create Form validate for form infomation.
		 * FormModel save in folder /component/rules/abc.php
		 **/
		
		$rulesInfomation = implode(",", $rulesInfomation);
		$attributeLabels = @CJSON::encode($attributeLabels);
		$attributeLabels = str_replace(":","=>",$attributeLabels);
		$attributeLabels = str_replace("{","",$attributeLabels);
		$attributeLabels = str_replace("}","",$attributeLabels);
		
		
		$file = Yii::getPathOfAlias('rules')."/ZoneInfomationForm".currentUser()->hexID.".php";
		$content = "<?php
		class ZoneInfomationForm".currentUser()->hexID." extends CFormModel
		{
			".$constFormInfomation."
			public function rules()
			{
				return array(
					array('".$rulesInfomation."', 'required'),
					array('email','email')
				);
			}
			public function attributeLabels()
			{
				return array(".$attributeLabels.");
			}
		}
		";
		@file_put_contents($file, $content, /*FILE_APPEND | */LOCK_EX);
		
		
		
		/**
		 * Split array combine array properties for user node.
		 * ! Compare array infomation
		 **/
		
		$sumary = "";
		if(!empty($results['value']['/common/topic/description'][0]['value'])){
			$sumary = $results['value']['/common/topic/description'][0]['value']; 
			unset($results['value']['/common/topic/description']);
		}
		
		$propertiesInfomation = array();
		if(!empty($results['value'])){
			$tmpCnt = 0;
			foreach($results['value'] as $key=>$property){
				// dump($property);
				$propertiesInfomation[$tmpCnt]['label'] = "";
				if(!empty($properties[$key]['label'])) $propertiesInfomation[$tmpCnt]['label'] = $properties[$key]['label'];
				if(!empty($properties[$key]['expected'])) $propertiesInfomation[$tmpCnt]['expected'] = $properties[$key]['expected'];
				else $propertiesInfomation[$tmpCnt]['expected'] = "";
				
				$propertiesInfomation[$tmpCnt]['value'] = "";
				$propertiesInfomation[$tmpCnt]['node'] = array();
				if(!empty($property[0]['node'])){
					if(!empty($property[0]['node']['name'])) $propertiesInfomation[$tmpCnt]['value'][] = $property[0]['node']['name'];
					$propertiesInfomation[$tmpCnt]['node'] =  $property[0]['node'];
				}else{
					if(!empty($property[0]['value'])) $propertiesInfomation[$tmpCnt]['value'][] = $property[0]['value'];
				}
				$tmpCnt++;
			}
		}
		
		
		return array(
			'constructsBasic'=>$constructsBasic,
			'constructsOther'=>$constructsOther,
			'propertiesInfomation'=>$propertiesInfomation,
			'sumary'=>$sumary,
			'results'=>$results,
			'years'=>$years,
			'days'=>$days,
			'locations'=>$locations,
			'months'=>$months,
			'token'=>!empty($results['token']) ? $results['token'] : "",
		);
	}
	/**
	 * This action used get nodes for edit form in page EditProfile
	 * Author: thinhpq
	 **/
	public function actionSuggestNodes(){
		$results = array();
		try{
			$nodes = ZoneInstanceRender::search($_GET['term'],10,0,!empty($_GET['expected']) ?  array($_GET['expected'] => $_GET['term'].'*') : null);
		}catch(Exception $e){
			
			
			jsonOut(array(
				'results' => array(),
			));
		}
		foreach ($nodes as $node) {
			$results[] = array('id' => $node['zone_id'], 'value' => $node['name'], 'info' => " <b> " . $node['label'] . "</b>");
		}
		jsonOut(array(
			'results' => $results,
		));
	}

	
}