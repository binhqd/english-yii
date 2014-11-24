<?php

class ApiController extends CController
{
	// Members
	/**
	 * Key which has to be in HTTP USERNAME and PASSWORD headers 
	 */
	Const APPLICATION_ID = 'ASCCPE';
 
	/**
	 * Default response format
	 * either 'json' or 'xml'
	 */
	private $format = 'json';
	/**
	 * @return array action filters
	 */
	public function filters()
	{
			return array();
	}
	public function actionGetPhotos($uuid=NULL){
		if(!empty($uuid)){
			Yii::import('application.modules.photo.models.*');
			Yii::import('application.modules.photo.models.JLContributePhoto');
			$strBizID = str_replace("-", "", $uuid);
			$topPhotos = JLContributePhoto::model()->getAvatars($strBizID, array(), 30, 0);
			$thumbs = null;
			if (!empty($topPhotos)) {
				foreach($topPhotos as $key=>$photo){
					$thumbs[] = $photo->metadata['info']['basename'];
				}
			}
			
			jsonOut(array(
				'error'=>false,
				'msg'=>'',
				'source'=>$thumbs
			));
		}else{
			jsonOut(array('error'=>true,'msg'=>'UUID undefined'));
		}
	}
	/**
		Get 32 Categories (JustLook)
	*/
	public function actionCategories(){
		$arrCategory = JLCategory::model()->getJLCategories();
		jsonOut($arrCategory);
	}
	public function actionCreateBusiness(){
		if(!empty($_POST)){
			$modelBusiness = new JLBusiness;
			$attributes = $_POST['Register'];
			$modelBusiness->name = $attributes['name'];
			$modelBusiness->address = $attributes['address'];
			$modelBusiness->latitude = $attributes['latitude'];
			$modelBusiness->longitude = $attributes['longtiude'];
			$modelBusiness->landline = $attributes['landline'];
			$modelBusiness->email = $attributes['email'];
			if($modelBusiness->validate()){
				if($modelBusiness->save()){
					$array			=	array(
						'name'		=>  $modelBusiness->name,
						'address'	=>  $modelBusiness->address,
						'landline'=>  $modelBusiness->landline,
						'email'		=>  $modelBusiness->email,
						'latitude'=> $modelBusiness->latitude,
						'longitude'=> $modelBusiness->longitude,
					);
					try{
						JLbusiness::model()->saveSphinx(IDHelper::uuidFromBinary($modelBusiness->id),$array,$modelBusiness);
					}catch(Exception $e){
						jsonOut(array(
							'message'=>$e->getMessage(),
							'error'=>true
						));
					}
					jsonOut(array(
						'message'=>'The has been save business',
						'eroor'=>false,
						'type'=>'success',
					));
				}else{
					$errors = $model->getErrors();
					list ($field, $_errors) = each ($errors);
					jsonOut(
						array(
							'message'=>'Can\'t save  business. ' .$_errors[0],
							'type'=>'error',
							'data'=>array(),
							'error'=>true,
						)
					);
				}
			}else{	
				$errors = $model->getErrors();
				list ($field, $_errors) = each ($errors);
				jsonOut(
					array(
						'message'=>'Businesses can\'t validate. ' .$_errors[0],
						'type'=>'error',
						'data'=>array(),
						'error'=>true,
					)
				);
			}
		}else{
			jsonOut(array(
				'error'=>true,
				'message'=>'Request bad.'
			));
		}
	}
	/**
	Nguoi viet : thinhpq
	Ham duoc dung de ho tro EasyWeb tra ve thong tin 1 doanh nghiep
	**/
	public function actionBizInfo($uuid=NULL,$callback=NULL){
		if(!empty($uuid)){
			$uuid = strtolower(str_replace("-","",$uuid));
			Yii::import('application.modules.businesses.models.*');
			Yii::import('application.models.SearchEngine');
			$modelSearchEngine	=	new SearchEngine;
			$model				=	$modelSearchEngine->getBizInfo($uuid);
			if($model===null ){
				$arrBusiness['error']	=	NULL;
				$arrBusiness['bizinfo']	=	NULL;
				$this->_sendResponse(200, CJSON::encode(
					$arrBusiness
				),'application/json');
			}else{
				$user = currentUser();
				$owner_id = strtolower(str_replace("-","",$model['owner_id']));
				$strOwnerId = IDHelper::uuidFromBinary($user->id,true);
				$error = NULL;
				if($user->id==-1) $error = 1;
				if($owner_id!=$strOwnerId) $error = 2;
				if($user->id!=-1 && $owner_id==$strOwnerId) $error = 0;
				
				$arrBusiness = array();
				$arrBusiness['error']	=	$error;
				$arrBusiness['bizinfo']['id']	=	str_replace('-','',$model['uuid']);
				$arrBusiness['bizinfo']['name']	=	$model['name'];
				$arrBusiness['bizinfo']['description']	=	!empty($model['description'])?$model['description']:'';
				$categories				=	explode('|',$model['jl_categories']);
				$bizcategories			=	explode('|',$model['categories']);
				$arrCat = array_merge($categories,$bizcategories);
				$arrCategory	=	array();
				foreach($arrCat as $key=>$value){
					$arrCategory[] = trim($value);
				}
				$arrCat = array_unique($arrCategory);
				sort($arrCat);
				$arrBusiness['bizinfo']['category']	=	$arrCat;
				$keyword = explode(", ",$model['keywords']);				
				$arrBusiness['bizinfo']['keywords']		=	$keyword;
				$address = $model['address'];
				$location = $model['location'];

				$arrBusiness['bizinfo']['location']		=	$location;
				$arrBusiness['bizinfo']['address']		=	$address;
				$arrBusiness['bizinfo']['ownerid']		=	$owner_id;
				$thumbs = array();
				if(!empty($_GET['photo'])){
					Yii::import('application.modules.photo.models.*');
					Yii::import('application.modules.photo.models.JLContributePhoto');
					$strBizID = str_replace('-','',$model['uuid']);
					$topPhotos = JLContributePhoto::model()->getAvatars($strBizID, array(), 30, 0);
					if (!empty($topPhotos)) {
						foreach($topPhotos as $key=>$photo){
							$thumbs[] = $photo->metadata['info']['basename'];
						}
					}
				}
				if(!empty($thumbs)){
					$arrBusiness['bizinfo']['photo']		=	$thumbs;
				}
				
				$cb = false;
					$reg = '/^[a-z_]+[_a-z0-9]*(\.[a-z_]+[_a-z0-9])*$/i';
					if(preg_match($reg, $callback)){
					$cb = $callback;
				}
				header('Content-type: application/json');
				echo ($cb?$cb.'(':'').CJSON::encode($arrBusiness).($cb?');':'');
			}
		}else{
			$this->_sendResponse(200, CJSON::encode(
				array(
					'msg'=>'Api bad request.',
					'error'=>true
				)
			),'application/json');
		}
	}
  // Actions
	public function actionSearch($viewmode = 'compact',$sortby = 'Justlook')
	{
		//$this->_checkAuth();		
		// Get the respective model instance
		if(! isset($_GET['type']) )
		{
			return $this->_sendResponse(200, @CJSON::encode(array('error' => 'Error: API is not implemented for model')),'application/json');			
		}
		switch($_GET['type'])
		{
			case 'business':
				//debug(jsonOut($_GET));
				Yii::import('application.models.SearchModel');
				
				$keyword = Yii::app()->request->getParam('keyword');
				$location = Yii::app()->request->getParam('location');
				$sort = Yii::app()->request->getParam('sortby');
				
				$keyword = str_replace(' : ',':',$keyword);
        		$keyword = str_replace('category:','] [category=',$keyword);
        		$keyword = str_replace('keyword:','] [keyword=',$keyword);
        		$keyword = '[word=' . $keyword . ' ]';
        		// usage : 
        		// bizname category : arg1 arg2 keyword : arg3 par4
        		$word = "";
        		$cat = "";
        		$key = "";
        		if($keyword != null)
        		{
        			preg_match('/\[word=([^]]+)\]/i',$keyword,$matches);
        			if(count($matches))
        				$word = trim($matches[1]);
        			preg_match('/\[category=([^]]+)\]/i',$keyword,$matches);
        			if(count($matches))
        				$cat = trim($matches[1]);
        			preg_match('/\[keyword=([^]]+)\]/i',$keyword,$matches);
        			if(count($matches))
        				$key = trim($matches[1]);
        		}
        		
        		if( $location == '')
        		{	
        			$location = 'Australia';
        		}
                
                //Truy vấn DB với 2 đối số là KEYWORD và LOCATION
        		$modelSearch = new SearchModel;
        		$keyword = trim($keyword);
                $word = trim($word);
        		$location = trim($location);
                
				$result = array();
                //Khai báo các biển or mảng ....
        		$arrBusiness	=	NULL;
        		$pages			=	new CPagination();
        		$pages->pageSize=	10;
                
                if( strtolower($location) == 'australia' && $word == '')
    			{
    				$arrBusiness = array();
    			} else            
    			if( strtolower($location) == 'australia' )
    			{
    				$response		=	$modelSearch->searchBusiness($word,'',$sortby,$pages,array('key' => $key,'category' => $cat));
    			}
    			else
    			{
    				$response		=	$modelSearch->searchBusiness($word,$location,$sortby,$pages,array('key' => $key,'category' => $cat));
    			}
                
                $arrBusiness	=	$response['matches'];
				if(count($arrBusiness))
				{		
					//$nearbyBusiness = $modelSearch->searchBusinessNearByRegion($keyword,$location);		
					Yii::import('application.modules.reviews.models.JLRating');
					$userRates = array();
					//$countReviews = array();
					
					$ratingModel = JLRating::model();
					$searchEngine = new SearchEngine;	
					$items = array();
					foreach ($arrBusiness as $index => $biz)
					{
						$binBizID = IDHelper::uuidToBinary($biz['attrs']['uuid']);
						$userRates = $ratingModel->getRating(Yii::app()->user->id, $binBizID, JLRating::RATE_FOR_BUSINESS);
						
                        if($biz['attrs']['position'])
    					{
    						$search = Yii::app()->search;
    						$search->select('*')->from('biz')
    						->where('@uuid '.$biz['attrs']['uuid'])
    						->limit(0, 1);
    						$bizInfo	=	$search->searchRaw();
    						if( count($bizInfo['matches']) )
    						{
    							$arrValue = array();
    							foreach($arrBusiness[$index]['attrs'] as $key => $value)
    							{
    								if($value != '' && $key != 'owner_id')
    									$arrValue[$key] = $value;
    							}						
    							
    							$arrBusiness[$index]['attrs'] = CMap::mergeArray(
    								$bizInfo['matches'][key($bizInfo['matches'])]['attrs'],$arrValue
    							);
    							/*
    							dump(CMap::mergeArray(
    								$bizInfo['matches'][key($bizInfo['matches'])]['attrs'],$arrValue
    							)) ;
    							*/
    						}
    						
    					}
                        
						if(empty($userRates))
							$biz['attrs']['currentUserRate'] = 0;
						else
							$biz['attrs']['currentUserRate'] = $userRates->rate;
						
						//$review = $searchEngine->getLastestReview($biz['attrs']['uuid']);
						
						$countYourReviews = 0;
						if (!currentUser()->isGuest) {
							$your = currentUser();
							$your->attachBehavior('UserReview', 'application.modules.reviews.components.behaviors.JLUserReviewBehavior');
							$countYourReviews = $your->countReviewsByBiz($binBizID);
						}
                        
						$biz['attrs']['yourreviews'] = $countYourReviews;
						
						$items[] = array(
							'business' => $biz['attrs'],
							//'last_review' => $review,
							//'currentUserRate'
						);
						
					}					
					$result = array(
						'bizs' => $items,
						'pages' => array(
							'currentPage' => $pages->currentPage,
							'itemCount' => $pages->itemCount,
							'limit' => $pages->limit,
							'offset' => $pages->offset,
							'pageCount' => $pages->pageCount,
							'pageSize' => $pages->pageSize,
							'pageVar' => $pages->pageVar,
							'params' => $pages->params,
							'route' => $pages->route,
						),
						'viewmode' => $viewmode,
					);
				}	
				else
				{
					$result = array(
						'bizs' => array(),
						'pages' => array(
							'currentPage' => $pages->currentPage,
							'itemCount' => $pages->itemCount,
							'limit' => $pages->limit,
							'offset' => $pages->offset,
							'pageCount' => $pages->pageCount,
							'pageSize' => $pages->pageSize,
							'pageVar' => $pages->pageVar,
							'params' => $pages->params,
							'route' => $pages->route,
						)
					);
				}
				break;
			default:
				// Model not implemented error
				$this->_sendResponse(501, sprintf(
					'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
					$_GET['model']) );
				exit;
		}
		// Did we get some results?
		if(count($result)) {
			// Send the response			
			if($this->format == 'json' )
				$this->_sendResponse(200, @CJSON::encode($result),'application/json');			
			else
			$this->_sendResponse(200, @CJSON::encode($result));
		} else {
			// No
			//$this->_sendResponse(200, sprintf('No items where found for model <b>%s</b>', $_GET['type']) );
			$this->_sendResponse(200, @CJSON::encode($result),'application/json');			
		}
		
	}
	
	
	
	private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
	{
		// set the status
		$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
		header($status_header);
		// and the content type
		header('Content-type: ' . $content_type);
	 
		// pages with body are easy
		if($body != '')
		{
			// send the body
			echo $body;
			exit;
		}
		// we need to create the body if none is passed
		else
		{
			// create some body messages
			$message = '';
	 
			// this is purely optional, but makes the pages a little nicer to read
			// for your users.  Since you won't likely send a lot of different status codes,
			// this also shouldn't be too ponderous to maintain
			switch($status)
			{
				case 401:
					$message = 'You must be authorized to view this page.';
					break;
				case 404:
					$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
					break;
				case 500:
					$message = 'The server encountered an error processing your request.';
					break;
				case 501:
					$message = 'The requested method is not implemented.';
					break;
			}
	 
			// servers don't always have a signature turned on 
			// (this is an apache directive "ServerSignature On")
			$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
	 
			// this should be templated in a real-world solution
			$body = '
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
	</head>
	<body>
		<h1>' . $this->_getStatusCodeMessage($status) . '</h1>
		<p>' . $message . '</p>
		<hr />
		<address>' . $signature . '</address>
	</body>
	</html>';
	 
			echo $body;
			exit;
		}
	}
	
	private function _getStatusCodeMessage($status)
	{
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
			200 => 'OK',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
		);
		return (isset($codes[$status])) ? $codes[$status] : '';
	}
	
	private function _checkAuth()
	{
		// Check if we have the USERNAME and PASSWORD HTTP headers set?
		if(!(isset($_SERVER['HTTP_X_USERNAME']) and isset($_SERVER['HTTP_X_PASSWORD']))) {
			// Error: Unauthorized
			$this->_sendResponse(401);
		}
		$username = $_SERVER['HTTP_X_USERNAME'];
		$password = $_SERVER['HTTP_X_PASSWORD'];
		// Find the user
		$user=User::model()->find('LOWER(username)=?',array(strtolower($username)));
		if($user===null) {
			// Error: Unauthorized
			$this->_sendResponse(401, 'Error: User Name is invalid');
		} else if(!$user->validatePassword($password)) {
			// Error: Unauthorized
			$this->_sendResponse(401, 'Error: User Password is invalid');
		}
	}
}
