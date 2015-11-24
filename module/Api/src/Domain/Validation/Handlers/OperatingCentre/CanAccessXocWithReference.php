<?php

/**
 * Can Access Xoc With Reference
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\Validation\Handlers\OperatingCentre;

use Dvsa\Olcs\Api\Domain\Validation\Handlers\AbstractHandler;

/**
 * Can Access Xoc With Reference
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class CanAccessXocWithReference extends AbstractHandler
{
    /**
     * @inheritdoc
     */
    public function isValid($dto)
    {
        list($prefix, $id) = $this->splitTypeAndId($dto->getId());

        if ($prefix === 'A') {
            return $this->canAccessApplication($id);
        }

        if ($prefix === 'L') {
            return $this->canAccessLicence($id);
        }

        return false;
    }

    private function splitTypeAndId($ref)
    {
        $type = substr($ref, 0, 1);

        $id = (int)substr($ref, 1);

        return [$type, $id];
    }
}
