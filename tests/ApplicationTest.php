<?php
namespace Tests;

use System\Psr\Log;
use System\Application;

/**
 * Class RoutesTest
 * @package Tests
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    public $app;

    public function setUp()
    {
        $config = $this->getMock( 'System\Config' );
        $this->app = new Application( $config );
    }

    public function testApplicationLoadClass()
    {
        $this->assertInstanceOf( 'System\Application', $this->app );
    }

    public function testApplicationIsLogAware()
    {
        $this->assertTrue($this->app instanceof Log\LoggerAwareInterface);
    }

}