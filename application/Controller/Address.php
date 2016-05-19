<?php
namespace Application\Controller;

use System;
use System\View;

/**
 * Class Address
 * @package Application\Controller
 */
class Address extends System\DefaultController
{

    /**
     * @var array
     */
    private $_addresses = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->fillAddress();
    }

    /**
     * Get one record. Only first parameter is considered as ID, the others are ignored.
     *
     * @param System\Request $request
     * @return View
     */
    public function addressGet( System\Request $request )
    {
        $response = [];
        $params = $request->getParameters();
        try {
            if (count( $request->getParameters() ) > 0) {
                $id = array_shift( $params );
                $response = array_filter( $this->_addresses, function ( $item ) use ( $id ) {
                    return $item['id'] == $id;
                } );
            } else {
                $response = $this->_addresses;
            }
        } catch (\Exception $e) {
            $request->setStatus( 400 );
            $response = [$request->getStatusText( $request->getStatus() )];
            $this->getLogger()->error( 'error in action {action}. Response: {response}. Exception Message: {exception}',
                ['action' => __FUNCTION__, 'response' => print_r( $response, true ), 'exception' => $e->getMessage()] );
        }

        return new View( $request->getFormat(), $response );

    }

    /**
     * I'm using PUT for Update and POST for Create
     *
     * @param System\Request $request
     * @return View
     */
    public function AddressPut( System\Request $request )
    {
        $params = $request->getParameters();
        $response = [];
        try {
            $id = filter_var( $params['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]] );
            if (!$id) {
                $request->setStatus( 400 );
                $response = [$request->getStatusText( $request->getStatus() )];
            } else {
                //Sanitizing parameters
                $params = filter_var_array( $params, [
                    'id'     => ['filter' => FILTER_SANITIZE_NUMBER_INT],
                    'name'   => ['filter' => FILTER_SANITIZE_STRING],
                    'phone'  => ['filter' => FILTER_SANITIZE_NUMBER_INT],
                    'street' => ['filter' => FILTER_SANITIZE_STRING],
                ] );
                //Removing commas
                array_walk( $params, function ( &$item ) {
                    $item = trim( str_replace( ',', '', $item ) );
                } );
                //Creating an array with the new data
                $new_data = array_map( function ( $item ) use ( $params ) {
                    return $item['id'] == $params['id'] ? $params : $item;
                }, $this->_addresses );

                $file = fopen( 'data/examples.csv', 'w' );
                foreach ($new_data as $address) {
                    fputcsv( $file, $address );
                }
                fclose( $file );
                $response = ['OK'];
            }
        } catch (\Exception $e) {
            $request->setStatus( 400 );
            $response[] = $e->getMessage();
            $this->getLogger()->error( 'error in action {action}. Response: {response}. Exception Message: {exception}',
                ['action' => __FUNCTION__, 'response' => print_r( $response, true ), 'exception' => $e->getMessage()] );
        }

        return new View( $request->getFormat(), $response );
    }

    /**
     * Delete a record. Only first parameter is considered as ID, the others are ignored.
     *
     * @param System\Request $request
     * @return View
     */
    public function AddressDelete( System\Request $request )
    {
        $response = [];
        $params = $request->getParameters();
        try {
            if (count( $params ) > 0) {
                $id = array_shift( $params );
                $addresses = array_filter( $this->_addresses, function ( $item ) use ( $id ) {
                    return $item['id'] != $id;
                } );
                if (count( $addresses ) > 0) {
                    $file = fopen( 'data/examples.csv', 'w' );
                    foreach ($addresses as $address) {
                        fputcsv( $file, $address );
                    }
                    fclose( $file );
                }
            }
            $response = ['OK'];
        } catch (\Exception $e) {
            $request->setStatus( 400 );
            $response[] = $e->getMessage();
            $this->getLogger()->error( 'error in action {action}. Response: {response}. Exception Message: {exception}',
                ['action' => __FUNCTION__, 'response' => print_r( $response, true ), 'exception' => $e->getMessage()] );
        }


        return new View( $request->getFormat(), $response );
    }

    /**
     * I'm using PUT for Update and POST for Create
     *
     * @param System\Request $request
     * @return View
     */
    public function addressPost( System\Request $request )
    {
        $params = $request->getParameters();
        $response = [];
        try {
            //Sanitizing parameters
            $params = filter_var_array( $params, [
                'id'     => ['filter' => FILTER_SANITIZE_NUMBER_INT],
                'name'   => ['filter' => FILTER_SANITIZE_STRING],
                'phone'  => ['filter' => FILTER_SANITIZE_NUMBER_INT],
                'street' => ['filter' => FILTER_SANITIZE_STRING],
            ] );
            //Removing commas
            array_walk( $params, function ( &$item ) {
                $item = trim( str_replace( ',', '', $item ) );
            } );
            $id = filter_var( $params['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]] );
            if (!$id) {
                $request->setStatus( 400 );
                throw new System\Exception\InvalidArguments('Invalid id provided');
            }

            if (in_array($params, $this->_addresses)) {
                $request->setStatus( 409 );
                throw new System\Exception\InvalidArguments('Duplicate record');
            }

            //Adding new record
            $this->_addresses[] = $params;
            $file = fopen( 'data/examples.csv', 'w' );
            foreach ($this->_addresses as $address) {
                fputcsv( $file, $address );
            }
            fclose( $file );
            $response = ['OK'];

        } catch (\Exception $e) {
            $response[] = $e->getMessage();
            $this->getLogger()->error( 'error in action {action}. Response: {response}. Exception Message: {exception}',
                ['action' => __FUNCTION__, 'response' => print_r( $response, true ), 'exception' => $e->getMessage()] );
        }

        return new View( $request->getFormat(), $response );
    }


    /**
     * Fill Address property with file content
     */
    private function fillAddress()
    {
        $file = fopen( 'data/examples.csv', 'r' );
        while (($line = fgetcsv( $file )) !== false) {
            $this->_addresses[] = [
                'id'     => $line[0],
                'name'   => $line[1],
                'phone'  => $line[2],
                'street' => $line[3]
            ];
        }
        fclose( $file );
    }
}