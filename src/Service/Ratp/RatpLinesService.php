<?php

declare(strict_types=1);

namespace App\Service\Ratp;

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

    }
}
