<?php
Yii::import('application.components.jl_bd.helpers.*');
/**
Nguoi tao : thinhpq
Controller dung de quan ly Business
**/
class MonitorController extends JLController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/back_end';
	public $pageSize = 30;
	public function actionRemoveBiz($bizID=NULL){
		JLAwaitingBusiness::model()->findByPk(IDHelper::uuidToBinary($bizID))->delete();
		jsonOut(array(
			'error'=>false,
			'msg'=>'Delete business success'
		));
	}
	
	public function actionUnPublishBiz($bizID=NULL){
		/**
			Set status table awaiting
		*/
		JLAwaitingBusiness::updateField(array(
			'status'=>JLAwaitingBusiness::STATUS_UNPUBLISHED,
			'contribute'=>1
		),IDHelper::uuidToBinary($bizID));
		/**
			Delete record business in table sphinx and table business
		*/
		JLBusiness::model()->findByPk(IDHelper::uuidToBinary($bizID))->delete();
		JLBusiness::model()->removeBusinessFromSphinx($bizID);
		
		//return
		jsonOut(array(
			'error'=>false,
			'msg'=>'The business has been un published successful'
		));
		
	}
	public function actionPublishBiz($bizID=NULL){

		// move record to table business from awaiting business
		$business = JLAwaitingBusiness::model()->findByPk(IDHelper::uuidToBinary($bizID));
		$business->owner_id = NULL;
		$modelBusiness = new JLBusiness;
		$modelBusiness->attributes = $business->attributes;
		$avatar = "";
		$ext = @CJSON::decode($modelBusiness->_ext);
		if(!empty($ext['avatar'])) $avatar = $ext['avatar'];
		
		
		
		if($modelBusiness->validate()){
			
			if($modelBusiness->save()){
				/**
					Update business in db sphinx
				**/
				$categoryMapping = new JLBusinessesCategoriesMapping;
				$categoryMapping = $categoryMapping->setCatMapofBizToAddSpinx($business->id);
				Yii::import('behaviors.RealtimeIndexBehavior');
				$business->attachBehavior('reindex','RealtimeIndexBehavior');
				$uuid = IDHelper::uuidFromBinary($business->id);
				
				$uuid = str_replace('-', '',$uuid);
				$owner_id = "";
				if($business->owner_id!=""){
					$owner_id = IDHelper::uuidFromBinary($business->owner_id);
					$owner_id = str_replace('-', '',$owner_id);
				}
				$array = array(
					'uuid'				=> $uuid,
					'reviews'			=> $business->reviews,
					'number_ratings'	=> $business->number_ratings,
					'avg_ratings'		=> $business->avg_ratings,
					'latitude' 			=> $business->latitude,
					'longitude' 		=> $business->longitude,
					'name' 				=> $business->name,
					'categories'		=> $categoryMapping['textCategory'],
					'jl_categories'		=> $categoryMapping['textJLCategory'],
					'keywords'			=> $business->keywords,
					'description' 		=> $business->description,
					'address' 			=> $business->address,
					'location'			=> $business->location,
					'url'				=> $business->url,
					'national_phone'	=> $business->national_phone,
					'latitude'			=> $business->latitude,
					'mobile' 			=> $business->mobile,
					'fax' 				=> $business->fax,
					'email' 			=> $business->email,
					'slogan' 			=> $business->slogan,
					'state'				=> $business->state_id,
					'suburb'			=> $business->suburb_id,
					'alias'				=> $business->alias,
					'avatar'			=> $avatar,
				);
				$business->insertIndexByArray(IDHelper::uuidFromBinary($business->id),$array ,'biz');
				$business->disableBehavior('reindex');

				/**
					todo : update table bizlocation
					
				**/
				
				
				/**
					Log business contribute
				**/
				$business->status = JLAwaitingBusiness::STATUS_PUBLISHED;
				if(!$business->save()){
					$errors = $business->getErrors();
					list ($field, $_errors) = each ($errors);
					jsonOut(array(
						'error'=>true,
						'msg'=>"Can't save data on table business. ". $_errors[0]
					));
				}
				
				// return 
				jsonOut(array(
					'error'=>false,
					'msg'=>'The business has been published successful'
				));
				
			}else{
				$errors = $modelBusiness->getErrors();
				list ($field, $_errors) = each ($errors);
				jsonOut(array(
					'error'=>true,
					'msg'=>"Can't save data on table business. ". $_errors[0]
				));
				
			}
		}else{
			$errors = $modelBusiness->getErrors();
			list ($field, $_errors) = each ($errors);
			jsonOut(array(
				'error'=>true,
				'msg'=>"Can't save data on table business. ". $_errors[0]
			));
		}


	}
	public function actionContributed($json=NULL){
		$criteria	=	new CDbCriteria;
		
		$status = 0;
		$criteria->condition = " created>= :fromday AND created<= :today and contribute=:contribute and status=:status";
		//Check request is json
		$date=$this->getDayFT();
		$criteria->params = array (	
			':fromday' => $date['dateFrom'],
			':today' => $date['dateTo'],
			':contribute' => 1,
			':status' => $status,
		);
		$result = JLAwaitingBusiness::model()->findAll($criteria);
		$count		=	count($result);
		$pages				=	new CPagination($count);
		$pages->pageSize	=	$this->pageSize;
		$pages->applyLimit($criteria);
		$criteria->order = "created desc";
		$result = JLAwaitingBusiness::model()->findAll($criteria);
		
		if((isset($_GET['json']) && $_GET['json']==true) || isset($_GET['filter']) ){
			$this->layout = "ajax";
			$this->render('_viewContributed',array(
				'arrBusiness'=>$result,
				'pages'=>$pages,
				'count'=>$count,
				'dateFrom'=>$date['dateFrom'],
				'dateTo'=>$date['dateTo'],
			));
			exit;
		}

		$this->render('business',array(
			'type'=>3,
			'arrBusiness'=>$result,
			'pages'=>$pages,
			'count'=>$count,
			'dateFrom'=>$date['dateFrom'],
			'dateTo'=>$date['dateTo'],
			'attributes'=>array(
				'info'=>false,
				'announcement'=>false,
				'attachments'=>false,
				'attributes'=>false,
				'usefull'=>false,
				'flash'=>false,
				'video'=>false,
			),
			'bootmenu'=>array(
				'new'=>false,
				'publish'=>false,
				'unpublish'=>false,
				'review'=>false,
				'contributed'=>true,
			),
		));
	}
	public function actionDeteleReview($reviewID=NULL){
		$binReviewID = IDHelper::uuidToBinary($reviewID);
		
		$modelReview = JLReview::model()->findByPk($binReviewID);
		$currentUserReview = NULL;
		if(!empty($modelReview)){
			$currentUserReview = $modelReview->user;
		}
		// Attach behavior for user
		$currentUserReview->attachBehavior('UserReview', 'application.modules.reviews.components.behaviors.JLUserReviewBehavior');
		$delete = $currentUserReview->deleteReview($binReviewID);
		if (!$delete['error']) {


			
			/***** Remove point of user *****/
			// Attach behavior for user
			Yii::import('application.modules.pointSystem.models.JLPointSystem');
			$currentUserReview->attachBehavior('UserPoint', 'application.modules.pointSystem.components.behaviors.JLUserPointBehavior');
			$currentUserReview->retrieveAction(JLPointSystem::WRITE_REVIEW, null, $binReviewID);
			if ($delete['model']->is_first)
				$currentUserReview->retrieveAction(JLPointSystem::FIRST_REVIEW, null, $delete['model']->id);
				
			$currentUserReview->detachBehavior('UserPoint');
			
			jsonOut(array(
				'error'=>false,
				'msg'=>'This review has been deleted'
			));
			
			
		} else{
			jsonOut(array(
				'error'=>true,
				'msg'=>'Cannot delete this review'
			));
		}
	}
	public function actionReview($json=NULL){
		
		$criteria	=	new CDbCriteria;
		$criteria->condition = " created>= :fromday AND created<= :today";
		//Check request is json
		$date=$this->getDayFT();
		$criteria->params = array (	
			':fromday' => $date['dateFrom'],
			':today' => $date['dateTo'],
		);
		$result = JLReview::model()->findAll($criteria);
		$count		=	count($result);
		$pages				=	new CPagination($count);
		$pages->pageSize	=	$this->pageSize;
		$pages->applyLimit($criteria);
		$criteria->order = "created desc";
		$result = JLReview::model()->findAll($criteria);
		
		if((isset($_GET['json']) && $_GET['json']==true) || isset($_GET['filter']) ){
			$this->layout = "ajax";
			$this->render('_viewReview',array(
				'arrReview'=>$result,
				'pages'=>$pages,
				'count'=>$count,
				'dateFrom'=>$date['dateFrom'],
				'dateTo'=>$date['dateTo'],
			));
			exit;
		}
		$this->render('business',array(
			'type'=>2,
			'arrBusiness'=>$result,
			'pages'=>$pages,
			'count'=>$count,
			'dateFrom'=>$date['dateFrom'],
			'dateTo'=>$date['dateTo'],
			'attributes'=>array(
				'info'=>false,
				'announcement'=>false,
				'attachments'=>false,
				'attributes'=>false,
				'usefull'=>false,
				'flash'=>false,
				'video'=>false,
			),
			'bootmenu'=>array(
				'new'=>false,
				'publish'=>false,
				'unpublish'=>false,
				'review'=>true,
				'contributed'=>false,
			),
		));
	}
	public function actionPublish($json=NULL){
		$this->actionNew($json,'publish',JLAwaitingBusiness::STATUS_PUBLISHED);
	}
	public function actionUnPublish($json=NULL){
		$this->actionNew($json,'unpublish',JLAwaitingBusiness::STATUS_UNPUBLISHED);
	}
	public function actionNew($json=NULL,$publish=NULL,$number=NULL){
		$bootmenu = array(
			'new'=>false,
			'publish'=>false,
			'unpublish'=>false,
			'review'=>false,
			'contributed'=>false,
			
		);
		$criteria	=	new CDbCriteria;
		$criteria->condition = " status <>:unpublish AND status <>:publish AND contribute=:contribute AND created>= :fromday AND created<= :today";
		if($publish=="publish"){
			$criteria->condition = " status=:publish AND  created>= :fromday AND created<= :today AND contribute=:contribute";
			$bootmenu['publish'] = true;
		}
		if($publish=="unpublish"){
			$criteria->condition = " status=:publish AND created>= :fromday AND created<= :today AND contribute=:contribute";
			$bootmenu['unpublish'] = true;
		}
		if($publish==NULL){
			$bootmenu['new'] = true;
		}
		
		//Check request is json
		$date=$this->getDayFT();
		
		if($publish==NULL){
			$criteria->params = array (	
				':fromday' => $date['dateFrom'],
				':today' => $date['dateTo'],
				':contribute' => 0,
				':publish' => JLAwaitingBusiness::STATUS_PUBLISHED,
				':unpublish' => JLAwaitingBusiness::STATUS_UNPUBLISHED,
			);
		}else{
			$criteria->params = array (	
				':fromday' => $date['dateFrom'],
				':today' => $date['dateTo'],
				':publish' => $number,
				':contribute' => 1,
			);		
		}

		$result = JLAwaitingBusiness::model()->findAll($criteria);
		$count		=	count($result);
		$pages				=	new CPagination($count);
		$pages->pageSize	=	$this->pageSize;
		$pages->applyLimit($criteria);
		$criteria->order = "created desc";
		$result = JLAwaitingBusiness::model()->findAll($criteria);
		
		if((isset($_GET['json']) && $_GET['json']==true) || isset($_GET['filter']) ){
			$this->layout = "ajax";
			$this->render('_viewBusiness',array(
				'arrBusiness'=>$result,
				'pages'=>$pages,
				'count'=>$count,
				'dateFrom'=>$date['dateFrom'],
				'dateTo'=>$date['dateTo'],
			));
			exit;
		}
		$this->render('business',array(
			'type'=>1,
			'arrBusiness'=>$result,
			'pages'=>$pages,
			'count'=>$count,
			'dateFrom'=>$date['dateFrom'],
			'dateTo'=>$date['dateTo'],
			'attributes'=>array(
				'info'=>false,
				'announcement'=>false,
				'attachments'=>false,
				'attributes'=>false,
				'usefull'=>false,
				'flash'=>false,
				'video'=>false,
			),
			'bootmenu'=>$bootmenu,
		));
	}
	public function actionBusiness($key=NULL,$json=NULL){
		$monitorBusiness = $this->outData($key,BusinessMonitor::INFO,$json);
		$this->renderItem($monitorBusiness,true,BusinessMonitor::INFO);
	}
	public function actionAnnouncement($key=NULL,$json=NULL){		
		$monitorBusiness = $this->outData($key,BusinessMonitor::ANNOUNCEMENT,$json);
		$this->renderItem($monitorBusiness,false,BusinessMonitor::ANNOUNCEMENT);

	}
	public function actionAttributes($key=NULL,$json=NULL){
		$monitorBusiness = $this->outData($key,BusinessMonitor::ATTRIBUTE,$json);
		$this->renderItem($monitorBusiness,false,BusinessMonitor::ATTRIBUTE);
	}
	public function actionAttachment($key=NULL,$json=NULL){
		$monitorBusiness = $this->outData($key,BusinessMonitor::ATTACHMENT,$json);
		$this->renderItem($monitorBusiness,false,BusinessMonitor::ATTACHMENT);
	}
	public function actionLink($key=NULL,$json=NULL){
		$monitorBusiness = $this->outData($key,BusinessMonitor::LINK,$json);
		$this->renderItem($monitorBusiness,false,BusinessMonitor::LINK);

	}
	public function actionFlash($key=NULL,$json=NULL){
		$monitorBusiness = $this->outData($key,BusinessMonitor::FLASH,$json);
		$this->renderItem($monitorBusiness,false,BusinessMonitor::FLASH);
	}
	public function actionVideo($key=NULL,$json=NULL){
		$monitorBusiness = $this->outData($key,BusinessMonitor::VIDEO,$json);
		$this->renderItem($monitorBusiness,false,BusinessMonitor::VIDEO);
	}
	private function getDayFT(){
		if((isset($_GET['json']) && $_GET['json']==true) && isset($_GET['filter']) ){
			switch($_GET['filter']){
				case 0:
					$dateFrom = date("Y-m-d"). " 00:00:00";
					$dateTo= date("Y-m-d"). " 23:59:59";
				break;
				case 1:
					$dateFrom = date('Y-m-d', strtotime('Last Monday', time()))." 00:00:00";
					$dateTo = date('Y-m-d', strtotime('Next Sunday', time()))." 23:59:59";
				break;
				case 2:
					$dateFrom = date("Y")."-01-01 ".date("G:i:s");
					$dateTo = date("Y-m-d G:i:s");
					if(isset($_GET['dateFrom']) && $_GET['dateFrom']!="") $dateFrom = $_GET['dateFrom']." 23:59:59";
					if(isset($_GET['dateTo']) && $_GET['dateTo']!="") $dateTo = $_GET['dateTo']." 23:59:59";
				break;
			}
		}else{
			$dateFrom = date("Y")."-01-01 ".date("G:i:s");
			$dateTo = date("Y-m-d G:i:s");
			if(isset($_GET['dateFrom']) && $_GET['dateFrom']!="") $dateFrom = $_GET['dateFrom']." 23:59:59";
			if(isset($_GET['dateTo']) && $_GET['dateTo']!="") $dateTo = $_GET['dateTo']." 23:59:59";
		}
		return array(
			'dateFrom'=>$dateFrom,
			'dateTo'=>$dateTo,
		);
	}
	private function outData($key=NULL,$type=NULL,$json=NULL){
		$modelBusiness = new JLBusiness;
		$attributes = array('type'=>$type);
		$search = false;
		if($key!="" && $type=0){
			$search = true;
			$attributes = array(
				'key'=>$key,
				'type'=>$type
			);
		}
		$criteria	=	new CDbCriteria;
		$criteria->condition = " time>= :fromday AND time<= :today ";

		
		//Check request is json
		$date=$this->getDayFT();
		
		$criteria->params = array (	
			':fromday' => $date['dateFrom'],
			':today' => $date['dateTo']
		);
		if($search==false){
			$criteria->addSearchCondition('_key',$key);
		}
		
		$result = BusinessMonitor::model()->findAllByAttributes($attributes,$criteria);
		$count		=	count($result);
		$pages				=	new CPagination($count);
		$pages->pageSize	=	$this->pageSize;
		$pages->applyLimit($criteria);
		$criteria->order = "time desc";
		$result = BusinessMonitor::model()->findAllByAttributes($attributes,$criteria);
		if((isset($_GET['json']) && $_GET['json']==true) || isset($_GET['filter']) ){
			$this->layout = "ajax";
			$attributes = false;
			if($type==2){
				$attributes = true;
			}
			$this->render('_view',array(
				'arrBusiness'=>$result,
				'pages'=>$pages,
				'count'=>$count,
				'dateFrom'=>$date['dateFrom'],
				'dateTo'=>$date['dateTo'],
				'modelBusiness'=>$modelBusiness,
				'attributes'=>$attributes,
			));
			exit;
		}
		return array(
			'result'=>$result,
			'pages'=>$pages,
			'count'=>$count,
			'dateFrom'=>$date['dateFrom'],
			'dateTo'=>$date['dateTo'],
			'modelBusiness'=>$modelBusiness,
		);
	}
	private function renderItem($monitorBusiness=NULL,$info=false,$item=NULL){
		$attributes = array(
			'info'=>false,
			'announcement'=>false,
			'attachments'=>false,
			'attributes'=>false,
			'usefull'=>false,
			'flash'=>false,
			'video'=>false,
		);
		switch($item){
			case 0:
				$attributes['info'] = true;
			break;
			case 1:
				$attributes['announcement'] = true;
			break;
			case 2:
				$attributes['attributes'] = true;
			break;
			case 3:
				$attributes['attachments'] = true;
			break;
			case 4:
				$attributes['usefull'] = true;
			break;
			case 5:
				$attributes['flash'] = true;
			break;
			case 6:
				$attributes['video'] = true;
			break;
		}

		$this->render('business',array(
			'arrBusiness'=>$monitorBusiness['result'],
			'pages'=>$monitorBusiness['pages'],
			'count'=>$monitorBusiness['count'],
			'modelBusiness'=>$monitorBusiness['modelBusiness'],
			'dateFrom'=>$monitorBusiness['dateFrom'],
			'dateTo'=>$monitorBusiness['dateTo'],
			'attributes'=>$attributes,
			'bootmenu'=>array(
				'new'=>false,
				'publish'=>false,
				'unpublish'=>false,
				'review'=>false,
				'contributed'=>false,
			),
			'info'=>$info
		));
	}

}
