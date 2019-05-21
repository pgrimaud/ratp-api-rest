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

        $apiStation = new Station();
        $apiStation->setLine($line);

        $apiStations = new Stations($apiStation);

        $api = new Api();

        $return = $api->getStations($apiStations)->getReturn();

        if (($ambiguousMessage = $this->isAmbiguous($return)) != '') {
            throw new AmbiguousException($ambiguousMessage);
        }

        foreach ($return->getStations() as $station) {
            /** @var \Ratp\Station $station */
            $stations[] = [
                'slug' => NameHelper::slugify($station->getName()),
                'name' => $station->getName()
            ];
        }

        return [
            'stations' => $stations
        ];
    }
}
