<?php
namespace System\Psr\Log;

use System\Logger;

/**
 * Describes a logger-aware instance
 * @see http://www.php-fig.org/psr/psr-3/
 */
interface LoggerAwareInterface
{

    /**
     * Sets a logger instance on the object
     *
     * @param Logger $logger
     * @return $this
     */
    public function setLogger(Logger $logger);

    /**
     * Get a logger instance on the object
     *
     * @return Logger
     */
    public function getLogger();
}