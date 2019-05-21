<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use App\Service\AbstractRatpService;

class RatpLinesService extends AbstractRatpService implements RatpServiceInterface
{
    /**
     * @param string $method
     * @param $parameters
     * @return array
     */
    public function get(string $method, array $parameters = []): array
    {
        return $this->getLinesFromCache();
    }

    /**
     * @return array
     */
    private function getLinesFromCache()
    {
        $cache = $this->storage->getCacheItem('lines_data');

        if ($cache->isHit()) {
            $data = unserialize($cache->get());
        } else {
            $data = $this->getAllLinesForCache();
            $this->storage->setCache($cache, $data, $this->resultTtl);
        }
        return $data;
    }
}
