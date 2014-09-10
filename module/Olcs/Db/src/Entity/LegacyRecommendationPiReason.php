<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;

/**
 * LegacyRecommendationPiReason Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="legacy_recommendation_pi_reason",
 *    indexes={
 *        @ORM\Index(name="fk_legacy_recommendation_pi_reason_legacy_recommendation1_idx", columns={"legacy_recommendation_id"}),
 *        @ORM\Index(name="fk_legacy_recommendation_pi_reason_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_legacy_recommendation_pi_reason_user2_idx", columns={"last_modified_by"}),
 *        @ORM\Index(name="fk_legacy_recommendation_pi_reason_legacy_pi_reason1_idx", columns={"legacy_pi_reason_id"})
 *    }
 * )
 */
class LegacyRecommendationPiReason implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\IdIdentity,
        Traits\LastModifiedByManyToOne,
        Traits\CreatedByManyToOne,
        Traits\CustomCreatedOnField,
        Traits\CustomLastModifiedOnField,
        Traits\CustomVersionField;

    /**
     * Legacy pi reason
     *
     * @var \Olcs\Db\Entity\LegacyPiReason
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\LegacyPiReason", fetch="LAZY")
     * @ORM\JoinColumn(name="legacy_pi_reason_id", referencedColumnName="id", nullable=false)
     */
    protected $legacyPiReason;

    /**
     * Legacy recommendation
     *
     * @var \Olcs\Db\Entity\LegacyRecommendation
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\LegacyRecommendation", fetch="LAZY")
     * @ORM\JoinColumn(name="legacy_recommendation_id", referencedColumnName="id", nullable=false)
     */
    protected $legacyRecommendation;

    /**
     * Comment
     *
     * @var string
     *
     * @ORM\Column(type="string", name="comment", length=30, nullable=true)
     */
    protected $comment;

    /**
     * Set the legacy pi reason
     *
     * @param \Olcs\Db\Entity\LegacyPiReason $legacyPiReason
     * @return LegacyRecommendationPiReason
     */
    public function setLegacyPiReason($legacyPiReason)
    {
        $this->legacyPiReason = $legacyPiReason;

        return $this;
    }

    /**
     * Get the legacy pi reason
     *
     * @return \Olcs\Db\Entity\LegacyPiReason
     */
    public function getLegacyPiReason()
    {
        return $this->legacyPiReason;
    }


    /**
     * Set the legacy recommendation
     *
     * @param \Olcs\Db\Entity\LegacyRecommendation $legacyRecommendation
     * @return LegacyRecommendationPiReason
     */
    public function setLegacyRecommendation($legacyRecommendation)
    {
        $this->legacyRecommendation = $legacyRecommendation;

        return $this;
    }

    /**
     * Get the legacy recommendation
     *
     * @return \Olcs\Db\Entity\LegacyRecommendation
     */
    public function getLegacyRecommendation()
    {
        return $this->legacyRecommendation;
    }


    /**
     * Set the comment
     *
     * @param string $comment
     * @return LegacyRecommendationPiReason
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get the comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

}
