<?php

namespace Dvsa\Olcs\Api\Entity\Bus;

use Doctrine\ORM\Mapping as ORM;
use Dvsa\Olcs\Api\Entity\Bus\BusNoticePeriod as BusNoticePeriodEntity;
use Doctrine\ORM\Query;
use Dvsa\Olcs\Api\Domain\Exception\ForbiddenException;

/**
 * BusReg Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="bus_reg",
 *    indexes={
 *        @ORM\Index(name="ix_bus_reg_licence_id", columns={"licence_id"}),
 *        @ORM\Index(name="ix_bus_reg_bus_notice_period_id", columns={"bus_notice_period_id"}),
 *        @ORM\Index(name="ix_bus_reg_subsidised", columns={"subsidised"}),
 *        @ORM\Index(name="ix_bus_reg_created_by", columns={"created_by"}),
 *        @ORM\Index(name="ix_bus_reg_last_modified_by", columns={"last_modified_by"}),
 *        @ORM\Index(name="ix_bus_reg_withdrawn_reason", columns={"withdrawn_reason"}),
 *        @ORM\Index(name="ix_bus_reg_status", columns={"status"}),
 *        @ORM\Index(name="ix_bus_reg_revert_status", columns={"revert_status"}),
 *        @ORM\Index(name="ix_bus_reg_reg_no", columns={"reg_no"}),
 *        @ORM\Index(name="fk_bus_reg_parent_id_bus_reg_id", columns={"parent_id"}),
 *        @ORM\Index(name="fk_bus_reg_operating_centre1", columns={"operating_centre_id"})
 *    },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="uk_bus_reg_olbs_key", columns={"olbs_key"})
 *    }
 * )
 */
class BusReg extends AbstractBusReg
{
    const STATUS_NEW = 'breg_s_new';
    const STATUS_VAR = 'breg_s_var';
    const STATUS_CANCEL = 'breg_s_cancellation';
    const STATUS_ADMIN = 'breg_s_admin';
    const STATUS_REGISTERED = 'breg_s_registered';
    const STATUS_REFUSED = 'breg_s_refused';
    const STATUS_WITHDRAWN = 'breg_s_withdrawn';
    const STATUS_CNS = 'breg_s_cns';
    const STATUS_CANCELLED = 'breg_s_cancelled';

    const FORBIDDEN_ERROR = 'This bus reg can\'t be edited. It must be the latest variation, and not from EBSR';

    /**
     * A bus reg may only be edited if it's the latest variation, and the record didn't come from EBSR
     *
     * @return bool
     * @throws ForbiddenException
     */
    public function canEdit()
    {
        if (!$this->isFromEbsr() && $this->isLatestVariation()) {
            return true;
        }

        throw new ForbiddenException('No permission to edit this record');
    }

    /**
     * Returns whether the variation is the latest one
     *
     * @return bool
     */
    public function isLatestVariation()
    {
        return $this->getId() === $this->getLicence()->getLatestBusVariation($this->getRegNo())->getId();
    }

    /**
     * Returns whether the record is from EBSR
     *
     * @return bool
     */
    public function isFromEbsr()
    {
        return ($this->isTxcApp === 'Y' ? true : false);
    }

    /**
     * Gets calculated values
     *
     * @return array
     */
    public function getCalculatedValues()
    {
        return [
            'licence' => null,
            'parent' => null,
            'isLatestVariation' => $this->isLatestVariation(),
            'shortNotice' => null
        ];
    }

    /**
     * @param $useAllStops
     * @param $hasManoeuvre
     * @param $manoeuvreDetail
     * @param $needNewStop
     * @param $newStopDetail
     * @param $hasNotFixedStop
     * @param $notFixedStopDetail
     * @param $subsidised
     * @param $subsidyDetail
     * @return bool
     */
    public function updateStops(
        $useAllStops,
        $hasManoeuvre,
        $manoeuvreDetail,
        $needNewStop,
        $newStopDetail,
        $hasNotFixedStop,
        $notFixedStopDetail,
        $subsidised,
        $subsidyDetail
    ) {
        $this->canEdit();

        $this->setUseAllStops($useAllStops);
        $this->setHasManoeuvre($hasManoeuvre);
        $this->setManoeuvreDetail($manoeuvreDetail);
        $this->setNeedNewStop($needNewStop);
        $this->setNewStopDetail($newStopDetail);
        $this->setHasNotFixedStop($hasNotFixedStop);
        $this->setNotFixedStopDetail($notFixedStopDetail);
        $this->setSubsidised($subsidised);
        $this->setSubsidyDetail($subsidyDetail);

        return true;
    }

    /**
     * @param $isQualityPartnership
     * @param $qualityPartnershipDetails
     * @param $qualityPartnershipFacilitiesUsed
     * @param $isQualityContract
     * @param $qualityContractDetails
     * @return bool
     */
    public function updateQualitySchemes(
        $isQualityPartnership,
        $qualityPartnershipDetails,
        $qualityPartnershipFacilitiesUsed,
        $isQualityContract,
        $qualityContractDetails
    ) {
        $this->canEdit();

        $this->setIsQualityPartnership($isQualityPartnership);
        $this->setQualityPartnershipDetails($qualityPartnershipDetails);
        $this->setQualityPartnershipFacilitiesUsed($qualityPartnershipFacilitiesUsed);
        $this->setIsQualityContract($isQualityContract);
        $this->setQualityContractDetails($qualityContractDetails);

        return true;
    }

    /**
     * @param $stoppingArrangements
     * @return bool
     */
    public function updateTaAuthority($stoppingArrangements)
    {
        $this->canEdit();

        $this->stoppingArrangements = $stoppingArrangements;

        return true;
    }

    public function updateServiceDetails(
        $serviceNo,
        $startPoint,
        $finishPoint,
        $via,
        $otherDetails,
        $receivedDate,
        $effectiveDate,
        $endDate,
        $busNoticePeriod,
        $busRules
    ) {
        $this->canEdit();

        $this->serviceNo = $serviceNo;
        $this->startPoint = $startPoint;
        $this->finishPoint = $finishPoint;
        $this->via = $via;
        $this->otherDetails = $otherDetails;
        $this->busNoticePeriod = $busNoticePeriod;

        $receivedDateTime = \DateTime::createFromFormat('Y-m-d', $receivedDate);
        $effectiveDateTime = \DateTime::createFromFormat('Y-m-d', $effectiveDate);
        $endDateTime = \DateTime::createFromFormat('Y-m-d', $endDate);

        if (!$receivedDateTime instanceof \DateTime) {
            $receivedDateTime = null;
        }

        if (!$effectiveDateTime instanceof \DateTime) {
            $effectiveDateTime = null;
        }

        if (!$endDateTime instanceof \DateTime) {
            $endDateTime = null;
        }

        $this->receivedDate = $receivedDateTime;
        $this->effectiveDate = $effectiveDateTime;
        $this->endDate = $endDateTime;

        $this->isShortNotice = 'N';

        if ($this->isShortNotice($effectiveDateTime, $receivedDateTime, $busRules)) {
            $this->isShortNotice = 'Y';
        }

        return true;
    }

    /**
     * @param \DateTime $effectiveDate
     * @param \DateTime $receivedDate
     * @param BusNoticePeriodEntity $busRules
     * @return bool|null
     */
    private function isShortNotice($effectiveDate, $receivedDate, BusNoticePeriodEntity $busRules)
    {
        if (!($effectiveDate instanceof \DateTime) || !($receivedDate instanceof \DateTime)) {
            return false;
        }

        $standardPeriod = $busRules->getStandardPeriod();

        if ($standardPeriod > 0) {
            $interval = new \DateInterval('P' . $standardPeriod . 'D');

            if (clone $receivedDate->add($interval) >= $effectiveDate) {
                return true;
            }
        }

        $cancellationPeriod = $busRules->getCancellationPeriod();
        $variationNo = $this->getVariationNo();

        if ($cancellationPeriod > 0 && $variationNo > 0) {
            $parent = $this->getParent();

            if (!$parent) {
                //if we don't have a parent record, the result is undefined.
                return null;
            }

            $lastDateTime = $parent->getEffectiveDate();
            $interval = new \DateInterval('P' . $cancellationPeriod . 'D');

            if (clone $lastDateTime->add($interval) >= $effectiveDate) {
                return true;
            }
        }

        return false;
    }
}
