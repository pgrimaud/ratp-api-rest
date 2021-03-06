<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Ratp\RatpStationsService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Exception\InvalidParameterException;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class StationsController extends AppController
{
    /**
     * @var RatpStationsService
     */
    private RatpStationsService $ratpStationsService;

    /**
     * @param RequestStack        $requestStack
     * @param RatpStationsService $ratpStationsService
     */
    public function __construct(RequestStack $requestStack, RatpStationsService $ratpStationsService)
    {
        parent::__construct($requestStack);

        $this->ratpStationsService = $ratpStationsService;
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get stations of a specific line from the RATP network."
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
     *     description="The code of transport line"
     * )
     * @SWG\Parameter(
     *     name="way",
     *     in="query",
     *     type="string",
     *     description="Way on the line",
     *     enum={"A", "R"}
     * )
     * @SWG\Tag(
     *   name="Stations",
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
     * @Rest\Get("/stations/{type}/{code}")
     *
     * @param Request $request
     * @param string  $type
     * @param string  $code
     *
     * @return View
     */
    public function stations(Request $request, string $type, string $code): View
    {
        $allowedTypes = [
            'rers',
            'metros',
            'tramways',
            'buses',
            'noctiliens',
        ];

        if (!in_array($type, $allowedTypes)) {
            throw new InvalidParameterException('Invalid line type : ' . $type);
        }

        $data = $this->fetchData(
            $this->ratpStationsService,
            'Stations',
            [
                'type' => $type,
                'code' => $code,
                'way'  => $request->get('way'),
            ],
            (int) getenv('CACHE_STATIONS'),
            '',
            [
                'way' => $request->get('way'),
            ]
        );

        return $this->appView($data);
    }
}
