<?php

namespace RatpApi\ApiBundle\Services\Api;

use RatpApi\ApiBundle\Helper\NamesHelper;
use FOS\RestBundle\Exception\InvalidParameterException;
use GuzzleHttp\Client;

class TrafficService extends ApiService implements ApiDataInterface
{
    const ENTRYPOINT_IXXI = 'http://apixha.ixxi.net/APIX?cmd=getTrafficSituation&category=all' .
    '&networkType=all&withText=true&apixFormat=json';

    /**
     * @var string $apiKey
     */
    private $apiKey;

    /**
     * @var int $resultTtl
     */
    private $resultTtl;

    /**
     * TrafficService constructor.
     * We override parent constructor to inject curl result ttl and apikey.
     *
     * @param $ttl
     * @param $resultTtl
     * @param $apiKey
     */
    public function __construct($ttl, $resultTtl, $apiKey)
    {
        $this->apiKey    = $apiKey;
        $this->resultTtl = $resultTtl;

        parent::__construct($ttl);
    }

    /**
     * @param $method
     * @param array $parameters
     * @return mixed
     */
    public function get($method, $parameters = [])
    {
        return parent::getData($method, $parameters);
    }

    /**
     * @return array|mixed
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getTrafficCache()
    {
        $cache = $this->storage->getCacheItem('traffic_data');

        if ($cache->isHit()) {
            $data = unserialize($cache->get());
        } else {
            $ixxiData = $this->getDataFromIxxi();

            $data = $this->formatData($ixxiData);

            $this->storage->setCache($cache, $data, $this->resultTtl);
        }

        return $data;
    }

    /**
     * @return array|mixed
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getAll()
    {
        return $this->getTrafficCache();
    }

    /**
     * @param $parameters
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getSpecific($parameters)
    {
        $typesAllowed = [
            'rers',
            'metros',
            'tramways'
        ];

        if (!in_array($parameters['type'], $typesAllowed)) {
            throw new InvalidParameterException(sprintf('Unknown type : %s', $parameters['type']));
        }

        $data = $this->getTrafficCache();

        return [
            $parameters['type'] => $data[$parameters['type']]
        ];
    }

    /**
     * @param $parameters
     *
     * @return mixed|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getLine($parameters)
    {
        $typesAllowed = [
            'rers',
            'metros',
            'tramways'
        ];

        if (!in_array($parameters['type'], $typesAllowed)) {
            throw new InvalidParameterException(sprintf('Unknown type : %s', $parameters['type']));
        }

        $data = $this->getTrafficCache();

        $line = null;

        foreach ($data[$parameters['type']] as $dataLine) {
            if (strtolower($dataLine['line']) == strtolower($parameters['code'])) {
                $line = $dataLine;
                break;
            }
        }

        return $line;
    }

    /**
     * @param $ixxi
     * @return array
     */
    private function formatData($ixxi)
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
                if (isset($ixxi[$type][$allowedLine])) {
                    $errors = $ixxi[$type][$allowedLine];
                    $event  = isset($errors['Incidents']) ? current($errors['Incidents']) : current($errors['Travaux']);

                    $information = [
                        'line'    => strtoupper($allowedLine),
                        'slug'    => NamesHelper::statusSlug($event['typeName']),
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
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getDataFromIxxi()
    {
        try {
            $client = new Client();
            $res    = $client->request('GET', $this->getEntryPointIxxi(), [
                'headers' => [
                    'User-Agent' => 'Dalvik/2.1.0 (Linux; U; Android 5.0; Google Nexus 4 - 5.0.0 - API 21 - 768x1280 Build/LRX21M)'
                ]
            ]);

            $data = json_decode($res->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);

            return $this->formatIxxiData($data);
        } catch (\Exception $exception) {
            return [
                'message' => 'Something went wrong'
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     */
    private function formatIxxiData($data = [])
    {
        $results = [];

        foreach ($data['events'] as $event) {
            foreach ($event['incidents'] as $incident) {
                foreach ($incident['lines'] as $line) {
                    if ($event['startDate'] <= date('c') && $event['endDate'] >= date('c')) {
                        $results[NamesHelper::getSlug($line['groupOfLinesName'])][$line['name']][$event['typeName']][$event['startDate']] = [
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
     * @param array $data
     * @return array
     */
    public function formatRatpData($data = [])
    {
        $results = [];

        $matchingRessources = [
            'metros'   => 'metro',
            'rers'     => 'rer',
            'tramways' => 'tram'
        ];

        foreach ($matchingRessources as $route => $ratp_route) {
            $dataName = $matchingRessources[$route];

            foreach ($data['status'][$dataName]['lines'] as $line => $value) {
                $line = strtolower(str_replace(['T', 'M', 'R'], '', $line));

                $results[$route][] = [
                    'line'    => strtoupper($line),
                    'slug'    => $value['name'],
                    'title'   => $value['title'],
                    'message' => $value['message']
                ];
            }
        }

        return $results;
    }

    /**
     * @return string
     */
    private function getEntryPointIxxi()
    {
        return self::ENTRYPOINT_IXXI . '&keyapp=' . $this->apiKey . '&tmp=' . time();
    }
}
