<?php
namespace JClaveau\PHPUnit\Framework\Constraint;
use       JClaveau\PHPUnit\Listener\StopwatchListener;

/**
 * Checks that the duration of the current test case is shorter than a given
 * amount of seconds.
 */
class ExecutionTimeBelow extends TestCaseRelatedConstraint
{
    /**
     * @var $limit
     */
    protected $limit;

    public function __construct($test_case)
    {
        parent::__construct($test_case);

        if ( ! StopwatchListener::listens() ) {
            throw new \LogicException(
                "A StopwatchListener must be added to PHPUnit's configuration to enable assertMaxMemoryBelow"
            );
        }
    }

    /**
     * @param  mixed  $other Value or object to evaluate.
     * @param  string $description
     * @param  bool   $returnResult
     * @return bool
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        $this->limit = StopwatchListener::getTestDuration( $this->testCase->getName() );

        $success = $other > $this->limit;

        if ($returnResult) {
            return $success;
        }

        if (!$success) {
            $this->fail($other, $description);
        }
    }


    /**
     * @return string
     */
    public function toString()
    {
        return 'is longer than the test execution duration: ' . $this->limit;
    }

    /**/
}
