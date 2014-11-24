<?php
class TestCompliment extends EnhancePHPTestFixture {
 
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
	 * getCompliments
	 **/
	 
	public function test_get_compliment()
	{
		//TODO : write later
	}
	
	
	/*
	 * dunghd 
	 * 17/03/2012
	 * Unit test function
	 * writeComplement
	 **/
	
	public function test_write_compliment()
	{
		// get 10 reviews
		$user = JLUser::model()->find('username = "huytbt"');		
		
		// find all business have been claimed
		$reviews = JLReview::model()->findAll(
			array(
				'limit' => 10, 
				'order' => 'created',
				'condition'	=> 'is_draft = 0'			
			)
		);
		
		$model = JLCompliment::model();
		$hasError = false;
		var_dump("write compliment");
		foreach($reviews as $review)
		{			
			$token = 'review_' . IDHelper::uuidFromBinary($review->id);
			var_dump($token);
			$result = $model->write($user->id,$token,'write compliment from unit test by ' . $user->username);
			if($result['error'])
			{
				$hasError = true;
				break;
			}
		}
		
		EnhancePHPAssert::isFalse($hasError);
	}
	
}
