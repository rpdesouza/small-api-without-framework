<?php
namespace System\View;

/**
 * Class Json
 * @package System\View
 */
class Json extends ViewAbstract {

    /**
     * Render method
     */
    public function render()
    {
        $json = json_encode($this->_variables);
        header('Content-Type: application/json; charset=utf8');
        die($json);
    }

}