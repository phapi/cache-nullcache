<?php

namespace Phapi\Tests\Di\Validator;

use Mockery\Mock;
use Phapi\Di\Validator\Cache;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @coversDefaultClass \Phapi\Di\Validator\Cache
 */
class CacheTest extends TestCase
{

    public $validator;
    public $container;

    public function setUp()
    {
        $mocklogger = \Mockery::mock('NullLogger');
        $mocklogger->shouldReceive('warning');

        $this->container = \Mockery::mock('Phapi\Contract\Di\Container');
        $this->container->shouldReceive('offsetGet')->with('log')->andReturn($mocklogger);

        $this->validator = new Cache($this->container);
    }

    public function testInvalidCache()
    {
        $cache = $this->validator->validate(new \stdClass());
        $this->assertInstanceOf('Phapi\Cache\NullCache', $cache($this->container));
    }

    public function testValidCache()
    {
        $closure = function ($app) {
            $cache = \Mockery::mock('Phapi\Cache\Memcache', 'Phapi\Contract\Cache');

            return $cache;
        };

        $cache = $this->validator->validate($closure);
        $this->assertInstanceOf('Phapi\Cache\Memcache', $cache($this->container));
    }

    public function testUnableToConnect()
    {
        $closure = function ($app) {
            throw new \Exception(); // An exception should be thrown if unable to connect to backend
        };

        $cache = $this->validator->validate($closure);
        $this->assertInstanceOf('Phapi\Cache\NullCache', $cache($this->container));
    }
}