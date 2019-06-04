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
    protected function getDestinations(array $parameters = []): array
    {
        $destinations = [];

        $prefixCode  = NameHelper::networkPrefix($parameters['type']);
        $networkRatp = NameHelper::typeSlug($parameters['type'], true);

        $line = new Line();

        // some buses need special API calls
        if ($networkRatp === 'busratp') {
            $line->setId('B' . $parameters['code']);
        } else {
            $reseau = new Reseau();
            $reseau->setCode($networkRatp);

            $line->setCode($prefixCode . $parameters['code']);
            $line->setReseau($reseau);
        }

        $directionsApi = new Directions($line);

        $api = new Api(null, [
            'connection_timeout' => getenv('API_TIMEOUT')
        ]);

        $result = $api->getDirections($directionsApi)->getReturn();

        if (($ambiguousMessage = $this->isAmbiguous($result)) != '') {
            throw new AmbiguousException($ambiguousMessage);
        }

        foreach ($result->getDirections() as $direction) {
            /** @var Direction $direction */
            $destinations[] = [
                'name' => $direction->getName(),
                'way'  => $direction->getSens(),
            ];
        }

        return [
            'destinations' => $destinations
        ];
    }
}
