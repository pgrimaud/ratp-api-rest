<?php
namespace ApiBundle\Services\Api;

use ApiBundle\Services\Core\CoreService;
use ApiBundle\Services\Core\StorageService;

class ApiService extends CoreService
{
    /**
     * @var StorageService
     * $storage
     */
    protected $storage;

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
            $this->storage->setCache($cache, $data);
            return $data;
        }
    }
}
