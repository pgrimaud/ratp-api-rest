<?php

declare(strict_types=1);

namespace App\Service;

abstract class AbstractRatpService
{
    /**
     * @param string $method
     * @param array $parameters
     *
     * @return array
     */
    public function get(string $method, array $parameters = []): array
    {
        return $this->{'get' . $method}($parameters);
    }
}
