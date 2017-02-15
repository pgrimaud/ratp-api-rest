<?php
namespace ApiBundle\Services;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class StorageService
{
    /** @var RedisAdapter $cache */
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
     * @param $hash
     * @return mixed|\Symfony\Component\Cache\CacheItem
     */
    public function getCache($hash)
    {
        return $this->cache->getItem($hash);
    }

    /**
     * @param $string
     * @return string
     */
    public function getHash($string)
    {
        return md5($string);
    }

    /**
     * @param $cachedData
     * @param $data
     */
    public function setCache($cachedData, $data)
    {
        $cachedData->set(serialize($data));
        $cachedData->expiresAt(new \DateTime('+ 30 seconds'));
        $this->cache->save($cachedData);
    }
}