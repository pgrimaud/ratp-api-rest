<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CacheService;
use App\Service\TrafficService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Exception\InvalidParameterException;
use FOS\RestBundle\View\View;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\RequestStack;

class TrafficController extends AppController
{
    /**
     * @var TrafficService
     */
    private $trafficService;

    /**
     * @param RequestStack $requestStack
     * @param CacheService $cacheService
     * @param TrafficService $trafficService
     */
    public function __construct(RequestStack $requestStack, CacheService $cacheService, TrafficService $trafficService)
    {
        parent::__construct($requestStack, $cacheService);
        $this->trafficService = $trafficService;
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get traffic of all lines"
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
        return $this->appView(
            $this->fetchData($this->trafficService, 'all')
        );
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get traffic of a specific type of transport"
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
        $data = $this->fetchData($this->trafficService);

        if (!isset($data[$type])) {
            throw new InvalidParameterException('Invalid line type : ' . $type);
        }

        return $this->appView([$type => $data[$type]]);
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get traffic of a specific line"
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
        $data = $this->fetchData($this->trafficService);

        if (!isset($data[$type])) {
            throw new InvalidParameterException('Invalid line type : ' . $type);
        }

        // manage breakage
        $code = strtoupper($code);

        $lineData = null;

        foreach ($data[$type] as $line) {
            if ($line['line'] == $code) {
                $lineData = $line;
            }
        }

        if (!$lineData) {
            throw new InvalidParameterException('Invalid line code : ' . $code);
        }

        return $this->appView($lineData);
    }
}
