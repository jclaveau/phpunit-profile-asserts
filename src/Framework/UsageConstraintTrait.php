<?php
namespace JClaveau\PHPUnit\Framework;
use       JClaveau\PHPUnit\Framework\Constraint\MemoryUsageBelow;
use       JClaveau\PHPUnit\Framework\Constraint\ExecutionTimeShorter;

/**
 * This traits provides the support of assertMemoryUsageBelow() and
 * assertExecutionTimeShorter() to PHPunit's TestCase
 */
trait UsageConstraintTrait
{
    public function assertMemoryUsageBelow($response, $message = '')
    {
        self::assertThat($response, new MemoryUsageBelow($this), $message);
    }

    public function assertExecutionTimeShorter($response, $message = '')
    {
        self::assertThat($response, new ExecutionTimeShorter($this), $message);
    }
}
