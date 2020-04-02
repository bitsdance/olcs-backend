<?php

namespace Dvsa\Olcs\Api\Entity\Generic;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Dvsa\Olcs\Api\Domain\Util\DateTime\DateTime;

/**
 * ApplicationPathGroup Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="application_path_group",
 *    indexes={
 *        @ORM\Index(name="ix_application_path_group_created_by", columns={"created_by"}),
 *        @ORM\Index(name="ix_application_path_group_last_modified_by", columns={"last_modified_by"})
 *    }
 * )
 */
class ApplicationPathGroup extends AbstractApplicationPathGroup
{
    const ECMT_SHORT_TERM_2020_APSG_WITHOUT_SECTORS_ID = 3;
    const ECMT_SHORT_TERM_2020_APSG_WITH_SECTORS_ID = 4;
    const ECMT_SHORT_TERM_2020_APGG = 5;

    const BILATERALS_CABOTAGE_PERMITS_ONLY_ID = 10;
    const BILATERALS_STANDARD_PERMITS_NO_CABOTAGE_ID = 11;
    const BILATERALS_STANDARD_AND_CABOTAGE_PERMITS_ID = 12;

    /**
     * Get an active application path
     *
     * @param \DateTime $dateTime DateTime to check against
     *
     * @return ApplicationPath|null
     */
    public function getActiveApplicationPath(\DateTime $dateTime = null)
    {
        if (!isset($dateTime)) {
            // get the latest active if specific datetime not provided
            $dateTime = new DateTime();
        }

        $criteria = Criteria::create();
        $criteria->where($criteria->expr()->lte('effectiveFrom', $dateTime));
        $criteria->orderBy(['effectiveFrom' => Criteria::DESC]);
        $criteria->setMaxResults(1);

        $activeApplicationPaths = $this->getApplicationPaths()->matching($criteria);

        return !$activeApplicationPaths->isEmpty() ? $activeApplicationPaths->first() : null;
    }

    /**
     * Whether this is a bilateral only application path group
     *
     * @return bool
     */
    public function isBilateralOnly()
    {
        return $this->id == self::BILATERALS_CABOTAGE_PERMITS_ONLY_ID;
    }
}
