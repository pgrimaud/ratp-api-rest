<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use App\Client\IxxiApiClient;

class RatpTrafficService extends AbstractRatpService implements RatpServiceInterface
{
    /**
     * @var IxxiApiClient
     */
    private $ixxiApiClient;

    public function __construct()
    {
        $this->ixxiApiClient = new IxxiApiClient();
    }

    /**
     * @return array
     */
    protected function getTraffic(): array
    {
        $ixxiData         = $this->ixxiApiClient->getData();
        $ixxiFormatedData = $this->formatIxxiData($ixxiData);

        $completeData = $this->getTemporaryDataFromIxxi($ixxiFormatedData);

        return $completeData;
    }

    /**
     * @param array $ixxiData
     *
     * @return array
     */
    private function getTemporaryDataFromIxxi(array $ixxiData): array
    {
        $data = [];

        $allowedSources = [
            'metros'   => [
                '1',
                '2',
                '3',
                '3b',
                '4',
                '5',
                '6',
                '7',
                '7b',
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
                '3a',
                '3b',
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
                    $errors = $ixxiData[$type][$allowedLine];

                    if (isset($errors['Travaux'])) {
                        $events = $errors['Travaux'];
                    } elseif (isset($errors['Incidents techniques'])) {
                        $events = $errors['Incidents techniques'];
                    } else if (isset($errors['Mouvement social'])) {
                        $events = $errors['Mouvement social'];
                    } else {
                        $events = current($errors);

                    }

                    $event = reset($events);

                    $information = [
                        'line'    => strtoupper($allowedLine),
                        'slug'    => $this->slugStatusIxxiData($event['typeName']),
                        'title'   => $event['typeName'],
                        'message' => $event['message'],
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
                        if ($this->slugIxxiData($line['groupOfLinesName']) !== '') {
                            $lineName = str_replace('T', '', $line['name']);

                            $results[$this->slugIxxiData($line['groupOfLinesName'])][$lineName][$event['typeName']][$event['startDate']] = [
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
            'Métro'   => 'metros',
        ];

        return isset($names[$value]) ? $names[$value] : '';
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
            'Mouvement social'     => 'alerte',
        ];

        if (!isset($names[$value])) {
            return $value;
        } else {
            return $names[$value];
        }
    }
}
