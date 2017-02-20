<?php
namespace ApiBundle\Services\Api;

use ApiBundle\Helper\NamesHelper;
use Ratp\Api;
use Ratp\Line;
use Ratp\Lines;

class LinesService extends ApiService implements ApiDataInterface
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
     * @return array
     */
    protected function getAll()
    {
        $return = [];

        $lines = new Lines();
        $api   = new Api();

        foreach ($api->getLines($lines)->getReturn() as $line) {
            /** @var Line $line */
            if ($line instanceof Line) {
                $type = NamesHelper::sdkSlug($line->getReseau()->getCode());

                if ($type) {
                    $return[$type][] = [
                        'id'         => $line->getId(),
                        'code'       => $line->getCode(),
                        'name'       => $line->getReseau()->getName() . ' ' . $line->getCode(),
                        'directions' => $line->getName(),
                        'image'      => $line->getImage()
                    ];
                }


            }
        }

        return $return;
    }
}
