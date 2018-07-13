# phpunit-profile-asserts

This lib provides asserts against execution time and memory usage.
It also provides a StopwatchListener based on the Stopwatch component of Symfony.


## Installation

Add this to your composer.json.

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/jclaveau/phpunit-profile-asserts",
            "no-api": true
        }
    },
    "require-dev": {
        "jclaveau/phpunit-profile-asserts": "dev-master",
    }
}
```


## Usage

Enable by adding the following code to your project's `phpunit.xml` file:

```xml
<phpunit bootstrap="vendor/autoload.php">
...
    <listeners>
        <listener class="JClaveau\PHPUnit\Listener\StopwatchListener" />
    </listeners>
</phpunit>
```

```php
class SomeTestCase extends \PHPUnit_Framework_TestCase
{
    use UsageConstraintTrait; // adds the asserts methods

    /**
     */
    public function test_usages()
    {
        ...

        $this->assertExecutionTimeShorter(100); // seconds
        $this->assertMemoryUsageBelow('1M');
    }

}
```

## TODO

+ PHP 7 implementation (find an elegant way to support PHP 5 and 7 together)
+ Integrate SpeedTrap and adds MemoryTrap
+ Investigate xhprof integration and asserts on number of calls / execution time of specific methods/functions

## Inspiration

+ https://github.com/usernam3/phpunit_stopwatch_annotations
+ https://github.com/johnkary/phpunit-speedtrap


## License

phpunit-profile-asserts is available under the MIT License.
