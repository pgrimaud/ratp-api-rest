<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Ratp\RatpDestinationsService;
use App\Service\Ratp\RatpStationsService;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Exception\InvalidParameterException;
use FOS\RestBundle\View\View;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\RequestStack;

class DestinationsController extends AppController
{
    /**
     * @var RatpStationsService
     */
    private $ratpDestinationsService;

    /**
     * @param RequestStack $requestStack
     * @param RatpDestinationsService $ratpDestinationsService
     */
    public function __construct(RequestStack $requestStack, RatpDestinationsService $ratpDestinationsService)
    {
        parent::__construct($requestStack);

        $this->ratpDestinationsService = $ratpDestinationsService;
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get destinations of a specific line from the RATP network."
     * )
     * @SWG\Parameter(
     *     name="type",
     *     in="path",
     *     type="string",
     *     description="The type of transport (metros, rers, tramways, bus or noctiliens)",
     *     enum={"metros", "rers", "tramways", "bus", "noctiliens"}
     * )
     * @SWG\Parameter(
     *     name="code",
     *     in="path",
     *     type="string",
     *     description="The code of transport line"
     * )
     * @SWG\Tag(
     *   name="Destinations",
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
     * @Rest\Get("/destinations/{type}/{code}")
     *
     * @param string $type
     * @param string $code
     *
     * @return View
     */
    public function stations(string $type, string $code): View
    {
        $allowedTypes = [
            'rers',
            'metros',
            'tramways',
            'bus',
            'noctiliens'
        ];

        if (!in_array($type, $allowedTypes)) {
            throw new InvalidParameterException('Invalid line type : ' . $type);
        }

        $data = $this->fetchData(
            $this->ratpDestinationsService,
            'destinations',
            [
                'type' => $type,
                'code' => $code,
            ],
            (int)getenv('CACHE_DESTINATIONS')
        );

        return $this->appView($data);
    }
}
