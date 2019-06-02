<?php

declare(strict_types=1);

namespace App\Service\Ratp;

interface RatpAmbigiousInterface
{
    /**
     * @param RatpAmbigiousInterface $object
     *
     * @return string
     */
    public function getAmbiguityMessage(RatpAmbigiousInterface $object): string;
}
