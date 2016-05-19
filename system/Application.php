<?php
namespace System;

use System\Exception;
use System\Psr\Log\LoggerAwareInterface;

/**
 * Class Application
 * @package System
 */
class Application implements LoggerAwareInterface
{
    /**
     * @var Config
     */
    private $_config;

    /**
     * @var Router
     */
    private $_router;

    /**
     * @var Logger
     */
    private $_logger;

    /**
     * @var Request
     */
    private $_request;

    /**
     * @param Config $config
     */
    public function __construct( Config $config )
    {
        if (NULL !== $config) {
            $this->_config = $config;
        } else {
            $this->getLogger()->error( 'Invalid application configurations provided' );
            throw new Exception\InvalidArguments(
                'Invalid configs provided'
            );
        }
    }

    /**
     * @param Router $router
     */
    public function setRouter( Router $router )
    {
        $this->_router = $router;
    }

    /**
     * @return null
     */
    public function getRouter()
    {
        if ($this->setRouter === NULL) {
            $this->setRouter( IoC::getInstance( 'Router' ) );
        }

        return $this->setRouter;
    }

    /**
     * Run the Application
     */
    public function run()
    {
        $this->getLogger()->debug( 'Application starts to run at: {time}',
            ['time' => ( new \DateTime() )->format( 'Y-m-d H:i:s' )] );
        try {
            if ($this->_router->parseRoute()) {
                $controller = $this->_router->getController();
                $action = $this->_router->getAction();
                //Singleton
                $controller = $controller::getInstance();
                $result = $controller->$action( $this->request() );
                $this->request()->sendHeaders();
                $result->render();
            } else {
                $this->getLogger()->warning( 'Path not found. {controller} -> {action}',
                    ['controller' => $this->_router->getController(), 'action' => $this->_router->getAction()] );
                $this->request()->sendHeaders();
            }
        } catch (\Exception $e) {
            $this->getLogger()->error( 'error in Application\Run' );
            $this->request()->setStatus( 500 );
            $this->request()->sendHeaders();
        }
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
            $this->setLogger( IoC::getInstance( 'Logger' ) );
        }

        return $this->_logger;
    }

    /**
     * @return Request
     */
    public function request()
    {
        if ($this->_request === NULL) {
            $this->setRequest( IoC::getInstance( 'Request' ) );
        }

        return $this->_request;
    }

    /**
     * @param Request $request
     */
    public function setRequest( Request $request )
    {
        $this->_request = $request;
    }


}