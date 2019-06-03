<?php

namespace Dvsa\Olcs\Api\Entity\Generic;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApplicationValidation Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="application_validation",
 *    indexes={
 *        @ORM\Index(name="fk_application_validation_question1_idx", columns={"question_id"}),
 *        @ORM\Index(name="fk_application_validation_application_step1_idx",
     *     columns={"application_step_id"})
 *    }
 * )
 */
class ApplicationValidation extends AbstractApplicationValidation
{
    /**
     * Get a platform/framework-neutral representation of this validation rule
     *
     * @return array
     */
    public function getRepresentation()
    {
        return [
            'rule' => $this->rule,
            'parameters' => json_decode($this->parameters, true)
        ];
    }
}
