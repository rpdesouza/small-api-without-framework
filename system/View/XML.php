<?php
namespace System\View;

/**
 * Class Json
 * @package System\View
 */
class XML extends ViewAbstract
{

    /**
     * Render method
     */
    public function render()
    {
        $xml = new \SimpleXMLElement( "<?xml version=\"1.0\"?><addresses/>" );
        foreach ($this->_variables as $value) {
            $address = $xml->addChild('address');
            $address->addChild('name', $value['name']);
            $address->addChild('phone', $value['phone']);
            $address->addChild('street', $value['street']);
        }
        header( 'Content-Type: application/xml; charset=utf-8' );
        print $xml->asXML();
    }

}