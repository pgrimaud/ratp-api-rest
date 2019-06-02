<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use App\Exception\AmbiguousException;
use App\Utils\NameHelper;

use Ratp\{Api, Line, Station, Stations};

class RatpStationsService extends AbstractRatpService implements RatpServiceInterface
{
    /**
     * @param array $parameters
     *
     * @return array
     *
     * @throws AmbiguousException
     */
    protected function getStations(array $parameters = []): array
    {
        $stations = [];

        /** @var Line $line */
        $line = $this->formatLineQuery($parameters['type'], $parameters['code']);

        $apiStation = new Station();
        $apiStation->setLine($line);

        $apiStations = new Stations($apiStation);

        $api = new Api(null, [
            'connection_timeout' => getenv('API_TIMEOUT')
        ]);

        $result = $api->getStations($apiStations)->getReturn();

        if (($ambiguousMessage = $this->isAmbiguous($result)) != '') {
            throw new AmbiguousException($ambiguousMessage);
        }

        foreach ($result->getStations() as $station) {
            /** @var Station $station */
            $stations[] = [
                'name' => $station->getName(),
                'slug' => NameHelper::slugify($station->getName()),
            ];
        }

        // Temporary fix for way on RATP SOAP call (#75 & #83)
        if ($parameters['way'] === 'R') {
            $stations = array_reverse($stations);
        }

        return [
            'stations' => $stations
        ];
    }
}
