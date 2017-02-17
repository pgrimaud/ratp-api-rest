<?php
namespace ApiBundle\Services\Api;

use ApiBundle\Services\Core\CoreService;
use ApiBundle\Services\Core\StorageService;

class ApiService extends CoreService
{
    /**
     * @var StorageService $storage
     */
    protected $storage;

    /**
     * @var integer $ttl
     */
    protected $ttl;

    /**
     * ApiService constructor.
     * @param $ttl
     */
    public function __construct($ttl)
    {
        $this->ttl = $ttl;
    }

    /**
     * @param $method
     * @return mixed
     */
    public function getData($method)
    {
        $cache = $this->storage->getCacheItem();

        if ($cache->isHit()) {
            return unserialize($cache->get());
        } else {
            $data = $this->{'get' . $method}();
            $this->storage->setCache($cache, $data, $this->ttl);
            return $data;
        }
    }
}
