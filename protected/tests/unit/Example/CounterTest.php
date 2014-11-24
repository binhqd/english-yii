<?php
class CounterTest extends EnhancePHPTestFixture {
 
    private $target;
    
    public function setUp() 
    {
        $this->target = EnhancePHPCore::getCodeCoverageWrapper('Counter');
    }
 
    public function tearDown() {
 
    }
    
    public function test_count()
    {	
		$counter_mock = EnhancePHPMockFactory::createMock("Counter");
		$counter_mock->addExpectation( EnhancePHPExpect::method('increment')->returns(10));
		
		$isVerify = true;
		
		try {
			$counter_mock->verifyExpectations();	
		} 
		catch(Exception $e) {
			$isVerify = false;
		}
		
		EnhancePHPAssert::isTrue($isVerify);
		
	}
 
}
