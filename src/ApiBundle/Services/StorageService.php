<?php
namespace ApiBundle\Services;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class StorageService
{
    /** @var FilesystemAdapter $cache */
    private $cache;

    /**
     * @param $key
     * @param $service
     */
    public function addService($key, $service)
    {
        $this->$key = $service;
    }

    /**
     * @param $string
     * @return string
     */
    public function getHash($string)
    {
        dump($this->cache);
        return md5($string);
    }
}