<?php

namespace RatpApi\ApiBundle\Services\Api;

use RatpApi\ApiBundle\Services\Core\CoreService;
use RatpApi\ApiBundle\Services\Core\StorageService;
use FOS\RestBundle\Exception\InvalidParameterException;
use Ratp\WrDirections;
use Ratp\WrMissions;
use Ratp\WrStations;

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
     * @param $parameters
     * @return mixed
     */
    public function getData($method, $parameters)
    {
        $cache = $this->storage->getCacheItem();

        if ($cache->isHit()) {
            return unserialize($cache->get());
        } else {
            $data = $this->{'get' . $method}($parameters);
            $this->storage->setCache($cache, $data, $this->ttl);
            return $data;
        }
    }

    /**
     * @param WrMissions|WrDirections|WrStations $object
     * @retur
     */
    public function isAmbiguous($object)
    {
        if ($object->getAmbiguityMessage() != '') {
            throw new InvalidParameterException($object->getAmbiguityMessage());
        }
    }
}
