<?php
namespace ApiBundle\Services\Core;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\RequestStack;

class StorageService extends CoreService
{
    /**
     * @var RedisAdapter $cache
     */
    protected $cache;

    /**
     * @var RequestStack $requestStack
     */
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param null $forceHash
     * @return mixed|CacheItem
     */
    public function getCacheItem($forceHash = null)
    {
        $hash = $forceHash ? $forceHash : $this->getHash();
        return $this->cache->getItem($hash);
    }

    /**
     * @return string
     */
    public function getHash()
    {
        $url = $this->requestStack->getCurrentRequest()->getBaseUrl() .
            $this->requestStack->getCurrentRequest()->getPathInfo();
        return md5($url);
    }

    /**
     * @param CacheItem $cachedData
     * @param $data
     */
    public function setCache(CacheItem $cachedData, $data, $ttl)
    {
        $cachedData->set(serialize($data));
        $cachedData->expiresAt(new \DateTimeImmutable('+' . $ttl . ' seconds'));

        $this->cache->save($cachedData);
    }
}
