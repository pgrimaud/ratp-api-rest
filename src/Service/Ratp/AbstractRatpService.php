<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use Ratp\AmbiguousInterface;

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

    /**
     * @param AmbiguousInterface $object
     *
     * @return string
     */
    protected function isAmbiguous(AmbiguousInterface $object): string
    {
        return $object->getAmbiguityMessage() ? $object->getAmbiguityMessage() : '';
    }
}
