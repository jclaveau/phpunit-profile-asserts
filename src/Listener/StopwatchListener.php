<?php
namespace JClaveau\PHPUnit\Listener;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Warning;

use Symfony\Component\Stopwatch\Stopwatch;

/**
 * A PHPUnit TestListener that registers duration and time using the
 * Symfony's Stopwatch component.
 *
 * @see https://symfony.com/doc/current/components/stopwatch.html
 */
class StopwatchListener implements TestListener
{
    /**
     * @var Stopwatch
     */
    protected static $stopwatch;

    /**
     * @var StopwatchListener
     */
    protected static $instance;

    /**
     * @var array
     */
    protected static $events = [];

    /**
     * @var array
     */
    protected static $initialMemory = [];

    public function __construct(array $options = [])
    {
        if (self::$instance !== null) {
            throw new \LogicException(
                "Only one " . __CLASS__ . " can be configured"
            );
        }

        self::$instance = $this;

        self::$stopwatch = new Stopwatch(true);
    }

    public static function listens(): bool
    {
        return self::$instance !== null;
    }

    public function startTest(Test $test): void
    {
        self::$events[ $test->getName() ] = self::$stopwatch->start($test->getName());
        self::$initialMemory[ $test->getName() ] = self::$events[ $test->getName() ]->lap()->getMemory();
    }

    public function endTest(Test $test, float $time): void
    {
        self::$stopwatch->stop($test->getName());
    }

    public static function getTestMemory($name)
    {
        return self::getTestStopwatchEvent($name)->lap()->getMemory() - self::$initialMemory[ $name ];
    }

    public static function getTestDuration($name)
    {
        return self::getTestStopwatchEvent($name)->lap()->getDuration() / 1000;
    }

    public static function getTestStopwatchEvent($name)
    {
        if (!isset(self::$events[$name])) {
            throw new \LogicException(
                "Trying to retrieve a Stopwatch event of a test which has not been run: "
                . $name
            );
        }

        return self::$events[$name];
    }

    // Required by TestListener interface but not used
    public function addError(Test $test, \Throwable $t, float $time): void
    {
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
    }

    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
    }

    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
    }

    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
    }

    public function startTestSuite(TestSuite $suite): void
    {
    }

    public function endTestSuite(TestSuite $suite): void
    {
    }
}
