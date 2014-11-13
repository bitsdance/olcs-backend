<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SiCategoryType Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedDate", timeAware=true)
 * @ORM\Table(name="si_category_type",
 *    indexes={
 *        @ORM\Index(name="fk_si_category_type_si_category1_idx", 
 *            columns={"si_category_id"}),
 *        @ORM\Index(name="fk_si_category_type_user1_idx", 
 *            columns={"created_by"}),
 *        @ORM\Index(name="fk_si_category_type_user2_idx", 
 *            columns={"last_modified_by"})
 *    }
 * )
 */
class SiCategoryType implements Interfaces\EntityInterface
{

    /**
     * Identifier - Id
     *
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string", name="id", length=8)
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
     * Si category
     *
     * @var \Olcs\Db\Entity\SiCategory
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\SiCategory", fetch="LAZY")
     * @ORM\JoinColumn(name="si_category_id", referencedColumnName="id", nullable=false)
     */
    protected $siCategory;

    /**
     * Description
     *
     * @var string
     *
     * @ORM\Column(type="string", name="description", length=255, nullable=true)
     */
    protected $description;

    /**
     * Deleted date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="deleted_date", nullable=true)
     */
    protected $deletedDate;

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
}
