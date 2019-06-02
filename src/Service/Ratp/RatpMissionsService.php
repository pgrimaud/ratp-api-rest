<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use App\Exception\AmbiguousException;
use App\Utils\NameHelper;

use Ratp\{Api, GetMission, Line, Mission, WrMission};

class RatpMissionsService extends AbstractRatpService implements RatpServiceInterface
{
    /**
     * @param array $parameters
     *
     * @return array
     *
     * @throws AmbiguousException
     */
    protected function getMissions(array $parameters = []): array
    {
        $stations = [];

        $line = new Line();
        $line->setId('R' . $parameters['code']);

        $mission = new Mission();
        $mission->setLine($line);
        $mission->setId($parameters['mission']);

        $getMission = new GetMission($mission, date('YmdHi'), false, false);

        $api = new Api(null, [
            'connection_timeout' => getenv('API_TIMEOUT')
        ]);

        /** @var WrMission $result */
        $result = $api->getMission($getMission)->getReturn();

        if (!$result->getMission()->getStations()) {
            throw new AmbiguousException($parameters['mission'] . ' mission is invalid.');
        }

        foreach ($result->getMission()->getStations() as $station) {
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
