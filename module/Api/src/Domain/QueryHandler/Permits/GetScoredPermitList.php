<?php

namespace Dvsa\Olcs\Api\Domain\QueryHandler\Permits;

use Dvsa\Olcs\Api\Domain\Exception\RuntimeException;
use Dvsa\Olcs\Api\Domain\QueryHandler\AbstractQueryHandler;
use Dvsa\Olcs\Api\Domain\Repository\IrhpCandidatePermit;
use Dvsa\Olcs\Transfer\Query\QueryInterface;
use Dvsa\Olcs\Transfer\Query\IrhpCandidatePermit\GetScoredList as Query;
use Dvsa\Olcs\Api\Entity\Permits\EcmtPermitApplication;

/**
 * Get a list of scored irhp candidate permit records and associated data
 *
 * @todo: Needed to specify $extraRepos for unknown reason to prevent unit test failing, investigate & remove
 */
class GetScoredPermitList extends AbstractQueryHandler
{
    const DEVOLVED_ADMINISTRATION_TRAFFIC_AREAS = ['M', 'G', 'N'];

    protected $repoServiceName = 'IrhpCandidatePermit';
    protected $extraRepos = ['IrhpCandidatePermit']; //this is needed for Unit Test
    protected $bundledRepos = [
        'irhpPermitApplication' => [
            'ecmtPermitApplication' => [
                'countrys',
                'sectors',
                'internationalJourneys'
            ],
            'irhpPermitWindow',
            'licence' => [
                'trafficArea',
                'organisation'
            ]
        ],
        'irhpPermitRange' => [
            'countrys'
        ],
    ];

    /**
     * Return a list of scored irhp candidate permit records and associated data
     * @param QueryInterface|Query $query DTO
     *
     * @return array
     */
    public function handleQuery(QueryInterface $query)
    {
        /** @var Query $query */
        $results = $this->getRepo('IrhpCandidatePermit')->fetchAllScoredForStock($query->getStockId());

        return [
            'result' => $this->formatResults(
                $this->resultList(
                    $results,
                    $this->bundledRepos
                )
            )
        ];
    }

    /**
     * Format the results of the query fetchAllScoredForStock to make them more readable
     *
     * @param array $data an array of query results with the same
     *                  format as that returned by fetchAllScoredForStock
     *
     * @return array a formatted and mapped array
     * @todo: find dynamic sector name for the 'None' option instead of hardcoding it
     */
    private function formatResults($data)
    {
        $formattedData = [];
        if (!empty($data)) {
            foreach ($data as $row) {
                $sector = $row['irhpPermitApplication']['ecmtPermitApplication']['sectors'];
                $trafficArea = $row['irhpPermitApplication']['licence']['trafficArea'];
                $interJourneys = $row['irhpPermitApplication']['ecmtPermitApplication']['internationalJourneys']['id'];
                $licence = $row['irhpPermitApplication']['licence'];

                $devolvedAdministration = 'N/A';
                if (in_array($trafficArea['id'], self::DEVOLVED_ADMINISTRATION_TRAFFIC_AREAS)) {
                    $devolvedAdministration = $trafficArea['name'];
                }

                $formattedData[] = [
                    'Permit Ref'                        => $licence['licNo'] . '/' . $row['irhpPermitApplication']['id'] . '/' . $row['id'],
                    'Operator'                          => $licence['organisation']['name'],
                    'Application Score'                 => $row['applicationScore'],
                    'Permit Intensity of Use'           => $row['intensityOfUse'],
                    'Random Factor'                     => $row['randomFactor'],
                    'Randomised Permit Score'           => $row['randomizedScore'],
                    'Percentage International'          => EcmtPermitApplication::INTERNATIONAL_JOURNEYS_DECIMAL_MAP[$interJourneys],
                    'Sector'                            => $sector['name'] === 'None/More than one of these sectors' ? 'N/A' : $sector['name'],
                    'Devolved Administration'           => $devolvedAdministration,
                    'Result'                            => $row['successful'] ? 'Successful' : 'Unsuccessful',
                    'Restricted Countries – Requested'  => $this->getRestrictedCountriesRequested($row),
                    'Restricted Countries – Offered'    => $this->getRestrictedCountriesOffered($row)
                ];
            }
        }

        return $formattedData;
    }

    /**
     * Retrieves the list of restricted countries requested for display in an export .csv file
     *
     * @param array $row Row from data from query
     *
     * @return string
     */
    private function getRestrictedCountriesRequested($row)
    {
        if ($row['irhpPermitApplication']['ecmtPermitApplication']['hasRestrictedCountries']) {
            return $this->formatRestrictedCountriesForDisplay($row['irhpPermitApplication']['ecmtPermitApplication']['countrys']);
        }

        return 'N/A';
    }

    /**
     * Retrieves the list of restricted countries offered for display in an export .csv file
     *
     * @param array $row Row from data from query
     *
     * @return string
     */
    private function getRestrictedCountriesOffered($row)
    {
        if (count($row['irhpPermitRange']['countrys']) > 0) {
            return $this->formatRestrictedCountriesForDisplay($row['irhpPermitRange']['countrys']);
        }

        return 'N/A';
    }

    /**
     * Formats a given list of restricted countries for display in an export .csv file
     *
     * @param array $countries a list of restricted countries in the format returned by backend
     *
     * @return string a list of countries seperated by semicolons
     */
    private function formatRestrictedCountriesForDisplay($countries)
    {
        $restrictedCountries = [];
        foreach ($countries as $country) {
            $restrictedCountries[] = $country['countryDesc'];
        }

        return implode('; ', $restrictedCountries);
    }
}
