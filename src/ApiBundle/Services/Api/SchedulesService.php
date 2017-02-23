<?php
namespace ApiBundle\Services\Api;

use ApiBundle\Helper\NetworkHelper;
use Ratp\Api;
use Ratp\Direction;
use Ratp\Line;
use Ratp\MissionsNext;
use Ratp\Reseau;
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
        } else if (in_array($parameters['type'], ['rers'])) {
            $line->setId($prefix . strtoupper($parameters['code']));
        } else {
            /** @FIXME RAPT PLS */
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


        foreach ($result->getMissions() as $mission) {
            $schedules[] = $mission->stationsMessages;
        }

        return $schedules;
    }
}
