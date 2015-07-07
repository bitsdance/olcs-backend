<?php

namespace Dvsa\Olcs\Api\Entity\Task;

use Doctrine\ORM\Mapping as ORM;
use Dvsa\Olcs\Api\Entity\System\Category;
use Dvsa\Olcs\Api\Entity\System\SubCategory;

/**
 * Task Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="task",
 *    indexes={
 *        @ORM\Index(name="ix_task_assigned_to_user_id", columns={"assigned_to_user_id"}),
 *        @ORM\Index(name="ix_task_assigned_to_team_id", columns={"assigned_to_team_id"}),
 *        @ORM\Index(name="ix_task_assigned_by_user_id", columns={"assigned_by_user_id"}),
 *        @ORM\Index(name="ix_task_licence_id", columns={"licence_id"}),
 *        @ORM\Index(name="ix_task_application_id", columns={"application_id"}),
 *        @ORM\Index(name="ix_task_bus_reg_id", columns={"bus_reg_id"}),
 *        @ORM\Index(name="ix_task_transport_manager_id", columns={"transport_manager_id"}),
 *        @ORM\Index(name="ix_task_irfo_organisation_id", columns={"irfo_organisation_id"}),
 *        @ORM\Index(name="ix_task_created_by", columns={"created_by"}),
 *        @ORM\Index(name="ix_task_last_modified_by", columns={"last_modified_by"}),
 *        @ORM\Index(name="ix_task_category_id", columns={"category_id"}),
 *        @ORM\Index(name="ix_task_case_id", columns={"case_id"}),
 *        @ORM\Index(name="ix_task_sub_category_id", columns={"sub_category_id"}),
 *        @ORM\Index(name="ix_task_etl", columns={"description","category_id","sub_category_id"})
 *    },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="uk_task_olbs_key", columns={"olbs_key"})
 *    }
 * )
 */
class Task extends AbstractTask
{
    const STATUS_OPEN = 'tst_open';
    const STATUS_CLOSED = 'tst_closed';
    const STATUS_ALL = 'tst_all';

    const CATEGORY_ENVIRONMENTAL = 7;
    const CATEGORY_APPLICATION = 9;

    const SUBCATEGORY_FEE_DUE = 11;
    const SUBCATEGORY_REVIEW_COMPLAINT = 61;

    /**
     * Ref data constants
     */
    const TYPE_SIMPLE  = 'task_at_simple';
    const TYPE_MEDIUM  = 'task_at_medium';
    const TYPE_COMPLEX = 'task_at_complex';

    public function __construct(Category $category, SubCategory $subCategory)
    {
        $this->category = $category;
        $this->subCategory = $subCategory;
    }
}
