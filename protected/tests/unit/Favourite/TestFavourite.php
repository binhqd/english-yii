<?php
Yii::import('application.components.utils.Array2xml');
class TestFavourite extends EnhancePHPTestFixture {
 
    private $target;
    
    public function setUp() 
    {
    }
 
    public function tearDown() {
 
    }
    
    /*
	 * dunghd 
	 * 27/03/2012
	 * Unit test function
	 * add the favourite
	 **/
	public function test_add_favourite()
	{	
		
		$user = JLUser::model()->find('username = "huytbt"');		
		
		// find all business have been claimed
		$business = JLBusiness::model()->findAll(
			array(
				'limit' => 12, 
				'order' => 'suburb_id'
			)
		);
		
		// add some fav		
		Yii::import('application.modules.favourites.models.*');
		$hasError = false;
		foreach($business as $key => $biz)
		{			
			$model = new JLFavourites;
			//echo  ' #' . $key . ' Test write review for :' . $biz->name . ' - Add ' . $biz->address . ' - Location ' . $biz->location ;		
			$model->user_id = $user->id;
			$model->business_id = $biz->id;
			$model->business_name = $biz->name;
			$model->tag = $biz->name . ',' . implode(',',$biz->getJLCategories()) ;
			$model->note = $biz->name;
			$model->created_date = time();
			$model->modified_date = time();
			var_dump($model);
			if($model->validate())
			{
				if(! $model->save() )
				{
					var_dump($model->getErrors());
					$hasError = true;
					break;
				}	
			}
			else
			{
				var_dump($model->getErrors());
				$hasError = true;
				break;
			}					
			
		}
		
		EnhancePHPAssert::isFalse($hasError);		
		
	}	
	
	/*
	 * dunghd 
	 * 27/03/2012
	 * Unit test function
	 * add_favourite_with_reviewbiz
	 **/
	public function test_remove_allbiz_fav()
	{		
		/*
		Yii::import('application.modules.favourites.models.*');
		$favourite = JLFavourites::model()->findAll();
		$hasError = false;
		// log to file	
		foreach($favourite as $fav)
		{			
			if(! $fav->delete() )
			{
				var_dump($favourite->getErrors());
				$hasError = true;
				break;
			}
		}
		
		EnhancePHPAssert::isFalse($hasError);		
		*/
	}


	/*
	 * dunghd 
	 * 27/03/2012
	 * Unit test function
	 * test_get_allbiz_fav
	 **/
	public function test_get_allbiz_fav()
	{		
		/*
		$user = JLUser::model()->find('username = "huytbt"');	
		Yii::import('application.modules.favourites.models.*');
		$favourite = JLFavourites::model()->getListFavourites(array(),$user->id);
		var_dump($favourite);		
		$favourite = JLFavourites::model()->getListFavourites(array('name' => 'Sydney'),$user->id);
		var_dump($favourite);				
		$favourite = JLFavourites::model()->getListFavourites(array('name' => 'Sydney','order' => 'created DESC'),$user->id);
		*/
	}
	
	/*
	 * dunghd 
	 * 28/03/2012
	 * Unit test function
	 * test_get_alltag_fav
	 **/
	public function test_get_alltag_fav()
	{		
		/*
		$user = JLUser::model()->find('username = "huytbt"');	
		Yii::import('application.modules.favourites.models.*');
		$tags= JLFavourites::model()->getAllTagsOfUser($user->id);
		var_dump($tags);
		
		$business = JLBusiness::model()->findAll(
			array(
				'limit' => 5, 
				'order' => 'suburb_id'
			)
		);
		
		$bizs = array();
		
		foreach($business as $biz)
		{
			$bizs[] = $biz->id;
		}
		
		$tag = JLFavourites::model()->getRelateTagsOfUser($user->id,$bizs,array('Dial A Chevy'));
		var_dump($tag);
		*/		
	}
	
	
	
	/*
	 * dunghd 
	 * 27/03/2012
	 * Unit test function
	 * add_favourite_with_reviewbiz
	 **/
	public function test_add_favourite_with_reviewbiz()
	{	
		/*
		$user = JLUser::model()->find('username = "huytbt"');		
		
		// find all business have been claimed
		$business = JLReview::model()->getRecentReviews($user->id,'created DESC',20);
		
		// add some fav		
		Yii::import('application.modules.favourites.models.*');
		Yii::import('application.modules.businesses.models.*');
		$hasError = false;
		// log to file		
				
		foreach($business as $key => $review)
		{						
			foreach($review as $rev)
			{
				$biz = $rev->business;		
				$model = new JLFavourites;
				//echo  ' #' . $key . ' Test write review for :' . $biz->name . ' - Add ' . $biz->address . ' - Location ' . $biz->location ;		
				$model->user_id = $user->id;
				$model->business_id = $biz->id;
				$model->business_name = $biz->name;
				$model->tag = $biz->name;
				$model->note = $biz->name;
				$model->created_date = time();
				$model->modified_date = time();
				//var_dump($model);
				if($model->validate())
				{
					if(! $model->save() )
					{
						var_dump($model->getErrors());
						$hasError = true;
						break;
					}	
				}
				else
				{
					var_dump($model->getErrors());
					$hasError = true;
					break;
				}
				break;		
			}						
			
		}
		
		EnhancePHPAssert::isFalse($hasError);		
		*/
		
	}

	
	
}
