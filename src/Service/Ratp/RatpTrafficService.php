<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use App\Client\IxxiApiClient;
use App\Client\RatpWebsiteClient;
use App\Utils\NameHelper;

class RatpTrafficService extends AbstractRatpService implements RatpServiceInterface
{
    /**
     * @var IxxiApiClient
     */
    private $ixxiApiClient;

    /**
     * @var RatpWebsiteClient
     */
    private $ratpWebsiteClient;

    public function __construct()
    {
        $this->ixxiApiClient = new IxxiApiClient();
        //$this->ratpWebsiteClient = new RatpWebsiteClient();
    }

    /**
     * @return array
     */
    protected function getTraffic(): array
    {
        $ixxiData         = $this->ixxiApiClient->getData();
        $ixxiFormatedData = $this->formatIxxiData($ixxiData);

        // 2019-10-05 - Website json file is protected by cloudflare. Can't bypass it
        // https://github.com/pgrimaud/horaires-ratp-api/issues/92

        // $ratpData = $this->ratpWebsiteClient->getData();
        // $completeData = $this->mergeDataSources($ratpData, $ixxiData);

        $completeData = $this->getTemporaryDataFromIxxi($ixxiFormatedData);

        return $completeData;
    }

    /**
     * @param array $ratpData
     * @param array $ixxiData
     *
     * @return array
     */
    private function mergeDataSources(array $ratpData, array $ixxiData): array
    {
        // merge only RER C, D and E
        $allowedRers = [
            'c',
            'd',
            'e'
        ];

        foreach ($allowedRers as $allowedRer) {
            if (isset($ixxiData['rers'][$allowedRer])) {
                $rer = $ixxiData['rers'][$allowedRer];
                ksort($rer);

                $firstEvent = current($rer);

                $information = [
                    'line'    => strtoupper($allowedRer),
                    'slug'    => NameHelper::statusSlug($firstEvent['typeName']),
                    'title'   => $firstEvent['typeName'],
                    'message' => $firstEvent['message']
                ];
            } else {
                $information = [
                    'line'    => strtoupper($allowedRer),
                    'slug'    => 'normal',
                    'title'   => 'Trafic normal',
                    'message' => 'Trafic normal sur l\'ensemble de la ligne.'
                ];
            }
            $tmpRers[$allowedRer] = $information;
        }

        ksort($tmpRers);

        foreach ($tmpRers as $rer) {
            $ratpData['rers'][] = $rer;
        }

        return $ratpData;
    }

    private function getTemporaryDataFromIxxi(array $ixxiData): array
    {
        $data = [];

        $allowedSources = [
            'metros'   => [
                '1',
                '2',
                '3',
                '3B',
                '4',
                '5',
                '6',
                '7',
                '7B',
                '8',
                '9',
                '10',
                '11',
                '12',
                '13',
                '14',
            ],
            'rers'     => [
                'A',
                'B',
                'C',
                'D',
                'E'
            ],
            'tramways' => [
                '1',
                '2',
                '3A',
                '3B',
                '4',
                '5',
                '6',
                '7',
                '8',
            ]
        ];

        foreach ($allowedSources as $type => $allowedLines) {
            foreach ($allowedLines as $allowedLine) {
                if (isset($ixxiData[$type][$allowedLine])) {
                    $errors      = $ixxiData[$type][$allowedLine];
                    $event       = isset($errors['Incidents']) ? current($errors['Incidents']) : (isset($errors['Travaux']) ? current($errors['Travaux']) : current($errors['Incidents techniques'])) ;
                    $information = [
                        'line'    => strtoupper($allowedLine),
                        'slug'    => $this->slugStatusIxxiData($event['typeName']),
                        'title'   => $event['typeName'],
                        'message' => $event['message']
                    ];
                } else {
                    $information = [
                        'line'    => strtoupper($allowedLine),
                        'slug'    => 'normal',
                        'title'   => 'Trafic normal',
                        'message' => 'Trafic normal sur l\'ensemble de la ligne.'
                    ];
                }
                $data[$type][] = $information;
            }
        }
        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function formatIxxiData(array $data): array
    {
        $results = [];

        foreach ($data['events'] as $event) {
            foreach ($event['incidents'] as $incident) {
                foreach ($incident['lines'] as $line) {
                    if ($event['startDate'] <= date('c') && $event['endDate'] >= date('c')) {
                        $results[$this->slugIxxiData($line['groupOfLinesName'])][$line['name']][$event['typeName']][$event['startDate']] = [
                            'message'          => $line['message'],
                            'shortMessage'     => $line['shortMessage'],
                            'incidentSeverity' => $line['incidentSeverity'],
                            'typeName'         => $event['typeName'],
                            'startDate'        => $event['startDate'],
                            'endDate'          => $event['endDate']
                        ];
                    }
                }
            }
        }

        return $results;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function slugIxxiData(string $value): string
    {
        $names = [
            'SNCF'    => 'trains',
            'RER'     => 'rers',
            'Tramway' => 'tramways',
            'Métro'   => 'metros'
        ];

        return $names[$value];
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function slugStatusIxxiData(string $value): string
    {
        $names = [
            'Travaux'              => 'normal_trav',
            'Incidents techniques' => 'critical',
            'Incidents'            => 'critical',
        ];

        if (!isset($names[$value])) {
            return $value;
        } else {
            return $names[$value];
        }
    }
}
