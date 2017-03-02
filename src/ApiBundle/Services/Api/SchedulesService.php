<?php
namespace ApiBundle\Services\Api;

use ApiBundle\Helper\NetworkHelper;
use Ratp\Api;
use Ratp\Direction;
use Ratp\Line;
use Ratp\MissionsNext;
use Ratp\Station;

class SchedulesService extends ApiService implements ApiDataInterface
{
    /**
     * @param $method
     * @param array $parameters
     * @return mixed
     */
    public function get($method, $parameters = [])
    {
        return parent::getData($method, $parameters);
    }

    /**
     * @param $parameters
     * @return array|null
     */
    protected function getSchedules($parameters)
    {
        $schedules = [];

        $typesAllowed = [
            'rers',
            'metros',
            'tramways',
            'bus',
            'noctiliens'
        ];

        if (!in_array($parameters['type'], $typesAllowed) || empty($parameters['code'])
            || empty($parameters['station']) || empty($parameters['way'])
        ) {
            return null;
        }

        $prefix = NetworkHelper::typeSlugSchedules($parameters['type']);

        $line = new Line();
        if (in_array($parameters['type'], ['bus', 'metros'])) {
            $line->setId($prefix . $parameters['code']);
        } elseif (in_array($parameters['type'], ['rers'])) {
            $line->setId($prefix . strtoupper($parameters['code']));
        } else {
            /** @FIXME TRAMWAYS DONT WORK */
            $line->setCode($prefix . strtoupper($parameters['code']));
        }

        $station = new Station();
        $station->setLine($line);
        $station->setName($parameters['station']);

        $direction = new Direction();
        $direction->setSens($parameters['way']);

        $mission = new MissionsNext($station, $direction, date('YmdHi'));

        $api    = new Api();
        $result = $api->getMissionsNext($mission)->getReturn();

        /** @todo Manage exception if station is not found */

        if ($result->getMissions()) {
            foreach ($result->getMissions() as $mission) {
                $schedules[] = $mission->stationsMessages;
            }
        } else {
            $schedules[] = 'Schedules unavailable';
        }

        return $schedules;
    }
}
