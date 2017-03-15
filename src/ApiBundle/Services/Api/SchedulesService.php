<?php
namespace ApiBundle\Services\Api;

use ApiBundle\Helper\NamesHelper;
use ApiBundle\Helper\NetworkHelper;
use FOS\RestBundle\Exception\InvalidParameterException;
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

        if (!in_array($parameters['type'], $typesAllowed)) {
            throw new InvalidParameterException(sprintf('Unknown type : %s', $parameters['type']));
        }

        $prefix = NetworkHelper::typeSlugSchedules($parameters['type']);

        $line = new Line();

        // prefix line name
        if (in_array($parameters['type'], ['bus', 'metros', 'tramways', 'noctiliens'])) {
            $line->setId($prefix . $parameters['code']);
        } elseif (in_array($parameters['type'], ['rers'])) {
            $line->setId($prefix . strtoupper($parameters['code']));
        }

        // lines with several destinations
        if ($parameters['id'] != '') {
            $line->setId($parameters['id']);
        }

        $station = new Station();
        $station->setLine($line);
        $station->setName(NamesHelper::clean($parameters['station']));

        $direction = new Direction();
        $direction->setSens($parameters['way']);

        $mission = new MissionsNext($station, $direction);

        $api    = new Api();
        $return = $api->getMissionsNext($mission)->getReturn();

        $this->isAmbiguous($return);

        if ($return->getMissions()) {
            foreach ($return->getMissions() as $mission) {
                $schedules[] = [
                    'code'        => $mission->code,
                    'message'     => $mission->stationsMessages[0],
                    'destination' => $return->getArgumentDirection()->getName(),
                ];
            }
        } else {
            $schedules[] = [
                'code'        => 'Schedules unavailable',
                'message'     => 'Schedules unavailable',
                'destination' => $return->getArgumentDirection()->getName(),
            ];
        }

        return [
            'schedules' => $schedules
        ];
    }
}
