<?php
namespace RatpApi\ApiBundle\Services\Api;

interface ApiDataInterface
{
    /**
     * @param $method
     * @param array $parameters
     * @return mixed
     */
    public function get($method, $parameters = []);
}
