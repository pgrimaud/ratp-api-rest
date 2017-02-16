<?php
namespace ApiBundle\Services\Api;

class TrafficService extends ApiService implements ApiDataInterface
{
    const ENTRYPOINT = 'http://apixha.ixxi.net/APIX?cmd=getTrafficSituation&category=all&networkType=all&withText=true&apixFormat=json';

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
        $entrypoint = $this->getEntryPoint();
        /** @todo get data (ie with guzzlehttp) */
        /** set curl result to storage ? */
        return [
            'payload' => 'NEW TRAFFIC ' . date('Y-m-d H:i:s')
        ];
    }

    private function getEntryPoint()
    {
        /** @todo manage configuration as dependency */
        $ixxi_key = $this->getParameter('ixxi_key');

        return self::ENTRYPOINT . '&keyapp=' . $ixxi_key . '&tmp=' . time();
    }
}
