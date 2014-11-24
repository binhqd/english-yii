<?php
class TestComment extends EnhancePHPTestFixture {
 
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
	 * countComments
	 **/
	
	public function test_count_comment()
	{
		//TODO : write later
		
	}
	
	/*
	 * dunghd 
	 * 17/03/2012
	 * Unit test function
	 * getComments
	 **/
	
	public function test_get_comments()
	{
		//TODO : write later
		
	}
	
	
	/*
	 * dunghd 
	 * 17/03/2012
	 * Unit test function
	 * countComments
	 **/
	
	public function test_write_comment()
	{
		// get 10 reviews
		$user = JLUser::model()->find('username = "dunghd"');		
		
		// find all business have been claimed
		$reviews = JLReview::model()->findAll(
			array(
				'limit' => 10, 
				'order' => 'created',
				'condition'	=> 'is_draft = 0'			
			)
		);
		
		$model = JLComment::model();
		$hasError = false;
		var_dump('write comment');
		foreach($reviews as $review)
		{			
			$token = 'review_' . IDHelper::uuidFromBinary($review->id);
			var_dump($token);
			$result = $model->write($user->id,$token,'write comment from unit test by ' . $user->username);
			if($result['error'])
			{
				$hasError = true;
				break;
			}
		}
		
		EnhancePHPAssert::isFalse($hasError);
	}
	
	
}
