<?php
namespace JClaveau\PHPUnit\Framework;
use       JClaveau\PHPUnit\Framework\Constraint\MemoryUsageBelow;
use       JClaveau\PHPUnit\Framework\Constraint\ExecutionTimeBelow;
use       JClaveau\PHPUnit\Listener\StopwatchListener;

/**
 * This traits provides the support of assertMemoryUsageBelow() and
 * assertExecutionTimeBelow() to PHPunit's TestCase
 */
trait UsageConstraintTrait
{
    protected function assertMemoryUsageBelow($response, $message = '')
    {
        self::assertThat($response, new MemoryUsageBelow($this), $message);
    }

    protected function assertExecutionTimeBelow($response, $message = '')
    {
        self::assertThat($response, new ExecutionTimeBelow($this), $message);
    }

    protected function getMemoryUsage()
    {
        return StopwatchListener::getTestMemory( $this->getName() );
    }

    protected function getExecutionTime()
    {
        return StopwatchListener::getTestDuration( $this->getName() );
    }

    protected function getStopwatchEvent()
    {
        return StopwatchListener::getTestStopwatchEvent( $this->getName() );
    }
}
