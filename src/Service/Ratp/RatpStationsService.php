<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use App\Exception\AmbiguousException;
use App\Utils\NameHelper;

use Ratp\{Api, Line, Reseau, Station, Stations};

class RatpStationsService extends AbstractRatpService implements RatpServiceInterface
{
    /**
     * @param array $parameters
     *
     * @return array
     *
     * @throws AmbiguousException
     */
    public function getDestinations(array $parameters = []): array
    {
        $stations = [];

        $prefixCode  = NameHelper::networkPrefix($parameters['type']);
        $networkRatp = NameHelper::typeSlug($parameters['type'], true);

        $reseau = new Reseau();
        $reseau->setCode($networkRatp);

        $line = new Line();
        $line->setReseau($reseau);
        $line->setCode($prefixCode . $parameters['code']);

        $apiStation = new Station();
        $apiStation->setLine($line);

        $apiStations = new Stations($apiStation);

        $api    = new Api();
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

        return [
            'stations' => $stations
        ];
    }
}
