<?php

class FamilyController extends GNController {
	public $layout = '//layouts/master/myzone';
	public function allowedActions(){
		return '*';
	}
	
	public function actionIndex(){
		if(currentUser()->isGuest){
			$this->redirect('/');
		}
		$this->renderHtml = true;
		

		// $name = Yii::app()->request->getParam('name');
		
		$user = currentUser();
		
		
		$criteria=new CDbCriteria();
		$criteria->compare('node_id',IDHelper::uuidFromBinary($user->id,true));
		$criteria->compare('active',0);
		$pages = new CPagination(count(ZoneRelatedRequest::model()->findAll($criteria)));
		$pages->pageSize = 10;
		$pages->applyLimit($criteria);
		$zoneRelatedRequest = ZoneRelatedRequest::model()->findAll($criteria);
		
		
		$this->render('index',array(
			'user'=>$user,
			'zoneRelatedRequest'=>$zoneRelatedRequest,
			'pages'=>$pages,
			
			
		));
	}
	public function actionApproved(){
		if(Yii::app()->request->isAjaxRequest){
			$id = Yii::app()->request->getParam('id',null);
			if($id != NULL) ZoneRelatedRequest::model()->approval($id);
		}
	}
	
	
	
	
}