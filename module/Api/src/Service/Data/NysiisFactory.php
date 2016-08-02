<?php

namespace Dvsa\Olcs\Api\Service\Data;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Olcs\Logging\Log\Logger;
use Dvsa\Olcs\Api\Domain\Exception\NysiisException;

/**
 * Class NysiisFactory
 * @package Olcs\Service\Data
 */
class NysiisFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config['nysiis']['wsdl']['uri'])) {
            throw new NysiisException('Unable to create soap client: WSDL not found');
        }

        set_error_handler([$this, 'my_custom_soap_wsdl_error_handler']);
        try {
            $soapClient = @new \SoapClient(
                $config['nysiis']['wsdl']['uri'],
                $config['nysiis']['wsdl']['soap']['options']
            );
            restore_error_handler();

            return new Nysiis($soapClient, $config);

        } catch (\SoapFault $e) {
            throw new NysiisException('Unable to create soap client: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new NysiisException('Unable to create soap client: ' . $e->getMessage());
        } finally {
            // do nothing
        }
    }

    /**
     * Error handler for instantiation of SOAP client. This is necessary due to PHP Fatal errors being generated when
     * the service is down and no connection to Nysiis is available.
     * This error handler ensures that a PHP SoapFault exception is raised rather than a PHP Fatal error. The
     * exception can then be caught and handled correctly.
     * Fixes possible bug with WSDL and SOAP.
     * @see http://php.net/manual/en/class.soapclient.php
     * @see https://bugs.php.net/bug.php?id=47584
     * 
     * @param   int         $errno
     * @param   string      $errstr
     * @param   null|string $errfile
     * @param   null|int    $errline
     * @param   null|string $errcontext
     * @throws \SoapFault
     */
    public function my_custom_soap_wsdl_error_handler(
        $errno,
        $errstr,
        $errfile = null,
        $errline = null,
        $errcontext = null
    ) {
        // Simulate the exception that Soap *should* have thrown instead of an error.
        // This is needed to support certain server setups it would seem.
        $wsdl_url = isset($errcontext['wsdl']) ? $errcontext['wsdl'] : '';
        $msg = "SOAP-ERROR: Parsing WSDL: Couldn't load from '" . $wsdl_url .
            "' : failed to load external entity \"" . $wsdl_url . "\"";
        if (class_exists('SoapFault')) {
            $e = new \SoapFault('WSDL', $msg);
        }
        else {
            $e = new \Exception($msg, 0);
        }
        throw $e;
    }
}

