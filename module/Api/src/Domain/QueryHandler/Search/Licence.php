<?php

/**
 * Search by LicenceId Details
 *
 * @author Shaun Lizzio <shaun@lizzio.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\QueryHandler\Search;

use Dvsa\Olcs\Api\Domain\QueryHandler\AbstractQueryHandler;
use Dvsa\Olcs\Api\Entity\Application\Application;
use Dvsa\Olcs\Api\Entity\Licence\Licence as LicenceEntity;
use Dvsa\Olcs\Transfer\Query\QueryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Dvsa\Olcs\Api\Entity\Organisation\Organisation;

/**
 * Search by LicenceId Details
 *
 * @author Shaun Lizzio <shaun@lizzio.co.uk>
 */
class Licence extends AbstractQueryHandler
{
    protected $repoServiceName = 'Licence';

    protected $extraRepos = ['Organisation'];

    public function handleQuery(QueryInterface $query)
    {
        /** @var LicenceEntity $licence */
        $licence = $this->getRepo()->fetchUsingId($query);

        $applications = $licence->getApplications();

        $applicationsArray = [];

        /** @var Application $application */
        foreach ($applications as $application) {
            $application->setPublicationNo(
                $application->determinePublicationNo()
            );
            $application->setPublishedDate(
                $application->determinePublishedDate()
            );

            $application->setOooDate(
                $application->getOutOfOppositionDate()
            );
            $application->setOorDate(
                $application->getOutOfRepresentationDate()
            );
            $application->setIsOpposed(
                $application->hasOpposition()
            );

            $applicationsArray[$application->getId()] = $application;
        }

        $result = $this->result(
            $licence,
            [],
            [
                'totalAuthVehicles' => $licence->getTotAuthVehicles(),
                'totalAuthTrailers' => $licence->getTotAuthTrailers(),
                'totalVehiclesInPossession' => $licence->getActiveVehiclesCount(),
                'totalPiRecords' => $licence->getPiRecordCount(),
                'activeCommunityLicences' => count($licence->getActiveCommunityLicences($licence)),
                'trafficArea' => $this->result(
                    $licence->getTrafficArea()
                )->serialize(),
                'companySubsidiaries' => $this->resultList(
                    $licence->getCompanySubsidiaries()
                ),
                'organisation' => $this->result(
                    $licence->getOrganisation(),
                    [
                        'type',
                        'contactDetails' => [
                            'address' => [
                                'countryCode'
                            ],
                            'phoneContacts' => [
                                'phoneContactType'
                            ]
                        ],
                        'natureOfBusinesses',
                        'tradingNames',
                        'leadTcArea' => [
                            'contactDetails' => [
                                'person'
                            ]
                        ]
                    ]
                )->serialize(),
                'correspondenceAddress' => $this->result(
                    $licence->getCorrespondenceCd(),
                    [
                        'person',
                        'phoneContacts',
                        'address' => [
                            'countryCode'
                        ]
                    ]
                )->serialize(),
                'partners' => $this->resultList(
                    $licence->getOrganisation()->getOrganisationPersons(),
                    [
                        'person'
                    ]
                ),
                'directors' => $this->resultList(
                    $licence->getOrganisation()->getOrganisationPersons(),
                    [
                        'person'
                    ]
                ),
                'transportManagers' => $this->resultList(
                    $licence->getTmLicences(),
                    [
                        'transportManager' => [
                            'homeCd' => [
                                'person'
                            ]
                        ]

                    ]
                ),
                'operatingCentres' => $this->resultList(
                    $licence->getOperatingCentres(),
                    [
                        'operatingCentre' => [
                            'address' => [
                                'countryCode'
                            ]
                        ]
                    ]
                ),
                'vehicles' => $this->resultList(
                    $licence->getLicenceVehicles(),
                    [
                        'vehicle',
                        'interimApplication'
                    ]
                ),
                'applications' => $this->resultList(
                    $applicationsArray
                ),
                'conditionUndertakings' => $this->resultList(
                    $licence->getConditionUndertakings(),
                    [
                        'conditionType'
                    ]
                ),
                'otherLicences' => $this->resultList($licence->getOtherActiveLicences())
            ]
        );

        return $result;
    }
}
