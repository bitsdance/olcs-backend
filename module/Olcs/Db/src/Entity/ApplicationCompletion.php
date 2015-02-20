<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;

/**
 * ApplicationCompletion Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="application_completion",
 *    indexes={
 *        @ORM\Index(name="fk_application_completion_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_application_completion_user2_idx", columns={"last_modified_by"})
 *    },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="fk_application_completion_application_id_udx", columns={"application_id"})
 *    }
 * )
 */
class ApplicationCompletion implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\AddressesStatusField,
        Traits\BusinessDetailsStatusField,
        Traits\BusinessTypeStatusField,
        Traits\CommunityLicencesStatusField,
        Traits\ConditionsUndertakingsStatusField,
        Traits\ConvictionsPenaltiesStatusField,
        Traits\CreatedByManyToOne,
        Traits\CustomCreatedOnField,
        Traits\DiscsStatusField,
        Traits\FinancialEvidenceStatusField,
        Traits\FinancialHistoryStatusField,
        Traits\IdIdentity,
        Traits\LastModifiedByManyToOne,
        Traits\CustomLastModifiedOnField,
        Traits\LicenceHistoryStatusField,
        Traits\OperatingCentresStatusField,
        Traits\PeopleStatusField,
        Traits\SafetyStatusField,
        Traits\TaxiPhvStatusField,
        Traits\TransportManagersStatusField,
        Traits\TypeOfLicenceStatusField,
        Traits\UndertakingsStatusField,
        Traits\VehiclesDeclarationsStatusField,
        Traits\VehiclesPsvStatusField,
        Traits\VehiclesStatusField,
        Traits\CustomVersionField;

    /**
     * Application
     *
     * @var \Olcs\Db\Entity\Application
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\Application", inversedBy="applicationCompletions")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=false)
     */
    protected $application;

    /**
     * Last section
     *
     * @var string
     *
     * @ORM\Column(type="string", name="last_section", length=255, nullable=true)
     */
    protected $lastSection;

    /**
     * Set the application
     *
     * @param \Olcs\Db\Entity\Application $application
     * @return ApplicationCompletion
     */
    public function setApplication($application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get the application
     *
     * @return \Olcs\Db\Entity\Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set the last section
     *
     * @param string $lastSection
     * @return ApplicationCompletion
     */
    public function setLastSection($lastSection)
    {
        $this->lastSection = $lastSection;

        return $this;
    }

    /**
     * Get the last section
     *
     * @return string
     */
    public function getLastSection()
    {
        return $this->lastSection;
    }
}
