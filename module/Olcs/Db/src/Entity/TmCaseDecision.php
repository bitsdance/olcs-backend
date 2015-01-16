<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TmCaseDecision Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedDate", timeAware=true)
 * @ORM\Table(name="tm_case_decision",
 *    indexes={
 *        @ORM\Index(name="fk_tm_case_decision_ref_data1_idx", columns={"decision"}),
 *        @ORM\Index(name="fk_tm_case_decision_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_tm_case_decision_user2_idx", columns={"last_modified_by"}),
 *        @ORM\Index(name="fk_tm_case_decision_cases1_idx", columns={"case_id"}),
 *        @ORM\Index(name="fk_tm_case_decision_ref_data2", columns={"unfitness"}),
 *        @ORM\Index(name="fk_tm_case_decision_ref_data3", columns={"rehab"})
 *    }
 * )
 */
class TmCaseDecision implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\CaseManyToOne,
        Traits\CreatedByManyToOne,
        Traits\CustomCreatedOnField,
        Traits\DecisionDateField,
        Traits\CustomDeletedDateField,
        Traits\IdIdentity,
        Traits\LastModifiedByManyToOne,
        Traits\CustomLastModifiedOnField,
        Traits\CustomVersionField;

    /**
     * Decision
     *
     * @var \Olcs\Db\Entity\RefData
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\RefData")
     * @ORM\JoinColumn(name="decision", referencedColumnName="id", nullable=false)
     */
    protected $decision;

    /**
     * Is msi
     *
     * @var string
     *
     * @ORM\Column(type="yesno", name="is_msi", nullable=false)
     */
    protected $isMsi = 0;

    /**
     * Notified date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="notified_date", nullable=true)
     */
    protected $notifiedDate;

    /**
     * Rehab
     *
     * @var \Olcs\Db\Entity\RefData
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\RefData")
     * @ORM\JoinColumn(name="rehab", referencedColumnName="id", nullable=true)
     */
    protected $rehab;

    /**
     * Repute not lost reason
     *
     * @var string
     *
     * @ORM\Column(type="string", name="repute_not_lost_reason", length=500, nullable=true)
     */
    protected $reputeNotLostReason;

    /**
     * Unfitness
     *
     * @var \Olcs\Db\Entity\RefData
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\RefData")
     * @ORM\JoinColumn(name="unfitness", referencedColumnName="id", nullable=true)
     */
    protected $unfitness;

    /**
     * Unfitness end date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="unfitness_end_date", nullable=true)
     */
    protected $unfitnessEndDate;

    /**
     * Unfitness start date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="unfitness_start_date", nullable=true)
     */
    protected $unfitnessStartDate;

    /**
     * Set the decision
     *
     * @param \Olcs\Db\Entity\RefData $decision
     * @return TmCaseDecision
     */
    public function setDecision($decision)
    {
        $this->decision = $decision;

        return $this;
    }

    /**
     * Get the decision
     *
     * @return \Olcs\Db\Entity\RefData
     */
    public function getDecision()
    {
        return $this->decision;
    }

    /**
     * Set the is msi
     *
     * @param string $isMsi
     * @return TmCaseDecision
     */
    public function setIsMsi($isMsi)
    {
        $this->isMsi = $isMsi;

        return $this;
    }

    /**
     * Get the is msi
     *
     * @return string
     */
    public function getIsMsi()
    {
        return $this->isMsi;
    }

    /**
     * Set the notified date
     *
     * @param \DateTime $notifiedDate
     * @return TmCaseDecision
     */
    public function setNotifiedDate($notifiedDate)
    {
        $this->notifiedDate = $notifiedDate;

        return $this;
    }

    /**
     * Get the notified date
     *
     * @return \DateTime
     */
    public function getNotifiedDate()
    {
        return $this->notifiedDate;
    }

    /**
     * Set the rehab
     *
     * @param \Olcs\Db\Entity\RefData $rehab
     * @return TmCaseDecision
     */
    public function setRehab($rehab)
    {
        $this->rehab = $rehab;

        return $this;
    }

    /**
     * Get the rehab
     *
     * @return \Olcs\Db\Entity\RefData
     */
    public function getRehab()
    {
        return $this->rehab;
    }

    /**
     * Set the repute not lost reason
     *
     * @param string $reputeNotLostReason
     * @return TmCaseDecision
     */
    public function setReputeNotLostReason($reputeNotLostReason)
    {
        $this->reputeNotLostReason = $reputeNotLostReason;

        return $this;
    }

    /**
     * Get the repute not lost reason
     *
     * @return string
     */
    public function getReputeNotLostReason()
    {
        return $this->reputeNotLostReason;
    }

    /**
     * Set the unfitness
     *
     * @param \Olcs\Db\Entity\RefData $unfitness
     * @return TmCaseDecision
     */
    public function setUnfitness($unfitness)
    {
        $this->unfitness = $unfitness;

        return $this;
    }

    /**
     * Get the unfitness
     *
     * @return \Olcs\Db\Entity\RefData
     */
    public function getUnfitness()
    {
        return $this->unfitness;
    }

    /**
     * Set the unfitness end date
     *
     * @param \DateTime $unfitnessEndDate
     * @return TmCaseDecision
     */
    public function setUnfitnessEndDate($unfitnessEndDate)
    {
        $this->unfitnessEndDate = $unfitnessEndDate;

        return $this;
    }

    /**
     * Get the unfitness end date
     *
     * @return \DateTime
     */
    public function getUnfitnessEndDate()
    {
        return $this->unfitnessEndDate;
    }

    /**
     * Set the unfitness start date
     *
     * @param \DateTime $unfitnessStartDate
     * @return TmCaseDecision
     */
    public function setUnfitnessStartDate($unfitnessStartDate)
    {
        $this->unfitnessStartDate = $unfitnessStartDate;

        return $this;
    }

    /**
     * Get the unfitness start date
     *
     * @return \DateTime
     */
    public function getUnfitnessStartDate()
    {
        return $this->unfitnessStartDate;
    }
}
