<?php
namespace System;

use System\Psr\Log\LoggerAwareInterface;

/**
 * Class Router
 * @package System
 */
class Router implements LoggerAwareInterface
{

    /**
     * @var string
     */
    private $_controller;

    /**
     * @var string
     */
    private $_action;

    /**
     * @var string
     */
    private $_request;

    /**
     * @var Logger
     */
    private $_logger;

    /**
     * @var Config
     */
    private $_config;

    /**
     * @param Request $request
     * @param Config $config
     */
    public function __construct( Request $request, Config $config )
    {
        if (NULL !== $request) {
            $this->_request = $request;
        } else {
            throw new Exception\InvalidArguments(
                'Invalid URL provided'
            );
        }
        $this->_config = $config;
    }

    /**
     * @return bool
     */
    public function parseRoute()
    {
        $routes = $this->_config->getConfig('routes');
        //if exists this route in config file
        if (!array_key_exists($this->_request->getEndpoint(), $routes)) {
            $this->_request->setStatus('404');
            return FALSE;
        }
        //if this route allows this http method
        $methods = $routes[$this->_request->getEndpoint()];
        if (!array_key_exists($this->_request->getMethod(), $methods)) {
            $this->_request->setStatus('405');
            return FALSE;
        }
        //if exists class and method
        $controller = $routes[$this->_request->getEndpoint()][$this->_request->getMethod()]['controller'];
        $action = $routes[$this->_request->getEndpoint()][$this->_request->getMethod()]['action'];
        if (class_exists($controller) && method_exists($controller, $action)) {
            $this->_controller = $controller;
            $this->_action = $action;
            return true;
        }
        //otherwise cancel the process
        $this->_request->setStatus('500');
        return FALSE;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * Sets a logger instance on the object
     *
     * @param Logger $logger
     * @return $this
     */
    public function setLogger( Logger $logger )
    {
        $this->_logger = $logger;
    }

    /**
     * Get a logger instance on the object
     *
     * @return Logger
     */
    public function getLogger()
    {
        if ($this->_logger === NULL) {
            $this->setLogger(IoC::getInstance('Logger'));
        }
        return $this->_logger;
    }
}