<?php
namespace Tests;

use System;

/**
 * Class ConfigTest
 * @package Tests
 */
class ConfigTest extends \PHPUnit_Framework_TestCase {


    public function testConfigLoadClass()
    {
        $config = System\Config::getInstance();
        $this->assertInstanceOf( 'System\Config', $config );
    }

    public function testLoadConfigFromDevelopment()
    {
        $config = System\Config::getInstance();
        $this->assertTrue( $config->loadFromEnvironment('development') );
    }

    public function testLoadConfigFromProduction()
    {
        $config = System\Config::getInstance();
        $this->assertTrue( $config->loadFromEnvironment('production') );
    }

    public function testReadConfigs()
    {
        $config = System\Config::getInstance('production');
        $this->assertGreaterThan(0,count($config->getConfig()));
    }
}