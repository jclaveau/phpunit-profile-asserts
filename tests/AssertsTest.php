<?php
namespace JClaveau\PHPUnit\Framework;
use       JClaveau\PHPUnit\Framework\Constraint\MemoryUsageBelow;
use       JClaveau\PHPUnit\Listener\StopwatchListener;
use                PHPUnit_Framework_TestCase as TestCase;

class AssertsTest extends TestCase
{
    use UsageConstraintTrait;

    /**
     */
    public function test_assertExecutionTimeExceeded()
    {
        $this->extendTime(50);
        $this->assertExecutionTimeShorter(100);

        try {
            $this->extendTime(300);
            $this->assertExecutionTimeShorter(100);
        }
        catch (\Exception $e) {
            $this->assertRegExp(
                "/Failed asserting that 100 is longer than the test execution duration: \d+/",
                $e->getMessage()
            );
        }
    }

    /**
     */
    public function test_assertMemoryUsageExceeded()
    {
        $this->useMemory("1M");
        $this->assertMemoryUsageBelow('2M');

        try {
            $this->useMemory("5M");
            $this->assertMemoryUsageBelow('1M');
        }
        catch (\Exception $e) {
            $this->assertRegExp(
                "/Failed asserting that '1M' memory limit has not been passed by \d+/",
                $e->getMessage()
            );
        }
    }

    /**
     * @param int $ms Number of additional microseconds to execute code
     */
    private function extendTime($ms)
    {
        usleep($ms * 1000);
    }

    private $usedMemory;

    /**
     * @param string|integer $bytes Amout of memory to consume in ko.
     */
    private function useMemory($bytes)
    {
        $bytes = MemoryUsageBelow::bytesToInt($bytes);

        $this->usedMemory = '';

        $before = $after = memory_get_usage();
        while ($after - $before < $bytes) {
            $this->usedMemory .= 'aaaaaaaa';
            $after = memory_get_usage();
        }

        // var_dump($after - $before);
    }
}
