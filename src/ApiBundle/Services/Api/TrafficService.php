<?php
namespace ApiBundle\Services\Api;

class TrafficService extends ApiService implements ApiDataInterface
{
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
        return [
            'payload' => 'NEW TRAFFIC ' . date('Y-m-d H:i:s')
        ];
    }
}
