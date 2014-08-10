<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TmQualification Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedDate", timeAware=true)
 * @ORM\Table(name="tm_qualification",
 *    indexes={
 *        @ORM\Index(name="fk_qualification_transport_manager1_idx", columns={"transport_manager_id"}),
 *        @ORM\Index(name="fk_qualification_country1_idx", columns={"country_code"}),
 *        @ORM\Index(name="fk_qualification_ref_data1_idx", columns={"qualification_type"}),
 *        @ORM\Index(name="fk_tm_qualification_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_tm_qualification_user2_idx", columns={"last_modified_by"})
 *    }
 * )
 */
class TmQualification implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\IdIdentity,
        Traits\LastModifiedByManyToOne,
        Traits\CreatedByManyToOne,
        Traits\CountryCodeManyToOne,
        Traits\TransportManagerManyToOne,
        Traits\IssuedDateField,
        Traits\CustomDeletedDateField,
        Traits\CustomCreatedOnField,
        Traits\CustomLastModifiedOnField,
        Traits\CustomVersionField;

    /**
     * Qualification type
     *
     * @var \Olcs\Db\Entity\RefData
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\RefData")
     * @ORM\JoinColumn(name="qualification_type", referencedColumnName="id")
     */
    protected $qualificationType;

    /**
     * Serial no
     *
     * @var string
     *
     * @ORM\Column(type="string", name="serial_no", length=20, nullable=true)
     */
    protected $serialNo;

    /**
     * Get identifier(s)
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->getId();
    }

    /**
     * Set the qualification type
     *
     * @param \Olcs\Db\Entity\RefData $qualificationType
     * @return TmQualification
     */
    public function setQualificationType($qualificationType)
    {
        $this->qualificationType = $qualificationType;

        return $this;
    }

    /**
     * Get the qualification type
     *
     * @return \Olcs\Db\Entity\RefData
     */
    public function getQualificationType()
    {
        return $this->qualificationType;
    }


    /**
     * Set the serial no
     *
     * @param string $serialNo
     * @return TmQualification
     */
    public function setSerialNo($serialNo)
    {
        $this->serialNo = $serialNo;

        return $this;
    }

    /**
     * Get the serial no
     *
     * @return string
     */
    public function getSerialNo()
    {
        return $this->serialNo;
    }

}
