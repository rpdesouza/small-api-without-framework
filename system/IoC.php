<?php
namespace System;

/**
 * Class IoC
 * IoC and DI Container
 *
 * @package System
 */
class IoC
{
    /**
     * @var array
     */
    private static $_container = [];

    /**
     * @param Config $config
     */
    public static function init( Config $config )
    {
        foreach ($config->getConfig('IoC') as $key => $closure) {
            static::$_container[strtolower( $key )] = $closure;
        }
    }

    /**
     * @param $className
     * @return mixed
     * @throws \Exception
     */
    public static function getInstance( $className )
    {
        $className = strtolower( $className );
        if (static::has( $className )) {
            return call_user_func( static::$_container[$className] );
        } else {
            throw new \Exception( "Class not found: $className", 1 );
        }
    }

    /**
     * @param $className
     * @return bool
     */
    public static function has( $className )
    {
        if (array_key_exists( $className, static::$_container )) {
            return true;
        }
    }
}