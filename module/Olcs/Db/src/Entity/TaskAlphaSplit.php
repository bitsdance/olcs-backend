<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;

/**
 * TaskAlphaSplit Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="task_alpha_split",
 *    indexes={
 *        @ORM\Index(name="ix_task_alpha_split_task_allocation_rules_id", columns={"task_allocation_rules_id"}),
 *        @ORM\Index(name="ix_task_alpha_split_user_id", columns={"user_id"})
 *    }
 * )
 */
class TaskAlphaSplit implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\IdIdentity,
        Traits\UserManyToOneAlt1;

    /**
     * Split from inclusive
     *
     * @var string
     *
     * @ORM\Column(type="string", name="split_from_inclusive", length=2, nullable=false)
     */
    protected $splitFromInclusive;

    /**
     * Split to inclusive
     *
     * @var string
     *
     * @ORM\Column(type="string", name="split_to_inclusive", length=2, nullable=false)
     */
    protected $splitToInclusive;

    /**
     * Task allocation rules
     *
     * @var \Olcs\Db\Entity\TaskAllocationRule
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\TaskAllocationRule")
     * @ORM\JoinColumn(name="task_allocation_rules_id", referencedColumnName="id", nullable=false)
     */
    protected $taskAllocationRules;

    /**
     * Set the split from inclusive
     *
     * @param string $splitFromInclusive
     * @return TaskAlphaSplit
     */
    public function setSplitFromInclusive($splitFromInclusive)
    {
        $this->splitFromInclusive = $splitFromInclusive;

        return $this;
    }

    /**
     * Get the split from inclusive
     *
     * @return string
     */
    public function getSplitFromInclusive()
    {
        return $this->splitFromInclusive;
    }

    /**
     * Set the split to inclusive
     *
     * @param string $splitToInclusive
     * @return TaskAlphaSplit
     */
    public function setSplitToInclusive($splitToInclusive)
    {
        $this->splitToInclusive = $splitToInclusive;

        return $this;
    }

    /**
     * Get the split to inclusive
     *
     * @return string
     */
    public function getSplitToInclusive()
    {
        return $this->splitToInclusive;
    }

    /**
     * Set the task allocation rules
     *
     * @param \Olcs\Db\Entity\TaskAllocationRule $taskAllocationRules
     * @return TaskAlphaSplit
     */
    public function setTaskAllocationRules($taskAllocationRules)
    {
        $this->taskAllocationRules = $taskAllocationRules;

        return $this;
    }

    /**
     * Get the task allocation rules
     *
     * @return \Olcs\Db\Entity\TaskAllocationRule
     */
    public function getTaskAllocationRules()
    {
        return $this->taskAllocationRules;
    }
}
