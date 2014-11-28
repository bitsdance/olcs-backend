<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OrganisationNatureOfBusiness Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedDate", timeAware=true)
 * @ORM\Table(name="organisation_nature_of_business",
 *    indexes={
 *        @ORM\Index(name="fk_org_nob_ref_data1_idx", columns={"ref_data_id"}),
 *        @ORM\Index(name="fk_org_nob_organisation1_idx", columns={"organisation_id"}),
 *        @ORM\Index(name="fk_org_nob_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_org_nob_user2_idx", columns={"last_modified_by"})
 *    }
 * )
 */
class OrganisationNatureOfBusiness implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\IdIdentity,
        Traits\LastModifiedByManyToOne,
        Traits\CreatedByManyToOne,
        Traits\CustomDeletedDateField,
        Traits\CustomCreatedOnField,
        Traits\CustomLastModifiedOnField,
        Traits\CustomVersionField;

    /**
     * Ref data
     *
     * @var \Olcs\Db\Entity\RefData
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\RefData", fetch="LAZY")
     * @ORM\JoinColumn(name="ref_data_id", referencedColumnName="id", nullable=false)
     */
    protected $refData;

    /**
     * Organisation
     *
     * @var \Olcs\Db\Entity\Organisation
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\Organisation", fetch="LAZY", inversedBy="natureOfBusinesss")
     * @ORM\JoinColumn(name="organisation_id", referencedColumnName="id", nullable=false)
     */
    protected $organisation;

    /**
     * Set the ref data
     *
     * @param \Olcs\Db\Entity\RefData $refData
     * @return OrganisationNatureOfBusiness
     */
    public function setRefData($refData)
    {
        $this->refData = $refData;

        return $this;
    }

    /**
     * Get the ref data
     *
     * @return \Olcs\Db\Entity\RefData
     */
    public function getRefData()
    {
        return $this->refData;
    }

    /**
     * Set the organisation
     *
     * @param \Olcs\Db\Entity\Organisation $organisation
     * @return OrganisationNatureOfBusiness
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;

        return $this;
    }

    /**
     * Get the organisation
     *
     * @return \Olcs\Db\Entity\Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }
}
