<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CacheService;
use App\Service\Ratp\RatpLinesService;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\RequestStack;

class LinesController extends AppController
{
    /**
     * @var RatpLinesService
     */
    private $ratpLinesService;

    /**
     * @param RequestStack $requestStack
     * @param CacheService $cacheService
     * @param RatpLinesService $ratpLinesService
     */
    /*public function __construct(RequestStack $requestStack, CacheService $cacheService, RatpLinesService $ratpLinesService)
    {
        parent::__construct($requestStack, $cacheService);
        $this->ratpLinesService = $ratpLinesService;
    }*/

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get all lines from RATP network"
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
     * @return View
     */
    public function lines(): View
    {
        //return $this->appView($this->ratpLinesService->get('all'));
    }
}
