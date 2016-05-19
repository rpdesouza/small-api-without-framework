<?php
namespace System\Logger\Writer;

/**
 * Interface WriterInterface
 * @package system\Logger\Writer
 */
interface WriterInterface {

    /**
     * @param $message
     * @return mixed
     */
    public function write($message);
}