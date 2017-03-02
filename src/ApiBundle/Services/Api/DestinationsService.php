<?php
namespace ApiBundle\Services\Api;

use ApiBundle\Helper\NetworkHelper;
use FOS\RestBundle\Exception\InvalidParameterException;
use Ratp\Api;
use Ratp\Direction;
use Ratp\Directions;
use Ratp\Line;
use Ratp\Reseau;

class DestinationsService extends ApiService implements ApiDataInterface
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
    protected function getLine($parameters)
    {
        $destinations = [];

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

        $networkRatp = NetworkHelper::typeSlug($parameters['type'], true);
        $prefixcode  = NetworkHelper::forcePrefix($parameters['type']);

        $reseau = new Reseau();
        $reseau->setCode($networkRatp);

        $line = new Line();
        $line->setReseau($reseau);
        $line->setCode($prefixcode . $parameters['code']);

        $directionsApi = new Directions($line);

        $api    = new Api();
        $return = $api->getDirections($directionsApi)->getReturn();

        $this->isAmbiguous($return);

        foreach ($return->getDirections() as $direction) {
            /** @var Direction $direction */
            $destinations[] = [
                'name' => $direction->getName(),
                'way'  => $direction->getSens()
            ];
        }

        return [
            'destinations' => $destinations
        ];
    }
}
