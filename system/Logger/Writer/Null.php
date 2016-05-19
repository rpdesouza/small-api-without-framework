<?php
namespace System\Logger\Writer;

/**
 * Class Null
 * @package System\Logger\Writer
 */
class Null implements WriterInterface {

    /**
     * @param $message
     */
    public function write($message)
    {
        /**
         * Silence is golden
         * @see http://www.youtube.com/watch?v=n03g8nsaBro
         */
        error_log($message);
    }
}