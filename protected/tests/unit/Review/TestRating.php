<?php
class TestRating extends EnhancePHPTestFixture {
 
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
	 * write
	 **/
	public function test_rate_business()
	{		
		
		$user = JLUser::model()->find('username = "dunghd"');		
		
		// find all business have been claimed
		$business = JLBusiness::model()->findAll(
			array(
				'limit' => 10, 
				'order' => 'suburb_id'
			)
		);
		
		// write some draft review
		$model = JLRating::model();
		$hasError = false;
		foreach($business as $key => $biz)
		{	
			$result = $model->rate($user->id,$biz->id,1,rand(1,5));
			if($result['error'])
			{
				$hasError = true;
				break;
			}
			
		}
		
		EnhancePHPAssert::isFalse($hasError);		
		
	}	
	
}
