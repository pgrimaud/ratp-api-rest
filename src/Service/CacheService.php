<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\HttpFoundation\RequestStack;

class CacheService
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var RedisAdapter
     */
    private $adapter;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var array
     */
    private $cacheParameters;

    /**
     * @param RequestStack $requestStack
     * @param string $hash
     * @param array $cacheParameters
     */
    public function __construct(RequestStack $requestStack, string $hash = '', array $cacheParameters = [])
    {
        $this->requestStack    = $requestStack;
        $this->hash            = $hash;
        $this->cacheParameters = $cacheParameters;

        $client        = RedisAdapter::createConnection(getenv('REDIS_URL'));
        $this->adapter = new RedisAdapter($client);
    }

    /**
     * @return array
     */
    public function getDataFromCache()
    {
        try {
            $cacheItem = $this->adapter->getItem($this->getHash());
            return $cacheItem->isHit() ? unserialize($cacheItem->get()) : [];
        } catch (InvalidArgumentException $e) {
            return [];
        }
    }

    /**
     * @return string
     */
    private function getHash(): string
    {
        if ($this->hash !== '') {
            return $this->hash;
        }

        $url = getenv('APP_SECRET') . $this->requestStack->getCurrentRequest()->getBaseUrl() .
            $this->requestStack->getCurrentRequest()->getPathInfo();

        foreach ($this->cacheParameters as $parameter => $value) {
            if ($value) {
                $url .= $parameter . '=' . $value;
            }
        }

        return md5($url);
    }

    /**
     * @param array $data
     * @param int $ttl
     */
    public function setDataToCache(array $data, int $ttl)
    {
        try {
            $cacheItem = $this->adapter->getItem($this->getHash());
            $cacheItem->set(serialize($data));
            $cacheItem->expiresAfter($ttl);

            $this->adapter->save($cacheItem);
        } catch (InvalidArgumentException $e) {

        }
    }
}
