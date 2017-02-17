<?php
namespace ApiBundle\Services\Api;

class TrafficService extends ApiService implements ApiDataInterface
{
    const ENTRYPOINT = 'http://apixha.ixxi.net/APIX?cmd=getTrafficSituation&category=all' .
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
     * @return mixed
     */
    public function get($method)
    {
        return parent::getData($method);
    }

    /**
     * @return array
     */
    protected function getAll()
    {
        /** @todo curl ixxi */

        return [
            'payload' => 'NEW TRAFFIC ' . date('Y-m-d H:i:s')
        ];
    }

    /**
     * @return string
     */
    private function getEntryPoint()
    {
        return self::ENTRYPOINT . '&keyapp=' . $this->apiKey . '&tmp=' . time();
    }
}
