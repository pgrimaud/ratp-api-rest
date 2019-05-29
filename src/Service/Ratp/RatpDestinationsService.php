<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use App\Exception\AmbiguousException;
use App\Utils\NameHelper;

use Ratp\{Api, Direction, Directions, Line, Reseau};

class RatpDestinationsService extends AbstractRatpService implements RatpServiceInterface
{
    /**
     * @param array $parameters
     *
     * @return array
     *
     * @throws AmbiguousException
     */
    public function getAll(array $parameters = []): array
    {
        $stations = [];

        $prefixcode  = NameHelper::networkPrefix($parameters['type']);
        $networkRatp = NameHelper::typeSlug($parameters['type'], true);

        $reseau = new Reseau();
        $reseau->setCode($networkRatp);

        $line = new Line();
        $line->setReseau($reseau);
        $line->setCode($prefixcode . $parameters['code']);

        $directionsApi = new Directions($line);

        $api    = new Api();
        $return = $api->getDirections($directionsApi)->getReturn();

        if (($ambiguousMessage = $this->isAmbiguous($return)) != '') {
            throw new AmbiguousException($ambiguousMessage);
        }

        foreach ($return->getDirections() as $direction) {
            /** @var Direction $direction */
            $destinations[] = [
                'name' => $direction->getName(),
                'way'  => $direction->getSens(),
            ];
        }

        return [
            'destinations' => $stations
        ];
    }
}
