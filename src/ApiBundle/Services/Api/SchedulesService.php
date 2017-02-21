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

        $networkRatp = NetworkHelper::typeSlugSchedules($parameters['type']);

        $line = new Line();
        $line->setId($networkRatp . $parameters['code']);

        $station = new Station();
        $station->setName($parameters['station']);
        $station->setLine($line);

        $direction = new Direction();
        $direction->setSens($parameters['way']);
        $direction->setLine($line);

        $mission = new MissionsNext($station, $direction);
        $api     = new Api();

        $result = $api->getMissionsNext($mission)->getReturn();

        foreach ($result->getMissions() as $mission) {
            $schedules[] = $mission->stationsMessages;
        }

        return $schedules;
    }
}
