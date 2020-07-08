<?php

namespace Dvsa\Olcs\Api\Service\Qa\Structure\Element\Custom\Bilateral;

use Dvsa\Olcs\Api\Service\Qa\QaContext;
use Dvsa\Olcs\Api\Service\Qa\Structure\Element\AnswerSaverInterface;
use Dvsa\Olcs\Api\Service\Qa\Supports\IrhpPermitApplicationOnlyTrait;

class EmissionsStandardsAnswerSaver implements AnswerSaverInterface
{
    use IrhpPermitApplicationOnlyTrait;

    /** @var CountryDeletingAnswerSaver */
    private $countryDeletingAnswerSaver;

    /**
     * Create service instance
     *
     * @param CountryDeletingAnswerSaver $countryDeletingAnswerSaver
     *
     * @return EmissionsStandardsAnswerSaver
     */
    public function __construct(CountryDeletingAnswerSaver $countryDeletingAnswerSaver)
    {
        $this->countryDeletingAnswerSaver = $countryDeletingAnswerSaver;
    }

    /**
     * {@inheritdoc}
     */
    public function save(QaContext $qaContext, array $postData)
    {
        return $this->countryDeletingAnswerSaver->save(
            $qaContext,
            $postData,
            'qanda.bilaterals.emissions-standards.euro3-or-euro4'
        );
    }
}
