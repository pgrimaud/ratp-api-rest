<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Ratp\RatpSchedulesService;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Exception\InvalidParameterException;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\RequestStack;

class SchedulesController extends AppController
{
    /**
     * @var RatpSchedulesService
     */
    private $ratpSchedulesService;

    /**
     * @param RequestStack $requestStack
     * @param RatpSchedulesService $ratpSchedulesService
     */
    public function __construct(RequestStack $requestStack, RatpSchedulesService $ratpSchedulesService)
    {
        parent::__construct($requestStack);

        $this->ratpSchedulesService = $ratpSchedulesService;
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get schedules at a specific station on a specific line."
     * )
     * @SWG\Parameter(
     *     name="type",
     *     in="path",
     *     type="string",
     *     description="The type of transport (metros, rers, tramways, buses or noctiliens)",
     *     enum={"metros", "rers", "tramways", "buses", "noctiliens"}
     * )
     * @SWG\Parameter(
     *     name="code",
     *     in="path",
     *     type="string",
     *     description="The code of transport line (e.g. 8)"
     * )
     * @SWG\Parameter(
     *     name="station",
     *     in="path",
     *     type="string",
     *     description="Slug of the station name (e.g. bonne+nouvelle)"
     * )
     * @SWG\Parameter(
     *     name="way",
     *     in="path",
     *     type="string",
     *     description="Way on the line",
     *     enum={"A", "R", "A+R"}
     * )
     * @SWG\Tag(
     *   name="Schedules",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad Request"
     * )
     *
     * @Rest\View()
     * @Rest\Get("/schedules/{type}/{code}/{station}/{way}")
     *
     * @param string $type
     * @param string $code
     * @param string $station
     * @param string $way
     *
     * @return View
     */
    public function schedules(string $type, string $code, string $station, string $way): View
    {
        $allowedTypes = [
            'rers',
            'metros',
            'tramways',
            'buses',
            'noctiliens'
        ];

        if (!in_array($type, $allowedTypes)) {
            throw new InvalidParameterException('Invalid line type : ' . $type);
        }

        $schedulesData = $this->fetchData(
            $this->ratpSchedulesService,
            'schedules',
            [
                'type'    => $type,
                'code'    => $code,
                'station' => $station,
                'way'     => $way,
            ],
            (int)getenv('CACHE_SCHEDULES')
        );

        return $this->appView($schedulesData);
    }
}
