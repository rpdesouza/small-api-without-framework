<?php
namespace system;

use System\Psr\Log;

/**
 * Class Logger
 * @package system
 */
class Logger
{

    use Log\LoggerTrait;

    /**
     * @var Logger\Writer\WriterInterface
     */
    private $_writer;

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function log( $level, $message, array $context = [] )
    {
        $writer = $this->getWriter();
        $now = new \DateTime();
        $log_message = "[{$now->format('Y-m-d H:i:s')}] ";
        $log_message .= "[$level] ";
        $log_message .= $this->interpolate($message, $context);
        $writer->write($log_message);
    }

    /**
     * @return Logger\Writer\WriterInterface
     */
    public function getWriter()
    {
        if ($this->_writer === NULL) {
            $this->setWriter(new Logger\Writer\Null());
        }

        return $this->_writer;
    }

    /**
     * @param Logger\Writer\WriterInterface $writer
     */
    public function setWriter( Logger\Writer\WriterInterface $writer )
    {
        $this->_writer = $writer;
    }

    /**
     * Interpolate a message
     *
     * @see http://www.php-fig.org/psr/psr-3/
     *
     * @param $message
     * @param array $context
     * @return string
     */
    public function interpolate($message, array $context = [])
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}