<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;

/**
 * OperatingCentreOpposition Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="operating_centre_opposition",
 *    indexes={
 *        @ORM\Index(name="fk_opposition_operating_centre_opposition1_idx", columns={"opposition_id"}),
 *        @ORM\Index(name="fk_opposition_operating_centre_operating_centre1_idx", columns={"operating_centre_id"}),
 *        @ORM\Index(name="fk_operating_centre_opposition_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_operating_centre_opposition_user2_idx", columns={"last_modified_by"})
 *    }
 * )
 */
class OperatingCentreOpposition implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\IdIdentity,
        Traits\LastModifiedByManyToOne,
        Traits\CreatedByManyToOne,
        Traits\OperatingCentreManyToOne,
        Traits\CustomCreatedOnField,
        Traits\CustomLastModifiedOnField,
        Traits\CustomVersionField;

    /**
     * Opposition
     *
     * @var \Olcs\Db\Entity\Opposition
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\Opposition")
     * @ORM\JoinColumn(name="opposition_id", referencedColumnName="id", nullable=false)
     */
    protected $opposition;

    /**
     * Set the opposition
     *
     * @param \Olcs\Db\Entity\Opposition $opposition
     * @return OperatingCentreOpposition
     */
    public function setOpposition($opposition)
    {
        $this->opposition = $opposition;

        return $this;
    }

    /**
     * Get the opposition
     *
     * @return \Olcs\Db\Entity\Opposition
     */
    public function getOpposition()
    {
        return $this->opposition;
    }
}
