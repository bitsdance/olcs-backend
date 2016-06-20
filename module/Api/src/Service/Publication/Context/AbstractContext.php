<?php

namespace Dvsa\Olcs\Api\Service\Publication\Context;

use Dvsa\Olcs\Api\Domain\QueryHandlerManager;

/**
 * Class AbstractContext
 * @package Dvsa\Olcs\Api\Service\Publication\Context
 */
abstract class AbstractContext implements ContextInterface
{
    /**
     * @var QueryHandlerManager
     */
    private $queryHandler;

    public function __construct(QueryHandlerManager $queryHandler)
    {
        $this->queryHandler = $queryHandler;
    }

    protected function handleQuery($query)
    {
        return $this->queryHandler->handleQuery($query, false);
    }
}
