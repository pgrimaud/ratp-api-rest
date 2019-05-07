<?php

namespace RatpApi\ApiBundle\Services\Api;

use RatpApi\ApiBundle\Helper\NamesHelper;
use FOS\RestBundle\Exception\InvalidParameterException;
use GuzzleHttp\Client;

class TrafficService extends ApiService implements ApiDataInterface
{
    const ENTRYPOINT_IXXI = 'http://apixha.ixxi.net/APIX?cmd=getTrafficSituation&category=all' .
    '&networkType=all&withText=true&apixFormat=json';
    const ENTRYPOINT_RATP = 'http://www.ratp.fr/meteo/ajax/data';

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
     * @param       $method
     * @param array $parameters
     * @return mixed
     */
    public function get($method, $parameters = [])
    {
        return parent::getData($method, $parameters);
    }

    /**
     * @return array
     */
    private function getTrafficCache()
    {
        $cache = $this->storage->getCacheItem('traffic_data');

        if ($cache->isHit()) {
            $data = unserialize($cache->get());
        } else {
            $ratpData = $this->getDataFromRatp();
            $ixxiData = $this->getDataFromIxxi();

            $data = $this->mergeDataSources($ratpData, $ixxiData);

            $this->storage->setCache($cache, $data, $this->resultTtl);
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getAll()
    {
        return $this->getTrafficCache();
    }

    /**
     * @param $parameters
     * @return array|null
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
     * @return array|null
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
     * @param $ratp
     * @param $ixxi
     * @return array
     */
    private function mergeDataSources($ratp, $ixxi)
    {
        // merge only RER C, D and E
        $allowedRers = [
            'c',
            'd',
            'e'
        ];

        foreach ($allowedRers as $allowedRer) {
            if (isset($ixxi['rers'][$allowedRer])) {
                $rer = $ixxi['rers'][$allowedRer];
                ksort($rer);

                $firstEvent = current($rer);

                $information = [
                    'line'    => strtoupper($allowedRer),
                    'slug'    => NamesHelper::statusSlug($firstEvent['typeName']),
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
            $ratp['rers'][] = $rer;
        }

        return $ratp;
    }

    /**
     * @return array
     */
    private function getDataFromRatp()
    {
        try {
            $client = new Client();
            $res    = $client->request('GET', $this->getEntryPointRatp(), [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'
                ]
            ]);

            $data = json_decode($res->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);

            return $this->formatRatpData($data);
        } catch (\Exception $exception) {
            return [
                'message' => 'Something went wrong'
            ];
        }
    }


    /**
     * @return array
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
                        $results[NamesHelper::getSlug($line['groupOfLinesName'])][$line['name']][$event['startDate']] = [
                            'message'          => $line['message'],
                            'shortMessage'     => $line['shortMessage'],
                            'incidentSeverity' => $line['incidentSeverity'],
                            'typeName'         => $event['typeName'],
                            'startDate'        => $event['startDate'],
                            'endDate'          => $event['startDate']
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

    /**
     * @return string
     */
    private function getEntryPointRatp()
    {
        return self::ENTRYPOINT_RATP . '?tmp=' . time();
    }
}
