<?php

namespace Dvsa\Olcs\Api\Domain\CommandHandler\IrhpPermitStock;

use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitStock as StockEntity;
use Dvsa\Olcs\Transfer\Command\IrhpPermitStock\Create as CreateStockCmd;
use Dvsa\Olcs\Api\Domain\Exception\ValidationException;
use Dvsa\Olcs\Api\Domain\ToggleAwareTrait;
use Dvsa\Olcs\Api\Entity\System\FeatureToggle;

/**
 * Create an IRHP Permit Stock
 *
 * @author Scott Callaway <scott.callaway@capgemini.com>
 */
final class Create extends AbstractCommandHandler
{
    use ToggleAwareTrait;

    protected $toggleConfig = [FeatureToggle::ADMIN_PERMITS];
    protected $repoServiceName = 'IrhpPermitStock';

    protected $extraRepos = ['IrhpPermitType'];

    public function handleCommand(CommandInterface $command): Result
    {
        $result = new Result();
        $permitType = $this->getRepo('IrhpPermitType')->fetchById($command->getPermitType());

        /**
         * @var CreateStockCmd $command
         */
        $stock = StockEntity::create(
            $permitType,
            $command->getValidFrom(),
            $command->getValidTo(),
            $command->getInitialStock()
        );

        try {
            $this->getRepo()->save($stock);
        } catch (\Exception $e) {
            throw new ValidationException(['You cannot create a duplicate stock']);
        }

        $result->addId('IrhpPermitStock', $stock->getId());
        $result->addMessage("IRHP Permit Stock '{$stock->getId()}' created");

        return $result;
    }
}
