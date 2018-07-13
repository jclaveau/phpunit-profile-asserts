<?php
namespace JClaveau\PHPUnit\Framework\Constraint;
use                PHPUnit_Framework_Constraint as Constraint;
use                PHPUnit_Framework_TestCase   as TestCase;

abstract class TestCaseRelatedConstraint extends Constraint
{
    /**
     * @var TestCase
     */
    protected $testCase;

    public function __construct(TestCase $test_case)
    {
        parent::__construct();
        $this->testCase = $test_case;
    }
}
