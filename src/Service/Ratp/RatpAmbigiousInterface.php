<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use Ratp\WrStations;

interface RatpAmbigiousInterface
{
    /**
     * @param WrStations $object
     *
     * @return string
     */
    public function getAmbiguityMessage(WrStations $object): string;
}
