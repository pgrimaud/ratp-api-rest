<?php
namespace RatpApi\ApiBundle\Services\Core;

abstract class CoreService
{
    /**
     * @param $key
     * @param $service
     */
    public function addService($key, $service)
    {
        $this->$key = $service;
    }
}
