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
        $this->sleep(0.5);
        $this->assertExecutionTimeBelow(0.6);

        try {
            $this->sleep(1);
            $this->assertExecutionTimeBelow(1);
        }
        catch (\Exception $e) {
            $this->assertRegExp(
                "/Failed asserting that 1 second\(s\) is longer than the test execution duration: \d+(\.\d+)? second\(s\)/",
                $e->getMessage()
            );
            return;
        }

        $this->assertFalse(true, 'An exception should have been thrown here');
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
     */
    public function test_getMemoryUsage()
    {
        $this->useMemory("1M");
        $this->assertEquals( 1024 * 1024, $this->getMemoryUsage() );
    }

    /**
     */
    public function test_getExecutionTime()
    {
        $this->sleep(1.2);
        $this->assertEquals(1.2, $this->getExecutionTime(), '', 0.01);
    }

    /**
     */
    public function test_getStopwatchEvent()
    {
        $this->assertInstanceOf( 'Symfony\Component\Stopwatch\StopwatchEvent', $this->getStopwatchEvent());
    }

    /**
     * @param float $seconds Number of additional seconds to execute code
     */
    private function sleep($seconds)
    {
        usleep($seconds * 1000 * 1000);
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
