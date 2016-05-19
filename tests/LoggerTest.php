<?php
namespace Tests;

use System;

class LoggerTest extends \PHPUnit_Framework_TestCase {

    public function testConfigLoadClass()
    {
        $logger = new System\Logger();
        $this->assertInstanceOf( 'System\Logger', $logger );
    }

    public function testLoggerHasDefaultWriter()
    {
        $logger = new System\Logger();
        $this->assertInstanceOf( 'System\Logger\Writer\WriterInterface', $logger->getWriter() );
    }
}