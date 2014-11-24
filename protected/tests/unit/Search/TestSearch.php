<?php
Yii::import('application.components.utils.Array2xml');
class TestSearch extends EnhancePHPTestFixture {
 
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
	 * findBusinessByCategory
	 **/
	public function test_find_business_by_category()
	{		
			
		
	}	
	
	/*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * findBusinessByName
	 **/
	public function test_find_business_by_name()
	{		
			
		
	}
	
	/*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * findListByBusiness
	 **/
	public function test_find_list_by_business()
	{		
			
		
	}
	
	/*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * searchBusiness
	 **/
	public function test_search_business_keyword_without_location()
	{	
		$pages			=	new CPagination();
		$pages->pageSize=	10;
		
		// Search keyword
		$keyword = 'hotel';
		
		$search = new SearchModel;
		$result = $search->searchBusiness($keyword,null,null,$pages);
		
		// log to file		
		$xml = new Array2xml('results','business');
		$xml->createNode( $result['matches'] );
		
		if(file_exists(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml'))		
			unlink(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml');		
		file_put_contents(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml',$xml);
		
	}
	
	/*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * searchBusiness
	 **/
	public function test_search_business_keyword_location()
	{	
		$pages			=	new CPagination();
		$pages->pageSize=	10;
		
		// Search keyword
		$keyword = 'hotel';
		$location = 'Sydney, NSW';
		$search = new SearchModel;
		$result = $search->searchBusiness($keyword,$location,null,$pages);
		
		// log to file		
		$xml = new Array2xml('results','business');
		$xml->createNode( $result['matches'] );
		
		if(file_exists(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml'))		
			unlink(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml');
		file_put_contents(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml',$xml);
	}
	
	/*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * searchBusinessNearByLocation
	 **/
	public function test_search_business_nearyby_location()
	{		
		// Search keyword
		$keyword = 'hotel';
		$location = 'Sydney, NSW';
		$search = new SearchModel;
		$result = $search->searchBusinessNearByLocation($keyword,$location);
		
		// log to file
		$xml = new Array2xml('results','business');
		$xml->createNode( $result['matches'] );
		
		if(file_exists(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml'))		
			unlink(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml');				
		file_put_contents(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml',$xml);
	}
	
	/*
	 * dunghd 
	 * 19/03/2012
	 * Unit test function
	 * searchBusinessNearByRegion
	 **/
	public function test_search_business_nearyby_region()
	{			
		// Search keyword
		$keyword = 'hotel';
		$location = '2500, NSW';
		$search = new SearchModel;
		$result = $search->searchBusinessNearByRegion($keyword,$location);
		
		// log to file
		$xml = new Array2xml('results','business');
		$xml->createNode( $result['matches'] );
		
		if(file_exists(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml'))		
			unlink(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml');				
		file_put_contents(dirname(__FILE__) . '/log/' . __FUNCTION__ . '.xml',$xml);	
		
	}
	
	
}
