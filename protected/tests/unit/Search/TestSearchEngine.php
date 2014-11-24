<?php
class TestSearchEngine extends EnhancePHPTestFixture {
 
    private $target;
    
    public function setUp() 
    {
       
    }
 
    public function tearDown() {
 
    }
    
    /*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * getCategoryMapping
	 **/
	public function test_get_category_mapping()
	{		
		// Search keyword
		/*
		$cat = 'hotel';
		$biz = 'cafes';
		
		$search = new SearchEngine;
		$result = $search->getCategoryMapping($biz,$cat);
		
		// log to file		
		$xml = new Array2xml('results','category');
		$xml->createNode( $result['matches'] );
				
		file_put_contents(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml',$xml);	
		*/
	}
    
    
    /*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * checkAvalabilityBiz
	 **/
	public function test_check_available_biz()
	{		
		
	}	
	
	
	/*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * suggestCategory
	 **/
	public function test_suggest_category()
	{		
		// Search keyword
		$cat = 'hote';
		
		$search = new SearchEngine;
		$result = $search->suggestCategory($cat);
		
		// log to file		
		$xml = new Array2xml();
		$xml->createNode(  $result );
		
		if(file_exists(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml'))		
			unlink(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml');
		file_put_contents(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml',$xml);		
		
	}
	
	/*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * suggestKeyword
	 **/
	public function test_suggest_keyword()
	{		
		// Search keyword
		$keyword = 'Hotel';
		
		$search = new SearchEngine;
		$result = $search->suggestKeyword($keyword);
		
		// log to file		
		$xml = new Array2xml();
		$xml->createNode(  $result );
		
		if(file_exists(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml'))		
			unlink(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml');		
		file_put_contents(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml',$xml);		
		
	}
	
	/*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * suggestLocation
	 **/
	public function test_suggest_location()
	{		
		// Search keyword
		$location = 'Woll';
		
		$search = new SearchEngine;
		$result = $search->suggestLocation($location);
		
		// log to file		
		$xml = new Array2xml();
		$xml->createNode(  $result );
		
		if(file_exists(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml'))		
			unlink(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml');		
		file_put_contents(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml',$xml);		
		
	}
	
	
	/*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * getBizInfo
	 **/
	public function test_get_biz_info()
	{		
			
		
	}
	
	
	/*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * getAllBizOfOwner
	 **/
	public function test_get_all_biz_info()
	{					
		
	}
}
