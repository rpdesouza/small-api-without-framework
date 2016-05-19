<?php
namespace System\View;

/**
 * Class JsonP
 * @package System\View
 */
class JsonP extends ViewAbstract {

    /**
     * @var string
     */
    protected $_callback;

    /**
     * Render method
     */
    public function render()
    {
        $json = json_encode($this->_variables);
        header('Content-Type: application/json; charset=utf8');
        $callback = filter_input(INPUT_GET, 'callback', FILTER_SANITIZE_ENCODED);
        if (!$callback) {
            throw new InvalidViewException('callback not provided');
        }
        die($callback.'('.$json.')');
    }

}