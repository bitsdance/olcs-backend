<?php

namespace Dvsa\Olcs\Api\Entity\Permits;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Dvsa\Olcs\Api\Domain\Util\DateTime\DateTime;
use Dvsa\Olcs\Api\Entity\Generic\ApplicationPath;

/**
 * IrhpPermitType Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="irhp_permit_type",
 *    indexes={
 *        @ORM\Index(name="irhp_permit_type_ref_data_id_fk", columns={"name"}),
 *        @ORM\Index(name="fk_irhp_permit_type_created_by_user_id", columns={"created_by"}),
 *        @ORM\Index(name="fk_irhp_permit_type_last_modified_by_user_id",
     *     columns={"last_modified_by"})
 *    }
 * )
 */
class IrhpPermitType extends AbstractIrhpPermitType
{
    const IRHP_PERMIT_TYPE_ID_ECMT = 1;
    const IRHP_PERMIT_TYPE_ID_ECMT_SHORT_TERM = 2;
    const IRHP_PERMIT_TYPE_ID_ECMT_REMOVAL = 3;
    const IRHP_PERMIT_TYPE_ID_BILATERAL = 4;
    const IRHP_PERMIT_TYPE_ID_MULTILATERAL = 5;

    /**
     * Gets calculated values
     *
     * @return array
     */
    public function getCalculatedBundleValues()
    {
        return [
            'isEcmtAnnual' => $this->isEcmtAnnual(),
            'isEcmtShortTerm' => $this->isEcmtShortTerm(),
            'isEcmtRemoval' => $this->isEcmtRemoval(),
            'isBilateral' => $this->isBilateral(),
            'isMultilateral' => $this->isMultilateral(),
            'isApplicationPathEnabled' => $this->isApplicationPathEnabled(),
        ];
    }

    /**
     * Is this ECMT Annual
     *
     * @return bool
     */
    public function isEcmtAnnual()
    {
        return $this->id === self::IRHP_PERMIT_TYPE_ID_ECMT;
    }

    /**
     * Is this ECMT Short Term
     *
     * @return bool
     */
    public function isEcmtShortTerm()
    {
        return $this->id === self::IRHP_PERMIT_TYPE_ID_ECMT_SHORT_TERM;
    }

    /**
     * Is this ECMT Removal
     *
     * @return bool
     */
    public function isEcmtRemoval()
    {
        return $this->id === self::IRHP_PERMIT_TYPE_ID_ECMT_REMOVAL;
    }

    /**
     * Is this Bilateral
     *
     * @return bool
     */
    public function isBilateral()
    {
        return $this->id === self::IRHP_PERMIT_TYPE_ID_BILATERAL;
    }

    /**
     * Is this Multilateral
     *
     * @return bool
     */
    public function isMultilateral()
    {
        return $this->id === self::IRHP_PERMIT_TYPE_ID_MULTILATERAL;
    }

    /**
     * Is application path enabled
     *
     * @return bool
     */
    public function isApplicationPathEnabled()
    {
        return $this->isEcmtRemoval();
    }

    /**
     * Get an active application path
     *
     * @param \DateTime $dateTime DateTime to change against
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
        $criteria->where(
            $criteria->expr()->lte(
                'effectiveFrom',
                $dateTime->format(DateTime::ISO8601)
            )
        );
        $criteria->orderBy(['effectiveFrom' => Criteria::DESC]);
        $criteria->setMaxResults(1);

        $activeApplicationPaths = $this->getApplicationPaths()->matching($criteria);

        return !$activeApplicationPaths->isEmpty() ? $activeApplicationPaths->first() : null;
    }
}
