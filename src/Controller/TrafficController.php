<?php

declare(strict_types=1);

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\CacheItem;

class TrafficController extends AppController
{
    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"}
     * )
     *
     * @SWG\Tag(
     *   name="Traffic"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="@todo"
     * )
     *
     * @Rest\View()
     * @Rest\Get("/traffic")
     *
     * @throws \Exception
     */
    public function trafficAction(): View
    {
        $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        $cache = new RedisAdapter(
            $client
        );

        $cacheItem = new CacheItem();
        $cacheItem->expiresAt(new \DateTimeImmutable('+' . getenv('CACHE_TRAFFIC') . ' seconds'));
        $cacheItem->set('test');

        $cache->save($cacheItem);

        // @todo
        return $this->appView(['ok']);
    }
}
