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
     * @param RequestStack $requestStack
     * @param string $hash
     */
    public function __construct(RequestStack $requestStack, string $hash = '')
    {
        $this->requestStack = $requestStack;
        $this->hash         = $hash;

        $client        = RedisAdapter::createConnection(getenv('REDIS_URL'));
        $this->adapter = new RedisAdapter($client);
    }

    /**
     * @return array
     */
    public function getDataFromCache(): array
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
