<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Opposition Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedDate", timeAware=true)
 * @ORM\Table(name="opposition",
 *    indexes={
 *        @ORM\Index(name="fk_opposition_application1_idx", columns={"application_id"}),
 *        @ORM\Index(name="fk_opposition_opposer1_idx", columns={"opposer_id"}),
 *        @ORM\Index(name="fk_opposition_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_opposition_user2_idx", columns={"last_modified_by"})
 *    }
 * )
 */
class Opposition implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\IdIdentity,
        Traits\LastModifiedByManyToOne,
        Traits\CreatedByManyToOne,
        Traits\ApplicationManyToOne,
        Traits\Notes4000Field,
        Traits\CustomDeletedDateField,
        Traits\CustomCreatedOnField,
        Traits\CustomLastModifiedOnField,
        Traits\CustomVersionField;

    /**
     * Opposer
     *
     * @var \Olcs\Db\Entity\Opposer
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\Opposer")
     * @ORM\JoinColumn(name="opposer_id", referencedColumnName="id")
     */
    protected $opposer;

    /**
     * Is representation
     *
     * @var unknown
     *
     * @ORM\Column(type="yesno", name="is_representation", nullable=false)
     */
    protected $isRepresentation;

    /**
     * Is copied
     *
     * @var unknown
     *
     * @ORM\Column(type="yesno", name="is_copied", nullable=false)
     */
    protected $isCopied = 0;

    /**
     * Raised date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="raised_date", nullable=true)
     */
    protected $raisedDate;

    /**
     * Is in time
     *
     * @var unknown
     *
     * @ORM\Column(type="yesno", name="is_in_time", nullable=false)
     */
    protected $isInTime = 0;

    /**
     * Is public inquiry
     *
     * @var unknown
     *
     * @ORM\Column(type="yesno", name="is_public_inquiry", nullable=false)
     */
    protected $isPublicInquiry = 0;

    /**
     * Is valid
     *
     * @var unknown
     *
     * @ORM\Column(type="yesno", name="is_valid", nullable=false)
     */
    protected $isValid;

    /**
     * Valid notes
     *
     * @var string
     *
     * @ORM\Column(type="string", name="valid_notes", length=4000, nullable=true)
     */
    protected $validNotes;

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
     * Set the opposer
     *
     * @param \Olcs\Db\Entity\Opposer $opposer
     * @return Opposition
     */
    public function setOpposer($opposer)
    {
        $this->opposer = $opposer;

        return $this;
    }

    /**
     * Get the opposer
     *
     * @return \Olcs\Db\Entity\Opposer
     */
    public function getOpposer()
    {
        return $this->opposer;
    }


    /**
     * Set the is representation
     *
     * @param unknown $isRepresentation
     * @return Opposition
     */
    public function setIsRepresentation($isRepresentation)
    {
        $this->isRepresentation = $isRepresentation;

        return $this;
    }

    /**
     * Get the is representation
     *
     * @return unknown
     */
    public function getIsRepresentation()
    {
        return $this->isRepresentation;
    }


    /**
     * Set the is copied
     *
     * @param unknown $isCopied
     * @return Opposition
     */
    public function setIsCopied($isCopied)
    {
        $this->isCopied = $isCopied;

        return $this;
    }

    /**
     * Get the is copied
     *
     * @return unknown
     */
    public function getIsCopied()
    {
        return $this->isCopied;
    }


    /**
     * Set the raised date
     *
     * @param \DateTime $raisedDate
     * @return Opposition
     */
    public function setRaisedDate($raisedDate)
    {
        $this->raisedDate = $raisedDate;

        return $this;
    }

    /**
     * Get the raised date
     *
     * @return \DateTime
     */
    public function getRaisedDate()
    {
        return $this->raisedDate;
    }


    /**
     * Set the is in time
     *
     * @param unknown $isInTime
     * @return Opposition
     */
    public function setIsInTime($isInTime)
    {
        $this->isInTime = $isInTime;

        return $this;
    }

    /**
     * Get the is in time
     *
     * @return unknown
     */
    public function getIsInTime()
    {
        return $this->isInTime;
    }


    /**
     * Set the is public inquiry
     *
     * @param unknown $isPublicInquiry
     * @return Opposition
     */
    public function setIsPublicInquiry($isPublicInquiry)
    {
        $this->isPublicInquiry = $isPublicInquiry;

        return $this;
    }

    /**
     * Get the is public inquiry
     *
     * @return unknown
     */
    public function getIsPublicInquiry()
    {
        return $this->isPublicInquiry;
    }


    /**
     * Set the is valid
     *
     * @param unknown $isValid
     * @return Opposition
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Get the is valid
     *
     * @return unknown
     */
    public function getIsValid()
    {
        return $this->isValid;
    }


    /**
     * Set the valid notes
     *
     * @param string $validNotes
     * @return Opposition
     */
    public function setValidNotes($validNotes)
    {
        $this->validNotes = $validNotes;

        return $this;
    }

    /**
     * Get the valid notes
     *
     * @return string
     */
    public function getValidNotes()
    {
        return $this->validNotes;
    }

}
