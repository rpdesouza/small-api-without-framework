<?php
namespace System;

use System\Exception;

/**
 * Class View
 * @package System
 */
class View extends View\ViewAbstract
{

    /**
     * @throws Exception
     * @throws \Exception
     */
    function render()
    {
        try {
            $view = $this->factory();
            $view->render();
        } catch ( Exception\InvalidView $e) {
            throw $e;
        } finally {
            IoC::getInstance( 'Logger' )->error( 'View not found' );
        }
    }

    /**
     * Factory Pattern
     * @return mixed
     * @throws Exception\InvalidView
     * @throws \Exception
     */
    private function factory()
    {
        $class = "System\\View\\{$this->_format}";
        if (class_exists( $class )) {
            return ( new $class( $this->_format, $this->_variables ) );
        } else {
            IoC::getInstance( 'Logger' )->error( 'View not found. {class}', ['class' => $class] );
            throw new Exception\InvalidView( "Invalid view type given." );
        }
    }

}