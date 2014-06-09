<?php

/**
 * A trait that controllers can use to easily interact with the flash messenger.
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
namespace Olcs\Db\Traits;

use Zend\Log\LoggerAwareTrait as ZendLoggerAwareTrait;
use Zend\Log\Logger;

/**
 * A trait that controllers can use to easily interact with the flash messenger.
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
trait LoggerAwareTrait
{

    use ZendLoggerAwareTrait;

    /**
     * Returns an instantiated instance of Zend Log.
     *
     * @return Logger
     */
    public function getLogger()
    {
        if (null === $this->logger) {

            $logger = $this->getServiceLocator()->get('Logger');

            if (($logger instanceof \Zend\Log\Logger) !== true) {
                throw new \LogicException(
                    "Incorrect object. Expecting '\Zend\Log\Logger', found " . get_class($logger)
                );
            }

            $this->setLogger($logger);
        }
        return $this->logger;
    }

    /**
     * Logs a message to the defined logger.
     *
     * @param string $message
     * @param string $priority
     */
    public function log($message, $priority = Logger::INFO, $extra = array())
    {
        $this->getLogger()->log($priority, $message, $extra);
    }
}
