<?php
namespace Application\Controller;

use System;
use System\View;

/**
 * Class Index
 * @package Application\Controller
 */
class Index extends System\DefaultController
{

    /**
     * Action just for debug purposes
     * @param System\Request $request
     * @return View
     */
    public function Index(System\Request $request)
    {
        return new View( $request->getFormat(), $request->getParameters() );
    }
}