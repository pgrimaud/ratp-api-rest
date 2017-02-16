<?php
namespace ApiBundle\Services;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\CacheItem;

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
     * @return mixed|CacheItem
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
     * @param CacheItem $cachedData
     * @param $data
     */
    public function setCache(CacheItem $cachedData, $data)
    {
        $cachedData->set(serialize($data));
        $cachedData->expiresAt(new \DateTime('+5 seconds'));
        $this->cache->save($cachedData);
    }
}