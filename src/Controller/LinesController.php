<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Ratp\RatpLinesService;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Exception\InvalidParameterException;
use FOS\RestBundle\View\View;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class LinesController extends AppController
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param RequestStack $requestStack
     * @param RatpLinesService $ratpLinesService
     */
    public function __construct(RequestStack $requestStack, RatpLinesService $ratpLinesService)
    {
        parent::__construct($requestStack);

        $this->data = $this->fetchData(
            $ratpLinesService,
            'all',
            [],
            (int)getenv('CACHE_LINES'),
            getenv('API_VERSION') . '_lines'
        );
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get all lines from the RATP network."
     * )
     * @SWG\Tag(
     *   name="Lines",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK"
     * )
     *
     * @Rest\View()
     * @Rest\Get("/lines")
     *
     * @Route(methods={"GET", "OPTIONS", "HEAD"})
     *
     * @return View
     */
    public function lines(): View
    {
        return $this->appView($this->data);
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get all lines of a specific type of transport from the RATP network."
     * )
     * @SWG\Parameter(
     *     name="type",
     *     in="path",
     *     type="string",
     *     description="The type of transport (metros, rers, tramways, bus or noctiliens)",
     *     enum={"metros", "rers", "tramways", "bus", "noctiliens"}
     * )
     * @SWG\Tag(
     *   name="Lines",
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
     * @Rest\Get("/lines/{type}")
     *
     * @Route(methods={"GET", "OPTIONS", "HEAD"})
     *
     * @param string $type
     *
     * @return View
     */
    public function linesType(string $type): View
    {
        if (!isset($this->data[$type])) {
            throw new InvalidParameterException('Invalid line type : ' . $type);
        }

        return $this->appView([$type => $this->data[$type]]);
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get information about a specific line from the RATP network."
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
     *   name="Lines",
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
     * @Rest\Get("/lines/{type}/{code}")
     *
     * @Route(methods={"GET", "OPTIONS", "HEAD"})
     *
     * @param string $type
     * @param string $code
     *
     * @return View
     */
    public function linesCode(string $type, string $code): View
    {
        if (!isset($this->data[$type])) {
            throw new InvalidParameterException('Invalid line type : ' . $type);
        }

        // manage breakage
        $code = strtoupper($code);

        $lineData = null;

        foreach ($this->data[$type] as $line) {
            if ($line['code'] === $code) {
                $lineData = $line;
            }
        }

        if (!$lineData) {
            throw new InvalidParameterException('Invalid line code : ' . $code);
        }

        return $this->appView($lineData);
    }
}
