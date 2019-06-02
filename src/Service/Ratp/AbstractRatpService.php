<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use App\Utils\NameHelper;
use Ratp\{AmbiguousInterface, Line, Reseau};

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
        return $this->{'get' . ucwords($method)}($parameters);
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

    /**
     * @param string $type
     * @param string $code
     *
     * @return Line
     */
    protected function formatLineQuery(string $type, string $code): Line
    {
        $prefixCode  = NameHelper::networkPrefix($type);
        $networkRatp = NameHelper::typeSlug($type, true);

        $line = new Line();

        // some buses need special API calls
        if ($networkRatp === 'bus') {
            $line->setId($prefixCode . $code);
        } else {
            $reseau = new Reseau();
            $reseau->setCode($networkRatp);

            $line->setCode($prefixCode . $code);
            $line->setReseau($reseau);
        }

        return $line;
    }
}
