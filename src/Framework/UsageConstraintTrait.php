<?php
namespace JClaveau\PHPUnit\Framework;
use       JClaveau\PHPUnit\Framework\Constraint\MemoryUsageBelow;
use       JClaveau\PHPUnit\Framework\Constraint\ExecutionTimeBelow;

/**
 * This traits provides the support of assertMemoryUsageBelow() and
 * assertExecutionTimeBelow() to PHPunit's TestCase
 */
trait UsageConstraintTrait
{
    public function assertMemoryUsageBelow($response, $message = '')
    {
        self::assertThat($response, new MemoryUsageBelow($this), $message);
    }

    public function assertExecutionTimeBelow($response, $message = '')
    {
        self::assertThat($response, new ExecutionTimeBelow($this), $message);
    }
}
