<?php
namespace System;

use System\Exception;
use System\Psr\Log\LoggerAwareInterface;

/**
 * Class Request
 * @package System
 */
class Request implements LoggerAwareInterface
{

    /**
     * @var Config
     */
    protected static $_instance;

    /**
     * Stores $_SERVER
     * @var array
     */
    protected $_server_information;

    /**
     * The HTTP method. GET, POST, PUT or DELETE
     * @var string
     */
    protected $_method = '';

    /**
     * Endpoint
     * Pattern /<endpoint>/<arg0>/<arg1>/<argx>
     * @var string
     */
    protected $_endpoint = 'index';

    /**
     * @var array
     */
    protected $_parameters;

    /**
     * @var string
     */
    protected $_format = 'Json';

    /**
     * HTTP Status Code
     * @var int
     */
    protected $_status = 200;

    /**
     * @var Logger
     */
    private $_logger;

    /**
     * Singleton
     * @param $server_information
     * @return Config|Request
     */
    public static function getInstance( $server_information )
    {
        if (is_null( self::$_instance )) {
            self::$_instance = new self( $server_information );
        }

        return self::$_instance;
    }

    /**
     * @param $server_information
     * @throws Exception\RequestException
     */
    public function __construct( $server_information )
    {
        $this->_server_information = $server_information;
        $this->_method = $this->_server_information['REQUEST_METHOD'];

        $request_url = (isset($this->_server_information['REQUEST_URI'])) ? $this->_server_information['REQUEST_URI'] : '';
        $script_url = (isset($this->_server_information['PHP_SELF'])) ? $this->_server_information['PHP_SELF'] : '';


        $this->parseParameters( $server_information, $script_url, $request_url );
        $this->setStatus( 200 );
    }

    /**
     * @throws Exception\RequestException
     */
    public function parseParameters( $server_information, $script_url, $request_url )
    {

        $url = trim( preg_replace( '/' . str_replace( '/', '\/', str_replace( 'index.php', '', $script_url ) ) . '/',
            '', $request_url, 1 ), '/' );

        $url = filter_var($url, FILTER_SANITIZE_URL);

        $this->_parameters = explode( '/', $url );

        $endpoint = array_shift( $this->_parameters );
        if (!empty($endpoint)) {
            $this->_endpoint = $endpoint;
        }

        if (isset($server_information['QUERY_STRING']) && isset($_GET['format'])) {
            $this->_format = filter_input( INPUT_GET, 'format' );
            $this->_endpoint = str_replace( $server_information['QUERY_STRING'], '', $this->_endpoint );
            $this->_endpoint = str_replace( '?', '', $this->_endpoint );
        }

        if ($this->_method != 'GET') {
            $put = file_get_contents( "php://input" );
            $content_type = $_SERVER['CONTENT_TYPE'] ?: false;
            switch ($content_type) {
                case "application/json":
                    $this->_parameters = array_merge($this->_parameters, json_decode( $put ));
                    break;
                case "application/x-www-form-urlencoded":
                    parse_str( $put, $params );
                    $this->_parameters = array_merge($this->_parameters, $params);
                    break;
                default:
                    $this->getLogger()->error('content type not recognized: {content}'. ['content' => $content_type]);
                    throw new Exception\RequestException("content type not recognized: {$content_type}");
                    break;
            }
        }
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param int $status
     */
    public function setStatus( $status )
    {
        $this->_status = $status;
    }

    /**
     * @return string
     */
    public function getStatusText()
    {
        $types = [
            200 => 'OK',
            400 => 'Bad Request',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            409 => 'Conflict',
            500 => 'Internal Server Error',
        ];

        return ($types[$this->_status]) ? $types[$this->_status] : $types[500];
    }

    /**
     * @return $this
     */
    public function sendHeaders()
    {
        if (headers_sent()) {
            return $this;
        }

        // status
        header( sprintf( 'HTTP/1.1 %s %s', $this->getStatus(), $this->getStatusText() ), true, $this->getStatus() );
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * @return string
     */
    public function getVerb()
    {
        return $this->_verb;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->_endpoint;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
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