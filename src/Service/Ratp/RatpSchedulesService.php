<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use App\Utils\NameHelper;
use Ratp\{Api, Direction, GeoPoint, Line, Mission, MissionsNext, Station};

class RatpSchedulesService extends AbstractRatpService implements RatpServiceInterface
{
    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function getSchedules(array $parameters = []): array
    {
        $schedules = [];

        $prefix = NameHelper::typeSlugSchedules($parameters['type']);

        $line = new Line();

        // prefix line name
        if (in_array($parameters['type'], ['buses', 'metros', 'noctiliens'])) {
            $line->setId($prefix . strtoupper($parameters['code']));
        } elseif ($parameters['type'] == 'tramways') {
            $line->setId($prefix . strtolower($parameters['code']));
        } elseif (in_array($parameters['type'], ['rers'])) {
            $line->setId($prefix . strtoupper($parameters['code']));
        }

        $station = new Station();
        $station->setLine($line);
        $station->setName(NameHelper::clean($parameters['station']));

        $way = $parameters['way'] == 'A+R' ? '*' : $parameters['way'];

        $direction = new Direction();
        $direction->setSens($way);

        $mission = new MissionsNext($station, $direction);

        $api    = new Api();
        $return = $api->getMissionsNext($mission)->getReturn();

        $this->isAmbiguous($return);

        if ($return->getMissions()) {

            /** @var Mission $mission */
            foreach ($return->getMissions() as $mission) {
                if (isset($mission->getStations()[1]) && ($mission->getStations()[1]->getGeoPointA() instanceof GeoPoint)) {
                    $destination = $mission->getStations()[1]->getGeoPointA()->getName();
                } elseif ($mission->getDirection() instanceof Direction) {
                    $destination = $mission->getDirection()->getName();
                } elseif (isset($mission->getStations()[1])) {
                    $destination = $mission->getStations()[1]->getName();
                } else {
                    $destination = $return->getArgumentDirection()->getName();
                }

                $schedules[] = [
                    'code'        => $mission->getCode(),
                    'message'     => $mission->getStationsMessages()[0],
                    'destination' => $destination,
                ];
            }
        } else {
            $destination = $return->getArgumentDirection() instanceof Direction ?
                $return->getArgumentDirection()->getName() : 'Destination unavailable';

            $schedules[] = [
                'code'        => 'Schedules unavailable',
                'message'     => 'Schedules unavailable',
                'destination' => $destination,
            ];
        }

        return [
            'schedules' => $schedules
        ];
    }
}
