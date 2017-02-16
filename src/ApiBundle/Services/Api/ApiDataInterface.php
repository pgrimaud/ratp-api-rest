<?php
namespace ApiBundle\Services\Api;

interface ApiDataInterface
{
    /**
     * @param $method
     * @return mixed
     */
    public function get($method);
}
