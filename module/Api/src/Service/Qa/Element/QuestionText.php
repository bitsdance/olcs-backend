<?php

namespace Dvsa\Olcs\Api\Service\Qa\Element;

class QuestionText
{
    /** @var FilteredTranslateableText|null */
    private $question;

    /** @var FilteredTranslateableText|null */
    private $details;

    /** @var FilteredTranslateableText|null */
    private $guidance;

    /** @var FilteredTranslateableText|null */
    private $additionalGuidance;

    /**
     * Create instance
     *
     * @param FilteredTranslateableText $question (optional)
     * @param FilteredTranslateableText $details (optional)
     * @param FilteredTranslateableText $guidance (optional)
     * @param FilteredTranslateableText $additionalGuidance (optional)
     *
     * @return QuestionText
     */
    public function __construct(
        ?FilteredTranslateableText $question = null,
        ?FilteredTranslateableText $details = null,
        ?FilteredTranslateableText $guidance = null,
        ?FilteredTranslateableText $additionalGuidance = null
    ) {
        $this->question = $question;
        $this->details = $details;
        $this->guidance = $guidance;
        $this->additionalGuidance = $additionalGuidance;
    }

    /**
     * Get the representation of this class to be returned by the API endpoint
     *
     * @return array
     */
    public function getRepresentation()
    {
        if (!is_null($this->question)) {
            $response['question'] = $this->question->getRepresentation();
        }

        if (!is_null($this->details)) {
            $response['details'] = $this->details->getRepresentation();
        }

        if (!is_null($this->guidance)) {
            $response['guidance'] = $this->guidance->getRepresentation();
        }

        if (!is_null($this->additionalGuidance)) {
            $response['additionalGuidance'] = $this->additionalGuidance->getRepresentation();
        }

        return $response;
    }

    /**
     * Get the embedded FilteredTranslateableText instance representing the guidance
     *
     * @return FilteredTranslateableText|null
     */
    public function getGuidance()
    {
        return $this->guidance;
    }
}
