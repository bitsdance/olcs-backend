<?php

use Dvsa\Olcs\Transfer\Query as TransferQuery;
use Dvsa\Olcs\Api\Domain\Query;
use Dvsa\Olcs\Api\Domain\QueryHandler;
use Dvsa\Olcs\Api\Domain\Query\Bookmark as BookmarkQuery;
use Dvsa\Olcs\Api\Domain\QueryHandler\Bookmark as BookmarkQueryHandler;
use Dvsa\Olcs\Api\Domain\Query\Queue as QueueQuery;
use Dvsa\Olcs\Api\Domain\QueryHandler\Queue as QueueQueryHandler;

return [
    // Bookmarks
    BookmarkQuery\LicenceBundle::class => BookmarkQueryHandler\LicenceBundle::class,
    BookmarkQuery\TransportManagerBundle::class => BookmarkQueryHandler\TransportManagerBundle::class,
    BookmarkQuery\DocParagraphBundle::class => BookmarkQueryHandler\DocParagraphBundle::class,
    BookmarkQuery\OppositionBundle::class => BookmarkQueryHandler\OppositionBundle::class,
    BookmarkQuery\StatementBundle::class => BookmarkQueryHandler\StatementBundle::class,
    BookmarkQuery\CommunityLicBundle::class => BookmarkQueryHandler\CommunityLicBundle::class,
    BookmarkQuery\FeeBundle::class => BookmarkQueryHandler\FeeBundle::class,
    BookmarkQuery\ApplicationBundle::class => BookmarkQueryHandler\ApplicationBundle::class,
    BookmarkQuery\InterimUnlinkedTm::class => BookmarkQueryHandler\InterimUnlinkedTm::class,
    BookmarkQuery\InterimOperatingCentres::class => BookmarkQueryHandler\InterimOperatingCentres::class,
    BookmarkQuery\UserBundle::class => BookmarkQueryHandler\UserBundle::class,
    BookmarkQuery\BusRegBundle::class => BookmarkQueryHandler\BusRegBundle::class,
    BookmarkQuery\PublicationLinkBundle::class => BookmarkQueryHandler\PublicationLinkBundle::class,
    BookmarkQuery\PublicationBundle::class => BookmarkQueryHandler\PublicationBundle::class,
    BookmarkQuery\PublicationLatestByTaAndTypeBundle::class
        => BookmarkQueryHandler\PublicationLatestByTaAndTypeBundle::class,
    BookmarkQuery\ConditionsUndertakings::class => BookmarkQueryHandler\ConditionsUndertakings::class,
    BookmarkQuery\GoodsDiscBundle::class => BookmarkQueryHandler\GoodsDiscBundle::class,
    BookmarkQuery\PsvDiscBundle::class => BookmarkQueryHandler\PsvDiscBundle::class,
    BookmarkQuery\InterimConditionsUndertakings::class
        => BookmarkQueryHandler\InterimConditionsUndertakings::class,
    BookmarkQuery\FStandingAdditionalVeh::class => BookmarkQueryHandler\FStandingAdditionalVeh::class,
    BookmarkQuery\FStandingCapitalReserves::class => BookmarkQueryHandler\FStandingCapitalReserves::class,
    BookmarkQuery\PiHearingBundle::class => BookmarkQueryHandler\PiHearingBundle::class,
    BookmarkQuery\PiVenueBundle::class => BookmarkQueryHandler\PiVenueBundle::class,
    BookmarkQuery\PreviousHearingBundle::class => BookmarkQueryHandler\PreviousHearing::class,
    BookmarkQuery\PreviousPublicationByPi::class => BookmarkQueryHandler\PreviousPublication::class,
    BookmarkQuery\PreviousPublicationByApplication::class => BookmarkQueryHandler\PreviousPublication::class,
    BookmarkQuery\PreviousPublicationByLicence::class => BookmarkQueryHandler\PreviousPublication::class,
    BookmarkQuery\TotalContFee::class => BookmarkQueryHandler\TotalContFee::class,
    BookmarkQuery\VehicleBundle::class => BookmarkQueryHandler\VehicleBundle::class,

    // Application
    TransferQuery\Application\Application::class => QueryHandler\Application\Application::class,
    TransferQuery\Application\FinancialHistory::class => QueryHandler\Application\FinancialHistory::class,
    TransferQuery\Application\FinancialEvidence::class => QueryHandler\Application\FinancialEvidence::class,
    TransferQuery\Application\PreviousConvictions::class => QueryHandler\Application\PreviousConvictions::class,
    TransferQuery\Application\Safety::class => QueryHandler\Application\Safety::class,
    TransferQuery\Application\Declaration::class => QueryHandler\Application\Declaration::class,
    TransferQuery\Application\LicenceHistory::class => QueryHandler\Application\LicenceHistory::class,
    TransferQuery\Application\TransportManagers::class => QueryHandler\Application\TransportManagers::class,
    TransferQuery\Application\GoodsVehicles::class => QueryHandler\Application\GoodsVehicles::class,
    TransferQuery\Application\VehicleDeclaration::class => QueryHandler\Application\VehicleDeclaration::class,
    TransferQuery\Application\Review::class => QueryHandler\Application\Review::class,
    TransferQuery\Application\Overview::class => QueryHandler\Application\Overview::class,
    TransferQuery\Application\EnforcementArea::class => QueryHandler\Application\EnforcementArea::class,
    TransferQuery\Application\Grant::class => QueryHandler\Application\Grant::class,
    TransferQuery\Application\People::class => QueryHandler\Application\People::class,
    TransferQuery\Application\OperatingCentre::class => QueryHandler\Application\OperatingCentre::class,
    TransferQuery\Application\TaxiPhv::class => QueryHandler\Application\TaxiPhv::class,
    TransferQuery\Application\Interim::class => QueryHandler\Application\Interim::class,
    TransferQuery\Application\GetList::class => QueryHandler\Application\GetList::class,
    TransferQuery\Application\OperatingCentres::class => QueryHandler\Application\OperatingCentres::class,
    TransferQuery\Application\PsvVehicles::class => QueryHandler\Application\PsvVehicles::class,

    // Licence
    TransferQuery\Licence\BusinessDetails::class => QueryHandler\Licence\BusinessDetails::class,
    TransferQuery\Licence\Licence::class => QueryHandler\Licence\Licence::class,
    TransferQuery\Licence\LicenceByNumber::class => QueryHandler\Licence\LicenceByNumber::class,
    TransferQuery\Licence\TypeOfLicence::class => QueryHandler\Licence\TypeOfLicence::class,
    TransferQuery\Licence\Safety::class => QueryHandler\Licence\Safety::class,
    TransferQuery\Licence\Addresses::class => QueryHandler\Licence\Addresses::class,
    TransferQuery\Licence\TransportManagers::class => QueryHandler\Licence\TransportManagers::class,
    TransferQuery\Licence\PsvDiscs::class => QueryHandler\Licence\PsvDiscs::class,
    TransferQuery\Licence\GoodsVehicles::class => QueryHandler\Licence\GoodsVehicles::class,
    TransferQuery\Licence\OtherActiveLicences::class => QueryHandler\Licence\OtherActiveLicences::class,
    TransferQuery\Licence\LicenceDecisions::class => QueryHandler\Licence\LicenceDecisions::class,
    TransferQuery\Licence\Overview::class => QueryHandler\Licence\Overview::class,
    TransferQuery\Licence\EnforcementArea::class => QueryHandler\Licence\EnforcementArea::class,
    TransferQuery\Licence\ConditionUndertaking::class => QueryHandler\Licence\ConditionUndertaking::class,
    TransferQuery\Licence\People::class => QueryHandler\Licence\People::class,
    TransferQuery\Licence\OperatingCentre::class => QueryHandler\Licence\OperatingCentre::class,
    TransferQuery\Licence\TaxiPhv::class => QueryHandler\Licence\TaxiPhv::class,
    TransferQuery\Licence\Markers::class => QueryHandler\Licence\Markers::class,
    TransferQuery\Licence\ContinuationDetail::class => QueryHandler\Licence\ContinuationDetail::class,
    TransferQuery\Licence\GetList::class => QueryHandler\Licence\GetList::class,
    TransferQuery\Licence\OperatingCentres::class => QueryHandler\Licence\OperatingCentres::class,
    TransferQuery\Licence\PsvVehicles::class => QueryHandler\Licence\PsvVehicles::class,
    Query\Licence\ContinuationNotSoughtList::class => QueryHandler\Licence\ContinuationNotSoughtList::class,

    // LicenceStatusRule
    TransferQuery\LicenceStatusRule\LicenceStatusRule::class => QueryHandler\LicenceStatusRule\LicenceStatusRule::class,

    // Other Licence
    TransferQuery\OtherLicence\OtherLicence::class => QueryHandler\OtherLicence\OtherLicence::class,
    TransferQuery\OtherLicence\GetList::class => QueryHandler\OtherLicence\GetList::class,

    // Organisation
    TransferQuery\Organisation\BusinessDetails::class => QueryHandler\Organisation\BusinessDetails::class,
    TransferQuery\Organisation\Organisation::class => QueryHandler\Organisation\Organisation::class,
    TransferQuery\Organisation\OutstandingFees::class => QueryHandler\Organisation\OutstandingFees::class,
    TransferQuery\Organisation\Dashboard::class => QueryHandler\Organisation\Dashboard::class,
    TransferQuery\Organisation\People::class => QueryHandler\Organisation\People::class,
    TransferQuery\Organisation\CpidOrganisation::class
        => QueryHandler\Organisation\CpidOrganisation::class,
    TransferQuery\Organisation\UnlicensedCases::class => QueryHandler\Organisation\UnlicensedCases::class,

    // Variation
    TransferQuery\Variation\Variation::class => QueryHandler\Variation\Variation::class,
    TransferQuery\Variation\TypeOfLicence::class => QueryHandler\Variation\TypeOfLicence::class,
    TransferQuery\Variation\GoodsVehicles::class => QueryHandler\Variation\GoodsVehicles::class,
    TransferQuery\Variation\PsvVehicles::class => QueryHandler\Variation\PsvVehicles::class,

    // Cases
    TransferQuery\Cases\Cases::class => QueryHandler\Cases\Cases::class,
    TransferQuery\Cases\CasesWithOppositionDates::class => QueryHandler\Cases\CasesWithOppositionDates::class,
    TransferQuery\Cases\CasesWithLicence::class => QueryHandler\Cases\CasesWithLicence::class,
    TransferQuery\Cases\Pi::class => QueryHandler\Cases\Pi::class,
    TransferQuery\Cases\Pi\Hearing::class => QueryHandler\Cases\Pi\Hearing::class,
    TransferQuery\Cases\Pi\HearingList::class => QueryHandler\Cases\Pi\HearingList::class,
    TransferQuery\Cases\AnnualTestHistory::class => QueryHandler\Cases\AnnualTestHistory::class,
    TransferQuery\Cases\LegacyOffence::class => QueryHandler\Cases\LegacyOffence::class,
    TransferQuery\Cases\LegacyOffenceList::class => QueryHandler\Cases\LegacyOffenceList::class,
    TransferQuery\Cases\Impounding\ImpoundingList::class => QueryHandler\Cases\Impounding\ImpoundingList::class,
    TransferQuery\Cases\Impounding\Impounding::class => QueryHandler\Cases\Impounding\Impounding::class,
    TransferQuery\Cases\ConditionUndertaking\ConditionUndertaking::class =>
        QueryHandler\Cases\ConditionUndertaking\ConditionUndertaking::class,
    TransferQuery\Cases\ConditionUndertaking\ConditionUndertakingList::class =>
        QueryHandler\Cases\ConditionUndertaking\ConditionUndertakingList::class,
    TransferQuery\Cases\ProposeToRevoke\ProposeToRevokeByCase::class
        => QueryHandler\Cases\ProposeToRevoke\ProposeToRevokeByCase::class,

    TransferQuery\Cases\Hearing\Appeal::class => QueryHandler\Cases\Hearing\Appeal::class,
    TransferQuery\Cases\Hearing\AppealByCase::class => QueryHandler\Cases\Hearing\Appeal::class,
    TransferQuery\Cases\Hearing\AppealList::class => QueryHandler\Cases\Hearing\AppealList::class,

    TransferQuery\Cases\Hearing\Stay::class => QueryHandler\Cases\Hearing\Stay::class,
    TransferQuery\Cases\Hearing\StayByCase::class => QueryHandler\Cases\Hearing\Stay::class,
    TransferQuery\Cases\Hearing\StayList::class => QueryHandler\Cases\Hearing\StayList::class,

    TransferQuery\Cases\Statement\Statement::class => QueryHandler\Cases\Statement\Statement::class,
    TransferQuery\Cases\Statement\StatementList::class => QueryHandler\Cases\Statement\StatementList::class,
    TransferQuery\Cases\ByTransportManager::class => QueryHandler\Cases\ByTransportManager::class,
    TransferQuery\Cases\ByLicence::class => QueryHandler\Cases\ByLicence::class,

    // Submission
    TransferQuery\Submission\SubmissionAction::class => QueryHandler\Submission\SubmissionAction::class,
    TransferQuery\Submission\SubmissionSectionComment::class => QueryHandler\Submission\SubmissionSectionComment::class,
    TransferQuery\Submission\Submission::class => QueryHandler\Submission\Submission::class,
    TransferQuery\Submission\SubmissionList::class => QueryHandler\Submission\SubmissionList::class,

    // Processing
    TransferQuery\Processing\History::class => QueryHandler\Processing\History::class,
    TransferQuery\Processing\Note::class => QueryHandler\Processing\Note::class,
    TransferQuery\Processing\NoteList::class => QueryHandler\Processing\NoteList::class,

    // Conviction - NOT Previous Conviction
    TransferQuery\Cases\Conviction\Conviction::class => QueryHandler\Cases\Conviction\Conviction::class,
    TransferQuery\Cases\Conviction\ConvictionList::class => QueryHandler\Cases\Conviction\ConvictionList::class,

    // NonPi
    TransferQuery\Cases\NonPi\Single::class => QueryHandler\Cases\NonPi\Single::class,
    TransferQuery\Cases\NonPi\Listing::class => QueryHandler\Cases\NonPi\Listing::class,

    // Prohibition
    TransferQuery\Cases\Prohibition\Prohibition::class => QueryHandler\Cases\Prohibition\Prohibition::class,
    TransferQuery\Cases\Prohibition\ProhibitionList::class
        => QueryHandler\Cases\Prohibition\ProhibitionList::class,

    // Prohibition / Defect
    TransferQuery\Cases\Prohibition\Defect::class => QueryHandler\Cases\Prohibition\Defect::class,
    TransferQuery\Cases\Prohibition\DefectList::class => QueryHandler\Cases\Prohibition\DefectList::class,

    // Previous Conviction
    TransferQuery\PreviousConviction\PreviousConviction::class
        => QueryHandler\PreviousConviction\PreviousConviction::class,
    TransferQuery\PreviousConviction\GetList::class => QueryHandler\PreviousConviction\GetList::class,

    // Company Subsidiary
    TransferQuery\CompanySubsidiary\CompanySubsidiary::class
        => QueryHandler\CompanySubsidiary\CompanySubsidiary::class,

    // Bus
    TransferQuery\Bus\BusReg::class => QueryHandler\Bus\Bus::class,
    TransferQuery\Bus\BusRegDecision::class => QueryHandler\Bus\BusRegDecision::class,
    TransferQuery\Bus\ShortNoticeByBusReg::class => QueryHandler\Bus\ShortNoticeByBusReg::class,
    TransferQuery\Bus\RegistrationHistoryList::class => QueryHandler\Bus\RegistrationHistoryList::class,
    TransferQuery\Bus\ByLicenceRoute::class => QueryHandler\Bus\ByLicenceRoute::class,

    // Trailer
    TransferQuery\Licence\Trailers::class => QueryHandler\Licence\Trailers::class,
    TransferQuery\Trailer\Trailer::class => QueryHandler\Trailer\Trailer::class,

    // Grace Periods
    TransferQuery\GracePeriod\GracePeriod::class => QueryHandler\GracePeriod\GracePeriod::class,
    TransferQuery\GracePeriod\GracePeriods::class => QueryHandler\GracePeriod\GracePeriods::class,

    // Irfo
    TransferQuery\Irfo\IrfoDetails::class => QueryHandler\Irfo\IrfoDetails::class,
    TransferQuery\Irfo\IrfoGvPermit::class => QueryHandler\Irfo\IrfoGvPermit::class,
    TransferQuery\Irfo\IrfoGvPermitList::class => QueryHandler\Irfo\IrfoGvPermitList::class,
    TransferQuery\Irfo\IrfoPermitStockList::class => QueryHandler\Irfo\IrfoPermitStockList::class,
    TransferQuery\Irfo\IrfoPsvAuth::class => QueryHandler\Irfo\IrfoPsvAuth::class,
    TransferQuery\Irfo\IrfoPsvAuthList::class => QueryHandler\Irfo\IrfoPsvAuthList::class,

    // Publication
    TransferQuery\Publication\Recipient::class => QueryHandler\Publication\Recipient::class,
    TransferQuery\Publication\RecipientList::class => QueryHandler\Publication\RecipientList::class,

    // My Account
    TransferQuery\MyAccount\MyAccount::class => QueryHandler\MyAccount\MyAccount::class,

    // User
    TransferQuery\User\Partner::class => QueryHandler\User\Partner::class,
    TransferQuery\User\PartnerList::class => QueryHandler\User\PartnerList::class,
    TransferQuery\User\User::class => QueryHandler\User\User::class,
    TransferQuery\User\UserList::class => QueryHandler\User\UserList::class,
    TransferQuery\User\UserSelfserve::class => QueryHandler\User\UserSelfserve::class,
    TransferQuery\User\UserListSelfserve::class => QueryHandler\User\UserListSelfserve::class,

    // Workshop
    TransferQuery\Workshop\Workshop::class => QueryHandler\Workshop\Workshop::class,

    // Correspondence
    TransferQuery\Correspondence\Correspondence::class => QueryHandler\Correspondence\Correspondence::class,
    TransferQuery\Correspondence\Correspondences::class => QueryHandler\Correspondence\Correspondences::class,

    // Transaction (formerly 'Payment')
    TransferQuery\Transaction\Transaction::class => QueryHandler\Transaction\Transaction::class,
    TransferQuery\Transaction\TransactionByReference::class => QueryHandler\Transaction\TransactionByReference::class,

    // CommunityLic
    TransferQuery\CommunityLic\CommunityLic::class => QueryHandler\CommunityLic\CommunityLic::class,

    // Document
    TransferQuery\Document\TemplateParagraphs::class => QueryHandler\Document\TemplateParagraphs::class,
    TransferQuery\Document\Document::class => QueryHandler\Document\Document::class,
    TransferQuery\Document\Letter::class => QueryHandler\Document\Letter::class,
    TransferQuery\Document\DocumentList::class => QueryHandler\Document\DocumentList::class,
    TransferQuery\Document\Download::class => QueryHandler\Document\Download::class,

    // Transport Manager Application
    TransferQuery\TransportManagerApplication\GetDetails::class
        => QueryHandler\TransportManagerApplication\GetDetails::class,
    TransferQuery\TransportManagerApplication\GetList::class
        => QueryHandler\TransportManagerApplication\GetList::class,
    TransferQuery\TransportManagerApplication\GetForResponsibilities::class
        => QueryHandler\TransportManagerApplication\GetForResponsibilities::class,

    // Transport Manager Licence
    TransferQuery\TransportManagerLicence\GetForResponsibilities::class
        => QueryHandler\TransportManagerLicence\GetForResponsibilities::class,
    TransferQuery\TransportManagerLicence\GetList::class
        => QueryHandler\TransportManagerLicence\GetList::class,

    // TmEmployment
    TransferQuery\TmEmployment\GetSingle::class => QueryHandler\TmEmployment\GetSingle::class,
    TransferQuery\TmEmployment\GetList::class => QueryHandler\TmEmployment\GetList::class,

    // Bus Reg History View
    TransferQuery\Bus\HistoryList::class => QueryHandler\Bus\HistoryList::class,

    // Fee
    TransferQuery\Fee\Fee::class => QueryHandler\Fee\Fee::class,
    TransferQuery\Fee\FeeList::class => QueryHandler\Fee\FeeList::class,

    // Operator
    TransferQuery\Operator\BusinessDetails::class => QueryHandler\Operator\BusinessDetails::class,
    TransferQuery\Operator\UnlicensedBusinessDetails::class => QueryHandler\Operator\UnlicensedBusinessDetails::class,
    TransferQuery\Operator\UnlicensedVehicles::class => QueryHandler\Operator\UnlicensedVehicles::class,

    // Licence Vehicle
    TransferQuery\LicenceVehicle\LicenceVehicle::class => QueryHandler\LicenceVehicle\LicenceVehicle::class,
    TransferQuery\LicenceVehicle\PsvLicenceVehicle::class => QueryHandler\LicenceVehicle\PsvLicenceVehicle::class,

    // Inspection Request
    TransferQuery\InspectionRequest\OperatingCentres::class => QueryHandler\InspectionRequest\OperatingCentres::class,

    // Opposition
    TransferQuery\Opposition\Opposition::class => QueryHandler\Opposition\Opposition::class,
    TransferQuery\Opposition\OppositionList::class => QueryHandler\Opposition\OppositionList::class,

    // Complaint
    TransferQuery\Complaint\Complaint::class => QueryHandler\Complaint\Complaint::class,
    TransferQuery\Complaint\ComplaintList::class => QueryHandler\Complaint\ComplaintList::class,
    TransferQuery\EnvironmentalComplaint\EnvironmentalComplaint::class =>
        QueryHandler\EnvironmentalComplaint\EnvironmentalComplaint::class,
    TransferQuery\EnvironmentalComplaint\EnvironmentalComplaintList::class =>
        QueryHandler\EnvironmentalComplaint\EnvironmentalComplaintList::class,

    // Inspection Request
    TransferQuery\InspectionRequest\OperatingCentres::class => QueryHandler\InspectionRequest\OperatingCentres::class,
    TransferQuery\InspectionRequest\ApplicationInspectionRequestList::class =>
        QueryHandler\InspectionRequest\ApplicationInspectionRequestList::class,
    TransferQuery\InspectionRequest\LicenceInspectionRequestList::class =>
        QueryHandler\InspectionRequest\LicenceInspectionRequestList::class,
    TransferQuery\InspectionRequest\InspectionRequest::class => QueryHandler\InspectionRequest\InspectionRequest::class,

    // Change of Entity
    TransferQuery\ChangeOfEntity\ChangeOfEntity::class => QueryHandler\ChangeOfEntity\ChangeOfEntity::class,

    // ConditionUndertaking
    TransferQuery\ConditionUndertaking\GetList::class => QueryHandler\ConditionUndertaking\GetList::class,
    TransferQuery\ConditionUndertaking\Get::class => QueryHandler\ConditionUndertaking\Get::class,

    // Task
    TransferQuery\Task\TaskList::class => QueryHandler\Task\TaskList::class,
    TransferQuery\Task\Task::class => QueryHandler\Task\Task::class,
    TransferQuery\Task\TaskDetails::class => QueryHandler\Task\TaskDetails::class,

    // Tm Responsibilities
    TransferQuery\TmResponsibilities\TmResponsibilitiesList::class =>
        QueryHandler\TmResponsibilities\TmResponsibilitiesList::class,
    TransferQuery\TmResponsibilities\GetDocumentsForResponsibilities::class =>
        QueryHandler\TmResponsibilities\GetDocumentsForResponsibilities::class,

    // Companies House
    TransferQuery\CompaniesHouse\AlertList::class => QueryHandler\CompaniesHouse\AlertList::class,

    // Queue
    QueueQuery\NextItem::class => QueueQueryHandler\NextItem::class,

    // TmCaseDecision
    TransferQuery\TmCaseDecision\GetByCase::class =>
        QueryHandler\TmCaseDecision\GetByCase::class,

    // TmQualification
    TransferQuery\TmQualification\TmQualificationsList::class =>
        QueryHandler\TmQualification\TmQualificationsList::class,
    TransferQuery\TmQualification\TmQualification::class => QueryHandler\TmQualification\TmQualification::class,
    TransferQuery\TmQualification\Documents::class => QueryHandler\TmQualification\Documents::class,

    // Transport Manager
    TransferQuery\Tm\TransportManager::class => QueryHandler\Tm\TransportManager::class,
    TransferQuery\Tm\Documents::class => QueryHandler\Tm\Documents::class,

    // Search
    TransferQuery\Search\Licence::class => QueryHandler\Search\Licence::class,

    // Application Operating Centres
    TransferQuery\ApplicationOperatingCentre\ApplicationOperatingCentre::class
        => QueryHandler\ApplicationOperatingCentre\ApplicationOperatingCentre::class,

    // Licence Operating Centres
    TransferQuery\LicenceOperatingCentre\LicenceOperatingCentre::class
        => QueryHandler\LicenceOperatingCentre\LicenceOperatingCentre::class,

    // Variation Operating Centres
    TransferQuery\VariationOperatingCentre\VariationOperatingCentre::class
        => QueryHandler\VariationOperatingCentre\VariationOperatingCentre::class,

    // Organisation Person
   TransferQuery\OrganisationPerson\GetSingle::class => QueryHandler\OrganisationPerson\GetSingle::class,

    // Disc Printing
    TransferQuery\DiscSequence\DiscPrefixes::class => QueryHandler\DiscSequence\DiscPrefixes::class,
    TransferQuery\DiscSequence\DiscsNumbering::class => QueryHandler\DiscSequence\DiscsNumbering::class,

    // Person
    TransferQuery\Person\Person::class => QueryHandler\Person\Person::class,

    // Continuation Detail
    TransferQuery\ContinuationDetail\ChecklistReminders::class =>
        QueryHandler\ContinuationDetail\ChecklistReminders::class,
    TransferQuery\ContinuationDetail\GetList::class =>
        QueryHandler\ContinuationDetail\GetList::class,

    // System
    TransferQuery\System\FinancialStandingRate::class => QueryHandler\System\FinancialStandingRate::class,
    TransferQuery\System\FinancialStandingRateList::class => QueryHandler\System\FinancialStandingRateList::class,

];
