<?php
namespace System;

/**
 * Class Config
 * @package System
 */
class Config {

    /**
     * @var array
     */
    private $_config = [];

    /**
     * @var null|string
     */
    private $_environment = 'development';

    /**
     * @var Config
     */
    protected static $_instance;

    /**
     * @param null $environment
     * @return Config
     */
    public static function getInstance($environment = NULL)
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self($environment);
        }
        return self::$_instance;
    }

    /**
     * @param null $environment
     */
    public function __construct( $environment = NULL ) {
        if (null !== $environment) {
            $this->_environment = $environment;
        }
        $this->loadFromEnvironment();
    }

    /**
     * @param null $environment
     * @return bool
     */
    public function loadFromEnvironment( $environment = NULL ) {
        if (null !== $environment) {
            $this->_environment = $environment;
        }

        if ($this->_environment != '') {
            $this->_setConfig($this->_environment);
        } else {
            throw new Exception\InvalidArguments(
                'Invalid configs provided'
            );
        }
        return TRUE;
    }

    /**
     * @param $environment
     */
    private function _setConfig($environment) {
        $environment = trim(strtolower($environment));
        if (!file_exists("application/config/environment/{$environment}.php")) {
            throw new Exception\InvalidArguments(
                'Invalid configs environment provided'
            );
        }
        $application_config = include 'application/config/application.php';
        $environment_config = include "application/config/environment/{$environment}.php";
        $this->_config = $this->_mergeOptions($application_config, $environment_config);
    }

    /**
     * @param array $array1
     * @param null $array2
     * @return array
     */
    private function _mergeOptions(array $array1, $array2 = null)
    {
        if (is_array($array2)) {
            foreach ($array2 as $key => $val) {
                if (is_array($array2[$key])) {
                    $array1[$key] = (array_key_exists($key, $array1) && is_array($array1[$key]))
                        ? $this->_mergeOptions($array1[$key], $array2[$key])
                        : $array2[$key];
                } else {
                    $array1[$key] = $val;
                }
            }
        }
        return $array1;
    }

    /**
     * @param null|string $environment
     */
    public function setEnvironment( $environment )
    {
        $this->_environment = $environment;
    }

    /**
     * @param null $index
     * @return array
     */
    public function getConfig($index = NULL)
    {
        return $index != NULL ? $this->_config[$index] : $this->_config;
    }
}