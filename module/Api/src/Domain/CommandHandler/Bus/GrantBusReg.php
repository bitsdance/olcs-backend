<?php

/**
 * Grant BusReg
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\Bus;

use Doctrine\ORM\Query;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\Exception\BadRequestException;
use Dvsa\Olcs\Api\Domain\Exception\ValidationException;
use Dvsa\Olcs\Api\Entity\Bus\BusReg as BusRegEntity;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Transfer\Command\Publication\Bus as PublishDto;
use Dvsa\Olcs\Transfer\Command\Bus\GrantBusReg as GrantCmd;

/**
 * Grant BusReg
 */
final class GrantBusReg extends AbstractCommandHandler
{
    protected $repoServiceName = 'Bus';

    public function handleCommand(CommandInterface $command)
    {
        /**
         * @var BusRegEntity $busReg
         * @var GrantCmd $command
         */
        $busReg = $this->getRepo()->fetchUsingId($command, Query::HYDRATE_OBJECT);

        $status = $busReg->getStatusForGrant();

        if (empty($status)) {
            throw new BadRequestException('The Bus Reg is not grantable');
        }

        $variationReasons = null;
        if ($busReg->getStatus()->getId() == BusRegEntity::STATUS_VAR) {
            if ($command->getVariationReasons() !== null) {
                // set variation reasons
                foreach ($command->getVariationReasons() as $variationReasonId) {
                    $variationReasons[] = $this->getRepo()->getRefdataReference($variationReasonId);
                }
            }

            if (empty($variationReasons)) {
                throw new ValidationException(['Variation reasons missing']);
            }
        }

        $busReg->grant(
            $this->getRepo()->getRefdataReference($status),
            $variationReasons
        );

        $this->getRepo()->save($busReg);

        // publish the bus reg
        $this->publish($busReg);

        $result = new Result();
        $result->addId('bus', $busReg->getId());
        $result->addMessage('Bus Reg granted successfully');

        return $result;
    }

    /**
     * @param BusRegEntity $busReg
     * @return Result
     */
    private function publish(BusRegEntity $busReg)
    {
        return $this->getCommandHandler()->handleCommand(
            PublishDto::create(
                [
                    'id' => $busReg->getId(),
                ]
            )
        );
    }
}
