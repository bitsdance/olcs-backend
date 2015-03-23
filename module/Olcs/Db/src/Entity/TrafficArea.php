<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Olcs\Db\Entity\Traits;

/**
 * TrafficArea Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="traffic_area",
 *    indexes={
 *        @ORM\Index(name="ix_traffic_area_created_by", columns={"created_by"}),
 *        @ORM\Index(name="ix_traffic_area_last_modified_by", columns={"last_modified_by"}),
 *        @ORM\Index(name="ix_traffic_area_contact_details_id", columns={"contact_details_id"})
 *    }
 * )
 */
class TrafficArea implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\ContactDetailsManyToOneAlt1,
        Traits\CreatedByManyToOne,
        Traits\CustomCreatedOnField,
        Traits\LastModifiedByManyToOne,
        Traits\CustomLastModifiedOnField,
        Traits\Name70Field,
        Traits\TxcName70Field,
        Traits\CustomVersionField;

    /**
     * Bus reg
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Olcs\Db\Entity\BusReg", mappedBy="trafficAreas")
     */
    protected $busRegs;

    /**
     * Identifier - Id
     *
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string", name="id", length=1)
     */
    protected $id;

    /**
     * Is england
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_england", nullable=false, options={"default": 0})
     */
    protected $isEngland = 0;

    /**
     * Is ni
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_ni", nullable=false, options={"default": 0})
     */
    protected $isNi = 0;

    /**
     * Is scotland
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_scotland", nullable=false, options={"default": 0})
     */
    protected $isScotland = 0;

    /**
     * Is wales
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_wales", nullable=false, options={"default": 0})
     */
    protected $isWales = 0;

    /**
     * Recipient
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Olcs\Db\Entity\Recipient", mappedBy="trafficAreas")
     */
    protected $recipients;

    /**
     * Document
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Olcs\Db\Entity\Document", mappedBy="trafficArea")
     */
    protected $documents;

    /**
     * Initialise the collections
     */
    public function __construct()
    {
        $this->recipients = new ArrayCollection();
        $this->busRegs = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    /**
     * Set the bus reg
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $busRegs
     * @return TrafficArea
     */
    public function setBusRegs($busRegs)
    {
        $this->busRegs = $busRegs;

        return $this;
    }

    /**
     * Get the bus regs
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBusRegs()
    {
        return $this->busRegs;
    }

    /**
     * Add a bus regs
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $busRegs
     * @return TrafficArea
     */
    public function addBusRegs($busRegs)
    {
        if ($busRegs instanceof ArrayCollection) {
            $this->busRegs = new ArrayCollection(
                array_merge(
                    $this->busRegs->toArray(),
                    $busRegs->toArray()
                )
            );
        } elseif (!$this->busRegs->contains($busRegs)) {
            $this->busRegs->add($busRegs);
        }

        return $this;
    }

    /**
     * Remove a bus regs
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $busRegs
     * @return TrafficArea
     */
    public function removeBusRegs($busRegs)
    {
        if ($this->busRegs->contains($busRegs)) {
            $this->busRegs->removeElement($busRegs);
        }

        return $this;
    }

    /**
     * Set the id
     *
     * @param string $id
     * @return TrafficArea
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the is england
     *
     * @param boolean $isEngland
     * @return TrafficArea
     */
    public function setIsEngland($isEngland)
    {
        $this->isEngland = $isEngland;

        return $this;
    }

    /**
     * Get the is england
     *
     * @return boolean
     */
    public function getIsEngland()
    {
        return $this->isEngland;
    }

    /**
     * Set the is ni
     *
     * @param boolean $isNi
     * @return TrafficArea
     */
    public function setIsNi($isNi)
    {
        $this->isNi = $isNi;

        return $this;
    }

    /**
     * Get the is ni
     *
     * @return boolean
     */
    public function getIsNi()
    {
        return $this->isNi;
    }

    /**
     * Set the is scotland
     *
     * @param boolean $isScotland
     * @return TrafficArea
     */
    public function setIsScotland($isScotland)
    {
        $this->isScotland = $isScotland;

        return $this;
    }

    /**
     * Get the is scotland
     *
     * @return boolean
     */
    public function getIsScotland()
    {
        return $this->isScotland;
    }

    /**
     * Set the is wales
     *
     * @param boolean $isWales
     * @return TrafficArea
     */
    public function setIsWales($isWales)
    {
        $this->isWales = $isWales;

        return $this;
    }

    /**
     * Get the is wales
     *
     * @return boolean
     */
    public function getIsWales()
    {
        return $this->isWales;
    }

    /**
     * Set the recipient
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $recipients
     * @return TrafficArea
     */
    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * Get the recipients
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Add a recipients
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $recipients
     * @return TrafficArea
     */
    public function addRecipients($recipients)
    {
        if ($recipients instanceof ArrayCollection) {
            $this->recipients = new ArrayCollection(
                array_merge(
                    $this->recipients->toArray(),
                    $recipients->toArray()
                )
            );
        } elseif (!$this->recipients->contains($recipients)) {
            $this->recipients->add($recipients);
        }

        return $this;
    }

    /**
     * Remove a recipients
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $recipients
     * @return TrafficArea
     */
    public function removeRecipients($recipients)
    {
        if ($this->recipients->contains($recipients)) {
            $this->recipients->removeElement($recipients);
        }

        return $this;
    }

    /**
     * Set the document
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $documents
     * @return TrafficArea
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;

        return $this;
    }

    /**
     * Get the documents
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add a documents
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $documents
     * @return TrafficArea
     */
    public function addDocuments($documents)
    {
        if ($documents instanceof ArrayCollection) {
            $this->documents = new ArrayCollection(
                array_merge(
                    $this->documents->toArray(),
                    $documents->toArray()
                )
            );
        } elseif (!$this->documents->contains($documents)) {
            $this->documents->add($documents);
        }

        return $this;
    }

    /**
     * Remove a documents
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $documents
     * @return TrafficArea
     */
    public function removeDocuments($documents)
    {
        if ($this->documents->contains($documents)) {
            $this->documents->removeElement($documents);
        }

        return $this;
    }
}
