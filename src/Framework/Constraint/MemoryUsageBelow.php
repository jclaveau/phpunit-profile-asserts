<?php
namespace JClaveau\PHPUnit\Framework\Constraint;
use       JClaveau\PHPUnit\Listener\StopwatchListener;

/**
 * Checks that the memory used during the current test case is below
 * a given value.
 */
class MemoryUsageBelow extends TestCaseRelatedConstraint
{
    /**
     * @var int $usedMemory
     */
    protected $usedMemory;

    public function __construct($test_case)
    {
        parent::__construct($test_case);

        if ( ! StopwatchListener::listens()) {
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
        $this->usedMemory = StopwatchListener::getTestMemory( $this->testCase->getName() );
        // var_dump($this->usedMemory);
        // var_dump($this::bytesToInt($other));

        $success = $this::bytesToInt($other) > $this->usedMemory;

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
        return 'memory limit has not been passed by '.$this->usedMemory;
    }

    /**
     * @param  decimal|string
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    public static function bytesToInt($byteString)
    {
        if (is_numeric($byteString)) {
            return $byteString;
        }
        elseif (is_string($byteString) && preg_match('/^\s*([0-9.]+)\s*([KMGTPE])B?\s*$/i', $byteString, $matches)) {
            $number = (float) $matches[1];
            $index  = strpos(" KMGTPE", $matches[2]);
            while ($index--) {
                $number *= 1024;
            }

            return intval($number);
        }
        else {
            throw new \InvalidArgumentException(
                "Bad value provided as memory size: "
                . var_export($byteString, true)
            );
        }
    }

    /**/
}
