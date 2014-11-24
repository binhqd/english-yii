<?php
class ApiController extends CController {
	
	
	public function allowedActions()
	{
		return '*';
	}
	
	
	/**
	 * This action used get data realtime for articles
	 * ...
	 */
	public function actionRealTime(){
		if(!empty($_POST) && !empty($_POST['data'])){
			
			$data = array();
			foreach($_POST['data'] as $d){
				$data[] = IDHelper::uuidToBinary($d,true);
			}
			// jsonOut($_POST);
			$criteria = new CDbCriteria();
			$criteria->addInCondition('id',$data); 
			$criteria->order = "created DESC";
			$activities = ZoneActivity::model()->findAll($criteria);
			
			// jsonOut($criteria);
			$this->layout = "//layouts/master/ajax";
			// $this->renderHtml =true;
			$this->renderPartial('realtime',array(
				'activities'=>$activities,
			));
			
		}
	}
	
	
}