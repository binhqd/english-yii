<?php
class SomeOtherType
{
    public $value;
}

class AssertIsNotInstanceOfTypeTestFixture extends EnhancePHPTestFixture
{
    /** @var EnhancePHPAssertions $target */
    private $target;
    
    public function setUp()
    {
        $this->target = EnhancePHPCore::getCodeCoverageWrapper('EnhancePHPAssertions', array(EnhancePHPLanguage::English));
    }

    public function assertIsNotInstanceOfTypeWithDifferentType()
    {
        $object = new SomeOtherType();
        $this->target->isNotInstanceOfType('SomeType', $object);
    }
    
    public function assertIsNotInstanceOfTypeWithIdenticalTypes()
    {
        $verifyFailed = false;
        $object = new SomeOtherType();
        try {
            $this->target->isNotInstanceOfType('SomeOtherType', $object);
        } catch (Exception $e) {
            $verifyFailed = true;
        }
        EnhancePHPAssert::isTrue($verifyFailed);
    }
}
?>