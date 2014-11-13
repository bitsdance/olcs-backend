<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GoodsDisc Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="goods_disc",
 *    indexes={
 *        @ORM\Index(name="fk_goods_disc_licence_vehicle1_idx", 
 *            columns={"licence_vehicle_id"}),
 *        @ORM\Index(name="fk_goods_disc_user1_idx", 
 *            columns={"created_by"}),
 *        @ORM\Index(name="fk_goods_disc_user2_idx", 
 *            columns={"last_modified_by"}),
 *        @ORM\Index(name="fk_goods_disc_ref_data1_idx", 
 *            columns={"removal_reason"}),
 *        @ORM\Index(name="fk_goods_disc_ref_data2_idx", 
 *            columns={"removal_explanation"})
 *    }
 * )
 */
class GoodsDisc implements Interfaces\EntityInterface
{

    /**
     * Licence vehicle
     *
     * @var \Olcs\Db\Entity\LicenceVehicle
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\LicenceVehicle", fetch="LAZY", inversedBy="goodsDiscs")
     * @ORM\JoinColumn(name="licence_vehicle_id", referencedColumnName="id", nullable=false)
     */
    protected $licenceVehicle;

    /**
     * Is copy
     *
     * @var string
     *
     * @ORM\Column(type="yesno", name="is_copy", nullable=false)
     */
    protected $isCopy = 0;

    /**
     * Reprint required
     *
     * @var string
     *
     * @ORM\Column(type="yesno", name="reprint_required", nullable=false)
     */
    protected $reprintRequired = 0;

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
     * @var \Olcs\Db\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\User", fetch="LAZY")
     * @ORM\JoinColumn(name="last_modified_by", referencedColumnName="id", nullable=true)
     */
    protected $lastModifiedBy;

    /**
     * Created by
     *
     * @var \Olcs\Db\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\User", fetch="LAZY")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=true)
     */
    protected $createdBy;

    /**
     * Removal explanation
     *
     * @var \Olcs\Db\Entity\RefData
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\RefData", fetch="LAZY")
     * @ORM\JoinColumn(name="removal_explanation", referencedColumnName="id", nullable=true)
     */
    protected $removalExplanation;

    /**
     * Removal reason
     *
     * @var \Olcs\Db\Entity\RefData
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\RefData", fetch="LAZY")
     * @ORM\JoinColumn(name="removal_reason", referencedColumnName="id", nullable=true)
     */
    protected $removalReason;

    /**
     * Disc no
     *
     * @var string
     *
     * @ORM\Column(type="string", name="disc_no", length=50, nullable=true)
     */
    protected $discNo;

    /**
     * Issued date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="issued_date", nullable=true)
     */
    protected $issuedDate;

    /**
     * Ceased date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="ceased_date", nullable=true)
     */
    protected $ceasedDate;

    /**
     * Is interim
     *
     * @var string
     *
     * @ORM\Column(type="yesno", name="is_interim", nullable=false)
     */
    protected $isInterim = 0;

    /**
     * Is printing
     *
     * @var string
     *
     * @ORM\Column(type="yesno", name="is_printing", nullable=false)
     */
    protected $isPrinting = 0;

    /**
     * Created on
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="created_on", nullable=true)
     */
    protected $createdOn;

    /**
     * Last modified on
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="last_modified_on", nullable=true)
     */
    protected $lastModifiedOn;

    /**
     * Version
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="version", nullable=false)
     * @ORM\Version
     */
    protected $version;

    /**
     * Set the licence vehicle
     *
     * @param \Olcs\Db\Entity\LicenceVehicle $licenceVehicle
     * @return GoodsDisc
     */
    public function setLicenceVehicle($licenceVehicle)
    {
        $this->licenceVehicle = $licenceVehicle;

        return $this;
    }

    /**
     * Get the licence vehicle
     *
     * @return \Olcs\Db\Entity\LicenceVehicle
     */
    public function getLicenceVehicle()
    {
        return $this->licenceVehicle;
    }

    /**
     * Set the is copy
     *
     * @param string $isCopy
     * @return GoodsDisc
     */
    public function setIsCopy($isCopy)
    {
        $this->isCopy = $isCopy;

        return $this;
    }

    /**
     * Get the is copy
     *
     * @return string
     */
    public function getIsCopy()
    {
        return $this->isCopy;
    }

    /**
     * Set the reprint required
     *
     * @param string $reprintRequired
     * @return GoodsDisc
     */
    public function setReprintRequired($reprintRequired)
    {
        $this->reprintRequired = $reprintRequired;

        return $this;
    }

    /**
     * Get the reprint required
     *
     * @return string
     */
    public function getReprintRequired()
    {
        return $this->reprintRequired;
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

    /**
     * Set the id
     *
     * @param int $id
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
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
     * @param \Olcs\Db\Entity\User $lastModifiedBy
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
     */
    public function setLastModifiedBy($lastModifiedBy)
    {
        $this->lastModifiedBy = $lastModifiedBy;

        return $this;
    }

    /**
     * Get the last modified by
     *
     * @return \Olcs\Db\Entity\User
     */
    public function getLastModifiedBy()
    {
        return $this->lastModifiedBy;
    }

    /**
     * Set the created by
     *
     * @param \Olcs\Db\Entity\User $createdBy
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get the created by
     *
     * @return \Olcs\Db\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set the removal explanation
     *
     * @param \Olcs\Db\Entity\RefData $removalExplanation
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
     */
    public function setRemovalExplanation($removalExplanation)
    {
        $this->removalExplanation = $removalExplanation;

        return $this;
    }

    /**
     * Get the removal explanation
     *
     * @return \Olcs\Db\Entity\RefData
     */
    public function getRemovalExplanation()
    {
        return $this->removalExplanation;
    }

    /**
     * Set the removal reason
     *
     * @param \Olcs\Db\Entity\RefData $removalReason
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
     */
    public function setRemovalReason($removalReason)
    {
        $this->removalReason = $removalReason;

        return $this;
    }

    /**
     * Get the removal reason
     *
     * @return \Olcs\Db\Entity\RefData
     */
    public function getRemovalReason()
    {
        return $this->removalReason;
    }

    /**
     * Set the disc no
     *
     * @param string $discNo
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
     */
    public function setDiscNo($discNo)
    {
        $this->discNo = $discNo;

        return $this;
    }

    /**
     * Get the disc no
     *
     * @return string
     */
    public function getDiscNo()
    {
        return $this->discNo;
    }

    /**
     * Set the issued date
     *
     * @param \DateTime $issuedDate
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
     */
    public function setIssuedDate($issuedDate)
    {
        $this->issuedDate = $issuedDate;

        return $this;
    }

    /**
     * Get the issued date
     *
     * @return \DateTime
     */
    public function getIssuedDate()
    {
        return $this->issuedDate;
    }

    /**
     * Set the ceased date
     *
     * @param \DateTime $ceasedDate
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
     */
    public function setCeasedDate($ceasedDate)
    {
        $this->ceasedDate = $ceasedDate;

        return $this;
    }

    /**
     * Get the ceased date
     *
     * @return \DateTime
     */
    public function getCeasedDate()
    {
        return $this->ceasedDate;
    }

    /**
     * Set the is interim
     *
     * @param string $isInterim
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
     */
    public function setIsInterim($isInterim)
    {
        $this->isInterim = $isInterim;

        return $this;
    }

    /**
     * Get the is interim
     *
     * @return string
     */
    public function getIsInterim()
    {
        return $this->isInterim;
    }

    /**
     * Set the is printing
     *
     * @param string $isPrinting
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
     */
    public function setIsPrinting($isPrinting)
    {
        $this->isPrinting = $isPrinting;

        return $this;
    }

    /**
     * Get the is printing
     *
     * @return string
     */
    public function getIsPrinting()
    {
        return $this->isPrinting;
    }

    /**
     * Set the created on
     *
     * @param \DateTime $createdOn
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
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
     * Set the createdOn field on persist
     *
     * @ORM\PrePersist
     */
    public function setCreatedOnBeforePersist()
    {
        $this->setCreatedOn(new \DateTime('NOW'));
    }

    /**
     * Set the last modified on
     *
     * @param \DateTime $lastModifiedOn
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
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
     * Set the lastModifiedOn field on persist
     *
     * @ORM\PreUpdate
     */
    public function setLastModifiedOnBeforeUpdate()
    {
        $this->setLastModifiedOn(new \DateTime('NOW'));
    }

    /**
     * Set the version
     *
     * @param int $version
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
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
     * Set the version field on persist
     *
     * @ORM\PrePersist
     */
    public function setVersionBeforePersist()
    {
        $this->setVersion(1);
    }
}
