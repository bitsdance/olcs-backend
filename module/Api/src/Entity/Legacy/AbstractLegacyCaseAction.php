<?php

namespace Dvsa\Olcs\Api\Entity\Legacy;

use Doctrine\ORM\Mapping as ORM;

/**
 * LegacyCaseAction Abstract Entity
 *
 * Auto-Generated
 *
 * @ORM\Table(name="legacy_case_action")
 */
abstract class AbstractLegacyCaseAction
{

    /**
     * Description
     *
     * @var string
     *
     * @ORM\Column(type="string", name="description", length=45, nullable=true)
     */
    protected $description;

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
     * Is driver
     *
     * @var string
     *
     * @ORM\Column(type="yesno", name="is_driver", nullable=false, options={"default": 0})
     */
    protected $isDriver = 0;

    /**
     * Set the description
     *
     * @param string $description
     * @return LegacyCaseAction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the id
     *
     * @param int $id
     * @return LegacyCaseAction
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
     * Set the is driver
     *
     * @param string $isDriver
     * @return LegacyCaseAction
     */
    public function setIsDriver($isDriver)
    {
        $this->isDriver = $isDriver;

        return $this;
    }

    /**
     * Get the is driver
     *
     * @return string
     */
    public function getIsDriver()
    {
        return $this->isDriver;
    }


}
