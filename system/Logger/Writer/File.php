<?php
namespace System\Logger\Writer;

/**
 * Class File
 * @package System\Logger\Writer
 */
class File implements WriterInterface {

    /**
     * @param $message
     */
    public function write($message)
    {
        $today = new \DateTime();
        error_log($message, 3, "data/log/log_{$today->format('Y-m-d')}.log");
    }
}