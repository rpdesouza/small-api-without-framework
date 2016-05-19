<?php
namespace System\View;

/**
 * Class ViewAbstract
 * @package system\View
 */
abstract class ViewAbstract {

    /**
     * View variables
     * @var array|ArrayAccess&Traversable
     */
    protected $_variables = [];

    /**
     * @var string
     */
    protected $_view = 'Index';

    /**
     * @var string
     */
    protected $_format = 'Json';

    /**
     * @param $variables
     */
    public function __construct($format, $variables)
    {
        if (null === $variables) {
            $variables = [];
        }

        if (isset($format) && !empty($format)) {
            $this->_format = $format;
        }

        $this->setVariables($variables);
    }

    /**
     * @return mixed
     */
    abstract function render();

    /**
     * @param $variables
     * @return $this
     */
    public function setVariables($variables)
    {
        foreach ($variables as $key => $value) {
            $this->setVariable($key, $value);
        }

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setVariable($name, $value)
    {
        $this->_variables[(string) $name] = $value;
        return $this;
    }
}