<?php
namespace JClaveau\PHPUnit\Framework\Constraint;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\TestCase;

abstract class TestCaseRelatedConstraint extends Constraint
{
    /**
     * @var TestCase
     */
    protected $testCase;

    public function __construct(TestCase $test_case)
    {
        $this->testCase = $test_case;
    }
}
