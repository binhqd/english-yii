<?php
class TestReview extends EnhancePHPTestFixture {
 
    private $target;
    
    public function setUp() 
    {
       
    }
 
    public function tearDown() {
 
    }
	
	
	/*
	 * dunghd 
	 * 17/03/2012
	 * Unit test function
	 * getRecentReviews
	 **/
	
	public function test_get_review_of_dunghd()
	{
		/*
		$user = JLUser::model()->find('username = "dunghd"');		
		// get all review of user 'huytbt'
		$reviews = JLReview::model()->getRecentReviews($user->id);		   
		*/
	}	
	
	/*
	 * dunghd 
	 * 17/03/2012
	 * Unit test function
	 * getRecentReviews
	 **/
	public function test_save_draft()
	{		
		/*
		$user = JLUser::model()->find('username = "dunghd"');		
		
		// find all business have been claimed
		$business = JLBusiness::model()->findAll(
			array(
				'limit' => 10, 
				'order' => 'suburb_id'
			)
		);
		
		// write some draft review
		$model = JLReview::model();
		$hasError = false;
		foreach($business as $key => $biz)
		{			
			//echo  ' #' . $key . ' Test write review for :' . $biz->name . ' - Add ' . $biz->address . ' - Location ' . $biz->location ;		
			$result = $model->saveDraft($user->id,$biz->id,rand(1,5),'Unit test by user ' . $user->username);
			if($result['error'])
			{
				$hasError = true;
				break;
			}
			
		}
		
		EnhancePHPAssert::isFalse($hasError);
		*/
	}
 
	/*
	 * dunghd 
	 * 17/03/2012
	 * Unit test function
	 * deleteDraft
	 **/
	public function test_delete_draft()
	{		
		//Toto: write later
		
	}
 
 
	/*
	 * dunghd 
	 * 17/03/2012
	 * Unit test function
	 * updateHelpfuls
	 **/
	public function test_update_helpful()
	{		
		//Toto: write later
		
	}
	
	/*
	 * dunghd 
	 * 17/03/2012
	 * Unit test function
	 * countReview24h
	 **/
	public function test_count_review24h()
	{		
		//Toto: write later
		
	}
	
	
	/*
	 * dunghd 
	 * 17/03/2012
	 * Unit test function
	 * write
	 **/
	public function test_write_review()
	{		
		
		$user = JLUser::model()->find('username = "dunghd"');		
		
		// find all business have been claimed
		$business = JLBusiness::model()->findAll(
			array(
				'limit' => 2, 
				'order' => 'suburb_id'
			)
		);
		
		// write some draft review
		$model = JLReview::model();
		$hasError = false;
		foreach($business as $key => $biz)
		{			
			var_dump($biz->name);
			$result = $model->write($user->id,$biz->id,rand(1,5), date('m-d-Y H:i:s') . ' Unit test by user ' . $user->username);
			if($result['error'])
			{
				$hasError = true;
				break;
			}
			
		}
		
		EnhancePHPAssert::isFalse($hasError);		
		
	}
	
	
}
