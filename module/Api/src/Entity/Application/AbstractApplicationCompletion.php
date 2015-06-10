<?php

namespace Dvsa\Olcs\Api\Entity\Application;

use Dvsa\Olcs\Api\Domain\QueryHandler\BundleSerializableInterface;
use JsonSerializable;
use Dvsa\Olcs\Api\Entity\Traits\BundleSerializableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApplicationCompletion Abstract Entity
 *
 * Auto-Generated
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="application_completion",
 *    indexes={
 *        @ORM\Index(name="ix_application_completion_created_by", columns={"created_by"}),
 *        @ORM\Index(name="ix_application_completion_last_modified_by", columns={"last_modified_by"}),
 *        @ORM\Index(name="ix_application_completion_application_id", columns={"application_id"})
 *    },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="uk_application_completion_application_id", columns={"application_id"})
 *    }
 * )
 */
abstract class AbstractApplicationCompletion implements BundleSerializableInterface, JsonSerializable
{
    use BundleSerializableTrait;

    /**
     * Addresses status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="addresses_status", nullable=true)
     */
    protected $addressesStatus;

    /**
     * Application
     *
     * @var \Dvsa\Olcs\Api\Entity\Application\Application
     *
     * @ORM\OneToOne(
     *     targetEntity="Dvsa\Olcs\Api\Entity\Application\Application",
     *     fetch="LAZY",
     *     inversedBy="applicationCompletion"
     * )
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=false)
     */
    protected $application;

    /**
     * Business details status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="business_details_status", nullable=true)
     */
    protected $businessDetailsStatus;

    /**
     * Business type status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="business_type_status", nullable=true)
     */
    protected $businessTypeStatus;

    /**
     * Community licences status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="community_licences_status", nullable=true)
     */
    protected $communityLicencesStatus;

    /**
     * Conditions undertakings status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="conditions_undertakings_status", nullable=true)
     */
    protected $conditionsUndertakingsStatus;

    /**
     * Convictions penalties status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="convictions_penalties_status", nullable=true)
     */
    protected $convictionsPenaltiesStatus;

    /**
     * Created by
     *
     * @var \Dvsa\Olcs\Api\Entity\User\User
     *
     * @ORM\ManyToOne(targetEntity="Dvsa\Olcs\Api\Entity\User\User", fetch="LAZY")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=true)
     */
    protected $createdBy;

    /**
     * Created on
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="created_on", nullable=true)
     */
    protected $createdOn;

    /**
     * Discs status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="discs_status", nullable=true)
     */
    protected $discsStatus;

    /**
     * Financial evidence status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="financial_evidence_status", nullable=true)
     */
    protected $financialEvidenceStatus;

    /**
     * Financial history status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="financial_history_status", nullable=true)
     */
    protected $financialHistoryStatus;

    /**
     * Identifier - Id
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Last modified by
     *
     * @var \Dvsa\Olcs\Api\Entity\User\User
     *
     * @ORM\ManyToOne(targetEntity="Dvsa\Olcs\Api\Entity\User\User", fetch="LAZY")
     * @ORM\JoinColumn(name="last_modified_by", referencedColumnName="id", nullable=true)
     */
    protected $lastModifiedBy;

    /**
     * Last modified on
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="last_modified_on", nullable=true)
     */
    protected $lastModifiedOn;

    /**
     * Last section
     *
     * @var string
     *
     * @ORM\Column(type="string", name="last_section", length=255, nullable=true)
     */
    protected $lastSection;

    /**
     * Licence history status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="licence_history_status", nullable=true)
     */
    protected $licenceHistoryStatus;

    /**
     * Operating centres status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="operating_centres_status", nullable=true)
     */
    protected $operatingCentresStatus;

    /**
     * People status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="people_status", nullable=true)
     */
    protected $peopleStatus;

    /**
     * Safety status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="safety_status", nullable=true)
     */
    protected $safetyStatus;

    /**
     * Taxi phv status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="taxi_phv_status", nullable=true)
     */
    protected $taxiPhvStatus;

    /**
     * Transport managers status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="transport_managers_status", nullable=true)
     */
    protected $transportManagersStatus;

    /**
     * Type of licence status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="type_of_licence_status", nullable=true)
     */
    protected $typeOfLicenceStatus;

    /**
     * Undertakings status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="undertakings_status", nullable=true)
     */
    protected $undertakingsStatus;

    /**
     * Vehicles declarations status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="vehicles_declarations_status", nullable=true)
     */
    protected $vehiclesDeclarationsStatus;

    /**
     * Vehicles psv status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="vehicles_psv_status", nullable=true)
     */
    protected $vehiclesPsvStatus;

    /**
     * Vehicles status
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="vehicles_status", nullable=true)
     */
    protected $vehiclesStatus;

    /**
     * Version
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="version", nullable=false, options={"default": 1})
     * @ORM\Version
     */
    protected $version = 1;

    /**
     * Set the addresses status
     *
     * @param int $addressesStatus
     * @return ApplicationCompletion
     */
    public function setAddressesStatus($addressesStatus)
    {
        $this->addressesStatus = $addressesStatus;

        return $this;
    }

    /**
     * Get the addresses status
     *
     * @return int
     */
    public function getAddressesStatus()
    {
        return $this->addressesStatus;
    }

    /**
     * Set the application
     *
     * @param \Dvsa\Olcs\Api\Entity\Application\Application $application
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
     * @return \Dvsa\Olcs\Api\Entity\Application\Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set the business details status
     *
     * @param int $businessDetailsStatus
     * @return ApplicationCompletion
     */
    public function setBusinessDetailsStatus($businessDetailsStatus)
    {
        $this->businessDetailsStatus = $businessDetailsStatus;

        return $this;
    }

    /**
     * Get the business details status
     *
     * @return int
     */
    public function getBusinessDetailsStatus()
    {
        return $this->businessDetailsStatus;
    }

    /**
     * Set the business type status
     *
     * @param int $businessTypeStatus
     * @return ApplicationCompletion
     */
    public function setBusinessTypeStatus($businessTypeStatus)
    {
        $this->businessTypeStatus = $businessTypeStatus;

        return $this;
    }

    /**
     * Get the business type status
     *
     * @return int
     */
    public function getBusinessTypeStatus()
    {
        return $this->businessTypeStatus;
    }

    /**
     * Set the community licences status
     *
     * @param int $communityLicencesStatus
     * @return ApplicationCompletion
     */
    public function setCommunityLicencesStatus($communityLicencesStatus)
    {
        $this->communityLicencesStatus = $communityLicencesStatus;

        return $this;
    }

    /**
     * Get the community licences status
     *
     * @return int
     */
    public function getCommunityLicencesStatus()
    {
        return $this->communityLicencesStatus;
    }

    /**
     * Set the conditions undertakings status
     *
     * @param int $conditionsUndertakingsStatus
     * @return ApplicationCompletion
     */
    public function setConditionsUndertakingsStatus($conditionsUndertakingsStatus)
    {
        $this->conditionsUndertakingsStatus = $conditionsUndertakingsStatus;

        return $this;
    }

    /**
     * Get the conditions undertakings status
     *
     * @return int
     */
    public function getConditionsUndertakingsStatus()
    {
        return $this->conditionsUndertakingsStatus;
    }

    /**
     * Set the convictions penalties status
     *
     * @param int $convictionsPenaltiesStatus
     * @return ApplicationCompletion
     */
    public function setConvictionsPenaltiesStatus($convictionsPenaltiesStatus)
    {
        $this->convictionsPenaltiesStatus = $convictionsPenaltiesStatus;

        return $this;
    }

    /**
     * Get the convictions penalties status
     *
     * @return int
     */
    public function getConvictionsPenaltiesStatus()
    {
        return $this->convictionsPenaltiesStatus;
    }

    /**
     * Set the created by
     *
     * @param \Dvsa\Olcs\Api\Entity\User\User $createdBy
     * @return ApplicationCompletion
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get the created by
     *
     * @return \Dvsa\Olcs\Api\Entity\User\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set the created on
     *
     * @param \DateTime $createdOn
     * @return ApplicationCompletion
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get the created on
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set the discs status
     *
     * @param int $discsStatus
     * @return ApplicationCompletion
     */
    public function setDiscsStatus($discsStatus)
    {
        $this->discsStatus = $discsStatus;

        return $this;
    }

    /**
     * Get the discs status
     *
     * @return int
     */
    public function getDiscsStatus()
    {
        return $this->discsStatus;
    }

    /**
     * Set the financial evidence status
     *
     * @param int $financialEvidenceStatus
     * @return ApplicationCompletion
     */
    public function setFinancialEvidenceStatus($financialEvidenceStatus)
    {
        $this->financialEvidenceStatus = $financialEvidenceStatus;

        return $this;
    }

    /**
     * Get the financial evidence status
     *
     * @return int
     */
    public function getFinancialEvidenceStatus()
    {
        return $this->financialEvidenceStatus;
    }

    /**
     * Set the financial history status
     *
     * @param int $financialHistoryStatus
     * @return ApplicationCompletion
     */
    public function setFinancialHistoryStatus($financialHistoryStatus)
    {
        $this->financialHistoryStatus = $financialHistoryStatus;

        return $this;
    }

    /**
     * Get the financial history status
     *
     * @return int
     */
    public function getFinancialHistoryStatus()
    {
        return $this->financialHistoryStatus;
    }

    /**
     * Set the id
     *
     * @param int $id
     * @return ApplicationCompletion
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the last modified by
     *
     * @param \Dvsa\Olcs\Api\Entity\User\User $lastModifiedBy
     * @return ApplicationCompletion
     */
    public function setLastModifiedBy($lastModifiedBy)
    {
        $this->lastModifiedBy = $lastModifiedBy;

        return $this;
    }

    /**
     * Get the last modified by
     *
     * @return \Dvsa\Olcs\Api\Entity\User\User
     */
    public function getLastModifiedBy()
    {
        return $this->lastModifiedBy;
    }

    /**
     * Set the last modified on
     *
     * @param \DateTime $lastModifiedOn
     * @return ApplicationCompletion
     */
    public function setLastModifiedOn($lastModifiedOn)
    {
        $this->lastModifiedOn = $lastModifiedOn;

        return $this;
    }

    /**
     * Get the last modified on
     *
     * @return \DateTime
     */
    public function getLastModifiedOn()
    {
        return $this->lastModifiedOn;
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

    /**
     * Set the licence history status
     *
     * @param int $licenceHistoryStatus
     * @return ApplicationCompletion
     */
    public function setLicenceHistoryStatus($licenceHistoryStatus)
    {
        $this->licenceHistoryStatus = $licenceHistoryStatus;

        return $this;
    }

    /**
     * Get the licence history status
     *
     * @return int
     */
    public function getLicenceHistoryStatus()
    {
        return $this->licenceHistoryStatus;
    }

    /**
     * Set the operating centres status
     *
     * @param int $operatingCentresStatus
     * @return ApplicationCompletion
     */
    public function setOperatingCentresStatus($operatingCentresStatus)
    {
        $this->operatingCentresStatus = $operatingCentresStatus;

        return $this;
    }

    /**
     * Get the operating centres status
     *
     * @return int
     */
    public function getOperatingCentresStatus()
    {
        return $this->operatingCentresStatus;
    }

    /**
     * Set the people status
     *
     * @param int $peopleStatus
     * @return ApplicationCompletion
     */
    public function setPeopleStatus($peopleStatus)
    {
        $this->peopleStatus = $peopleStatus;

        return $this;
    }

    /**
     * Get the people status
     *
     * @return int
     */
    public function getPeopleStatus()
    {
        return $this->peopleStatus;
    }

    /**
     * Set the safety status
     *
     * @param int $safetyStatus
     * @return ApplicationCompletion
     */
    public function setSafetyStatus($safetyStatus)
    {
        $this->safetyStatus = $safetyStatus;

        return $this;
    }

    /**
     * Get the safety status
     *
     * @return int
     */
    public function getSafetyStatus()
    {
        return $this->safetyStatus;
    }

    /**
     * Set the taxi phv status
     *
     * @param int $taxiPhvStatus
     * @return ApplicationCompletion
     */
    public function setTaxiPhvStatus($taxiPhvStatus)
    {
        $this->taxiPhvStatus = $taxiPhvStatus;

        return $this;
    }

    /**
     * Get the taxi phv status
     *
     * @return int
     */
    public function getTaxiPhvStatus()
    {
        return $this->taxiPhvStatus;
    }

    /**
     * Set the transport managers status
     *
     * @param int $transportManagersStatus
     * @return ApplicationCompletion
     */
    public function setTransportManagersStatus($transportManagersStatus)
    {
        $this->transportManagersStatus = $transportManagersStatus;

        return $this;
    }

    /**
     * Get the transport managers status
     *
     * @return int
     */
    public function getTransportManagersStatus()
    {
        return $this->transportManagersStatus;
    }

    /**
     * Set the type of licence status
     *
     * @param int $typeOfLicenceStatus
     * @return ApplicationCompletion
     */
    public function setTypeOfLicenceStatus($typeOfLicenceStatus)
    {
        $this->typeOfLicenceStatus = $typeOfLicenceStatus;

        return $this;
    }

    /**
     * Get the type of licence status
     *
     * @return int
     */
    public function getTypeOfLicenceStatus()
    {
        return $this->typeOfLicenceStatus;
    }

    /**
     * Set the undertakings status
     *
     * @param int $undertakingsStatus
     * @return ApplicationCompletion
     */
    public function setUndertakingsStatus($undertakingsStatus)
    {
        $this->undertakingsStatus = $undertakingsStatus;

        return $this;
    }

    /**
     * Get the undertakings status
     *
     * @return int
     */
    public function getUndertakingsStatus()
    {
        return $this->undertakingsStatus;
    }

    /**
     * Set the vehicles declarations status
     *
     * @param int $vehiclesDeclarationsStatus
     * @return ApplicationCompletion
     */
    public function setVehiclesDeclarationsStatus($vehiclesDeclarationsStatus)
    {
        $this->vehiclesDeclarationsStatus = $vehiclesDeclarationsStatus;

        return $this;
    }

    /**
     * Get the vehicles declarations status
     *
     * @return int
     */
    public function getVehiclesDeclarationsStatus()
    {
        return $this->vehiclesDeclarationsStatus;
    }

    /**
     * Set the vehicles psv status
     *
     * @param int $vehiclesPsvStatus
     * @return ApplicationCompletion
     */
    public function setVehiclesPsvStatus($vehiclesPsvStatus)
    {
        $this->vehiclesPsvStatus = $vehiclesPsvStatus;

        return $this;
    }

    /**
     * Get the vehicles psv status
     *
     * @return int
     */
    public function getVehiclesPsvStatus()
    {
        return $this->vehiclesPsvStatus;
    }

    /**
     * Set the vehicles status
     *
     * @param int $vehiclesStatus
     * @return ApplicationCompletion
     */
    public function setVehiclesStatus($vehiclesStatus)
    {
        $this->vehiclesStatus = $vehiclesStatus;

        return $this;
    }

    /**
     * Get the vehicles status
     *
     * @return int
     */
    public function getVehiclesStatus()
    {
        return $this->vehiclesStatus;
    }

    /**
     * Set the version
     *
     * @param int $version
     * @return ApplicationCompletion
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get the version
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set the createdOn field on persist
     *
     * @ORM\PrePersist
     */
    public function setCreatedOnBeforePersist()
    {
        $this->createdOn = new \DateTime();
    }

    /**
     * Set the lastModifiedOn field on persist
     *
     * @ORM\PreUpdate
     */
    public function setLastModifiedOnBeforeUpdate()
    {
        $this->lastModifiedOn = new \DateTime();
    }

    /**
     * Clear properties
     *
     * @param type $properties
     */
    public function clearProperties($properties = array())
    {
        foreach ($properties as $property) {

            if (property_exists($this, $property)) {
                if ($this->$property instanceof Collection) {

                    $this->$property = new ArrayCollection(array());

                } else {

                    $this->$property = null;
                }
            }
        }
    }
}
