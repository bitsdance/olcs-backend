<?php

namespace Dvsa\Olcs\Api\Service\Qa\Structure\QuestionText\Custom\EcmtShortTerm;

use Dvsa\Olcs\Api\Entity\Fee\FeeType as FeeTypeEntity;
use Dvsa\Olcs\Api\Domain\Repository\FeeType as FeeTypeRepository;
use Dvsa\Olcs\Api\Service\Qa\Structure\QuestionText\QuestionTextGenerator;
use Dvsa\Olcs\Api\Service\Qa\Structure\QuestionText\QuestionTextGeneratorInterface;
use Dvsa\Olcs\Api\Service\Qa\Structure\QuestionText\QuestionTextGeneratorContext;

class NoOfPermitsGenerator implements QuestionTextGeneratorInterface
{
    /** @var QuestionTextGenerator */
    private $questionTextGenerator;

    /** @var FeeTypeRepository */
    private $feeTypeRepo;

    /**
     * Create service instance
     *
     * @param QuestionTextGenerator $questionTextGenerator
     * @param FeeTypeRepository $feeTypeRepo
     *
     * @return NoOfPermitsGenerator
     */
    public function __construct(
        QuestionTextGenerator $questionTextGenerator,
        FeeTypeRepository $feeTypeRepo
    ) {
        $this->questionTextGenerator = $questionTextGenerator;
        $this->feeTypeRepo = $feeTypeRepo;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(QuestionTextGeneratorContext $context)
    {
        $irhpApplicationEntity = $context->getIrhpApplicationEntity();

        $applicationFee = $this->feeTypeRepo->getLatestByProductReference(
            $irhpApplicationEntity->getApplicationFeeProductReference()
        );

        $issueFee = $this->feeTypeRepo->getLatestByProductReference(
            $irhpApplicationEntity->getIssueFeeProductReference()
        );

        $feePerPermit = $irhpApplicationEntity->getFeePerPermit($applicationFee, $issueFee);
        $applicationFeeFixedValue = $applicationFee->getFixedValue();

        $questionText = $this->questionTextGenerator->generate($context);
        $guidanceTranslateableText = $questionText->getGuidance()->getTranslateableText();

        $guidanceTranslateableText->getParameter(0)->setValue($feePerPermit);
        $guidanceTranslateableText->getParameter(1)->setValue($applicationFeeFixedValue);
        $guidanceTranslateableText->getParameter(2)->setValue($issueFee->getFixedValue());
        $guidanceTranslateableText->getParameter(3)->setValue($applicationFeeFixedValue);

        return $questionText;
    }
}
