<?php

namespace Dvsa\Olcs\Api\Entity\System;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemParameter Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="system_parameter")
 */
class SystemParameter extends AbstractSystemParameter
{
    const CNS_EMAIL_LIST = 'CNS_EMAIL_LIST';
    const DISABLED_SELFSERVE_CARD_PAYMENTS = 'DISABLED_SELFSERVE_CARD_PAYMENTS';
    const SELFSERVE_USER_PRINTER = 'SELFSERVE_USER_PRINTER';
    const RESOLVE_CARD_PAYMENTS_MIN_AGE = 'RESOLVE_CARD_PAYMENTS_MIN_AGE';
    const DISABLE_GDS_VERIFY_SIGNATURES = 'DISABLE_GDS_VERIFY_SIGNATURES';
    const DUPLICATE_VEHICLE_EMAIL_LIST = 'DUPLICATE_VEHICLE_EMAIL_LIST';
    const PSV_REPORT_EMAIL_LIST = 'PSV_REPORT_EMAIL_LIST';
    const INTERNATIONAL_GV_REPORT_EMAIL_TO = 'INTERNATIONAL_GV_REPORT_EMAIL_TO';
    const INTERNATIONAL_GV_REPORT_EMAIL_CC = 'INTERNATIONAL_GV_REPORT_EMAIL_CC';
    const DISABLE_DIGITAL_CONTINUATIONS = 'DISABLE_DIGITAL_CONTINUATIONS';
    const DISABLE_DATA_RETENTION_RECORDS = 'DISABLE_DATA_RETENTION_RECORDS';
    const DIGITAL_CONTINUATION_REMINDER_PERIOD = 'DIGITAL_CONT_REMINDER_PERIOD';
    const DISABLE_DATA_RETENTION_DOCUMENT_DELETE = 'DISABLE_DR_DOCUMENT_DELETE';
    const SYSTEM_DATA_RETENTION_USER = 'SYSTEM_DATA_RETENTION_USER';
    const DR_DELETE_LIMIT = 'DR_DELETE_LIMIT';
    const DISABLE_DATA_RETENTION_DELETE = 'DISABLE_DATA_RETENTION_DELETE';
    const DISABLE_UK_COMMUNITY_LIC_OFFICE = 'DISABLE_UK_COMMUNITY_LIC_OFFICE';
    const DISABLE_UK_COMMUNITY_LIC_REPRINT = 'DISABLE_UK_COMMUNITY_LIC_REPRINT';
}
