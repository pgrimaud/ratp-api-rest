<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Ratp\RatpTrafficService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Exception\InvalidParameterException;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\RequestStack;

class TrafficController extends AppController
{
    /**
     * @var array
     */
    private array $data;

    /**
     * @param RequestStack       $requestStack
     * @param RatpTrafficService $trafficService
     */
    public function __construct(RequestStack $requestStack, RatpTrafficService $trafficService)
    {
        parent::__construct($requestStack);

        $this->data = $this->fetchData(
            $trafficService,
            'traffic',
            [],
            (int) getenv('CACHE_TRAFFIC'),
            getenv('API_VERSION') . '_traffic'
        );
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get traffic of all lines from the RATP network."
     * )
     * @SWG\Tag(
     *   name="Traffic",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK"
     * )
     *
     * @Rest\View()
     * @Rest\Get("/traffic")
     *
     * @return View
     */
    public function traffic(): View
    {
        return $this->appView($this->data);
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get traffic of a specific type of transport from the RATP network."
     * )
     * @SWG\Parameter(
     *     name="type",
     *     in="path",
     *     type="string",
     *     description="The type of transport (metros, rers or tramways)",
     *     enum={"metros", "rers", "tramways"}
     * )
     * @SWG\Tag(
     *   name="Traffic",
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
     * @Rest\Get("/traffic/{type}")
     *
     * @param string $type
     *
     * @return View
     */
    public function trafficType(string $type): View
    {
        $allowedTypes = [
            'rers',
            'metros',
            'tramways',
        ];

        if (!in_array($type, $allowedTypes)) {
            throw new InvalidParameterException('Invalid line type : ' . $type);
        }

        return $this->appView([$type => $this->data[$type]]);
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get traffic of a specific line from the RATP network."
     * )
     * @SWG\Parameter(
     *     name="type",
     *     in="path",
     *     type="string",
     *     description="The type of transport (metros, rers or tramways)",
     *     enum={"metros", "rers", "tramways"}
     * )
     * @SWG\Parameter(
     *     name="code",
     *     in="path",
     *     type="string",
     *     description="The code of transport line"
     * )
     * @SWG\Tag(
     *   name="Traffic",
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
     * @Rest\Get("/traffic/{type}/{code}")
     *
     * @param string $type
     * @param string $code
     *
     * @return View
     */
    public function trafficCode(string $type, string $code): View
    {
        $allowedTypes = [
            'rers',
            'metros',
            'tramways',
        ];

        if (!in_array($type, $allowedTypes)) {
            throw new InvalidParameterException('Invalid line type : ' . $type);
        }

        // manage breakage
        $code = strtoupper($code);

        $lineData = null;

        foreach ($this->data[$type] as $line) {
            if ($line['line'] === $code) {
                $lineData = $line;
            }
        }

        if (!$lineData) {
            throw new InvalidParameterException('Invalid line code : ' . $code);
        }

        return $this->appView($lineData);
    }
}
