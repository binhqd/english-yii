<?php
class TestScenerios extends EnhancePHPTestFixture {
 
    private $target;
    
    public function setUp() 
    {
        $this->target = EnhancePHPCore::getCodeCoverageWrapper('ScenarioExampleClass');
    }
 
    public function tearDown() {
 
    }
	
	public function test_screnario()
	{
		$scenario = EnhancePHPCore::getScenario($this->target, 'addTwoNumbers');
        $scenario->with(1, 2)->expect(3);
        $scenario->with(3, 4)->expect(7);
        $scenario->with(3, -4)->expect(-1);
        $scenario->with(-3, -4)->expect(-7);
        $scenario->with(3.14, 4.14)->expect(7.28);
        $scenario->with(3.14, 4.12)->expect(7.26);
        $scenario->VerifyExpectations();
	}
	
    public function score_home_calls_increment () {
			$home_counter_mock = EnhancePHPMockFactory::createMock("Counter");
			$away_counter = new Counter();
		 
			$home_counter_mock->addExpectation( EnhancePHPExpect::method('increment') );
		 
			$scoreboard = new Scoreboard($home_counter_mock, $away_counter);
			$scoreboard->score_home();
		 
			$home_counter_mock->verifyExpectations();
	  }
  
	   public function score_away_calls_increment () {
		$home_counter_stub = EnhancePHPStubFactory::createStub("Counter");
		$away_counter = new Counter();
	 
		$home_counter_stub->addExpectation( EnhancePHPExpect::method('increment')->returns(10) );
	 
		$scoreboard = new Scoreboard($home_counter_stub, $away_counter);
		$result = $scoreboard->score_home();
	 
		EnhancePHPAssert::areIdentical($result, 10);
	 
	  }
 
}
