<?php
namespace RatpApi\ApiBundle\Services\Api;

use Ratp\Api;
use Ratp\GetMission;
use Ratp\Line;
use Ratp\Mission;

class MissionService extends ApiService implements ApiDataInterface
{
    /**
     * LinesService constructor.
     * We override parent constructor to inject ttl.
     *
     * @param $ttl
     */
    public function __construct($ttl)
    {
        parent::__construct($ttl);
    }

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
    protected function getMission($parameters)
    {
        $stations = [];

        $line = new Line();
        $line->setId('R' . $parameters['code']);

        $mission = new Mission();
        $mission->setLine($line);
        $mission->setId($parameters['mission']);

        $getMission = new GetMission($mission, date('YmdHi'), false, false);

        $api = new Api();

        /** @var \Ratp\WrMission $result */
        $result = $api->getMission($getMission)->getReturn();

        foreach ($result->getMission()->getStations() as $station) {
            $stations[] = [
                'name' => $station->getName(),
            ];
        }

        return [
            'stations' => $stations
        ];
    }
}
