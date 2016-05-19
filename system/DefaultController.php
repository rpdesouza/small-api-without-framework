<?php
namespace system;

/**
 * Class DefaultController
 * @package system
 */
class DefaultController {

    /**
     * @var Logger
     */
    private $_logger;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_logger = IoC::getInstance('Logger');
        return true;
    }

    /**
     * Singleton approach
     * @return static
     */
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        return $instance;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->_logger;
    }
}