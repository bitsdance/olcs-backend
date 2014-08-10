<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;

/**
 * PhoneContact Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="phone_contact",
 *    indexes={
 *        @ORM\Index(name="fk_phone_contact_contact_details1_idx", columns={"contact_details_id"}),
 *        @ORM\Index(name="fk_phone_contact_ref_data1_idx", columns={"phone_contact_type"}),
 *        @ORM\Index(name="fk_phone_contact_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_phone_contact_user2_idx", columns={"last_modified_by"})
 *    }
 * )
 */
class PhoneContact implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\IdIdentity,
        Traits\LastModifiedByManyToOne,
        Traits\CreatedByManyToOne,
        Traits\ContactDetailsManyToOne,
        Traits\CustomCreatedOnField,
        Traits\CustomLastModifiedOnField,
        Traits\CustomVersionField;

    /**
     * Phone contact type
     *
     * @var \Olcs\Db\Entity\RefData
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\RefData")
     * @ORM\JoinColumn(name="phone_contact_type", referencedColumnName="id")
     */
    protected $phoneContactType;

    /**
     * Phone number
     *
     * @var string
     *
     * @ORM\Column(type="string", name="phone_number", length=45, nullable=true)
     */
    protected $phoneNumber;

    /**
     * Details
     *
     * @var string
     *
     * @ORM\Column(type="string", name="details", length=45, nullable=true)
     */
    protected $details;

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
     * Set the phone contact type
     *
     * @param \Olcs\Db\Entity\RefData $phoneContactType
     * @return PhoneContact
     */
    public function setPhoneContactType($phoneContactType)
    {
        $this->phoneContactType = $phoneContactType;

        return $this;
    }

    /**
     * Get the phone contact type
     *
     * @return \Olcs\Db\Entity\RefData
     */
    public function getPhoneContactType()
    {
        return $this->phoneContactType;
    }


    /**
     * Set the phone number
     *
     * @param string $phoneNumber
     * @return PhoneContact
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get the phone number
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }


    /**
     * Set the details
     *
     * @param string $details
     * @return PhoneContact
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get the details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

}
