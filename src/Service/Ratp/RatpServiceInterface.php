<?php

declare(strict_types=1);

namespace App\Service\Ratp;

interface RatpServiceInterface
{
    /**
     * @param string $method
     * @param array $parameters
     *
     * @return array
     */
    public function get(string $method, array $parameters = []): array;
}
