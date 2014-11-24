<?php

class ZoneController extends GNController {
	public $categories = array();
	/* SEO Vars */
    public $pageTitle = 'Youlook';
    public $pageDesc = '';
    public $pageRobotsIndex = true;

    public $pageOgTitle = '';
    public $pageOgDesc = '';
    public $pageOgImage = '';
	
	public function init(){
		
		if(currentUser()->isGuest){
			$this->categories = ZoneCategories::model()->getCategories(10);
		}else{
			$zoneInterestsCategories = ZoneInterestsCategories::model()->getInterestedForUser(currentUser()->id);
			$countInterestCat = ZoneInterestsCategories::model()->countInterested(currentUser()->id);
			
			// if(!empty($zoneInterestsCategories) && (Yii::app()->getModule('interest')->limitTopic - $countInterestCat)<=0 ){
			if(!empty($zoneInterestsCategories)){
				foreach($zoneInterestsCategories as $key=>$value){
					$this->categories[] = (object) $value->categories->attributes;
				}
			}else{
				$this->categories = ZoneCategories::model()->getCategories(10);
				// $this->redirect(ZoneRouter::createUrl('/interest'));
			}
		}

		parent::init();
	}

	public function afterRender($view, &$output)
	{
		parent::afterRender($view, $output);
		try {
			if (!$this->isJsonRequest) {
				$requestInfor = array();
				$requestInfor['module'] = !empty(Yii::app()->controller->module) ? Yii::app()->controller->module->id : 'app';
				$requestInfor['controller'] = Yii::app()->controller->id;
				$requestInfor['action'] = Yii::app()->controller->action->id;
				$requestInfor['alias'] = "/{$requestInfor['module']}/{$requestInfor['controller']}/{$requestInfor['action']}";
				$requestInfor['url'] = Yii::app()->request->url;

				Yii::app()->session['history'] = $requestInfor;
				/*$queueHistories = Yii::app()->session['queueHistories'];
				if (empty($queueHistories)) $queueHistories = array();
				if (count($queueHistories)) {
					$last = count($queueHistories);
					if (isset($queueHistories[$last]['url']) && $queueHistories[$last]['url'] != $requestInfor['url']) {
						$queueHistories[] = $requestInfor;
					}
				} else {
					$queueHistories[] = $requestInfor;
				}
				Yii::app()->session['queueHistories'] = $queueHistories;*/
			}
		} catch (Exception $ex) {}
	}
}