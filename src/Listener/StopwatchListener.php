<?php
namespace JClaveau\PHPUnit\Listener;
use                PHPUnit_Framework_Test as Test;
use                PHPUnit_Framework_TestListener as TestListener;
use                PHPUnit_Framework_TestSuite as TestSuite;
use                PHPUnit_Framework_AssertionFailedError as AssertionFailedError;
use                PHPUnit_Framework_Warning as Warning;

use       Symfony\Component\Stopwatch\Stopwatch;

/**
 * A PHPUnit TestListener that registers duration and time using the
 * Symfony's Stopwatch component.
 *
 * @see https://symfony.com/doc/current/components/stopwatch.html
 */
class StopwatchListener implements TestListener
{
    use TestListenerDefaultImplementation;

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
        // $this->loadOptions($options);
    }

    public static function listens()
    {
        return self::$instance !== null;
    }

    public function startTest(Test $test)
    {
        self::$events[ $test->getName() ] = self::$stopwatch->start($test->getName());
        self::$initialMemory[ $test->getName() ] = self::$events[ $test->getName() ]->lap()->getMemory();
    }

    /**
     * A test ended.
     *
     * @param Test  $test
     * @param float $time
     */
    public function endTest(Test $test, $time)
    {
        self::$stopwatch->stop($test->getName());
    }

    /**
     */
    public static function getTestMemory($name)
    {
        return self::getTestStopwatchEvent($name)->lap()->getMemory() - self::$initialMemory[ $name ];
    }

    /**
     */
    public static function getTestDuration($name)
    {
        return self::getTestStopwatchEvent($name)->lap()->getDuration() / 1000;
    }

    /**
     */
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

    /**/
}

trait TestListenerDefaultImplementation
{
    public function addError(Test $test, \Exception $t, $time)
    {
    }
    public function addWarning(Test $test, Warning $e, $time)
    {
    }
    public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
    }
    public function addIncompleteTest(Test $test, \Exception $t, $time)
    {
    }
    public function addRiskyTest(Test $test, \Exception $t, $time)
    {
    }
    public function addSkippedTest(Test $test, \Exception $t, $time)
    {
    }
    public function startTestSuite(TestSuite $suite)
    {
    }
    public function endTestSuite(TestSuite $suite)
    {
    }
    public function startTest(Test $test)
    {
    }
    public function endTest(Test $test, $time)
    {
    }
}
