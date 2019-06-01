<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Ratp\RatpMissionsService;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\RequestStack;

class MissionsController extends AppController
{
    /**
     * @var RatpMissionsService
     */
    private $ratpMissionsService;

    /**
     * @param RequestStack $requestStack
     * @param RatpMissionsService $ratpMissionsService
     */
    public function __construct(RequestStack $requestStack, RatpMissionsService $ratpMissionsService)
    {
        parent::__construct($requestStack);

        $this->ratpMissionsService = $ratpMissionsService;
    }

    /**
     * @SWG\Get(
     *     produces={"application/json", "application/xml"},
     *     description="Get stations of a mission on a specific RER line."
     * )
     * @SWG\Parameter(
     *     name="code",
     *     in="path",
     *     type="string",
     *     description="The code of RER line",
     *     enum={"A", "B"}
     * )
     * @SWG\Parameter(
     *     name="mission",
     *     in="path",
     *     type="string",
     *     description="The name of mission (e.g. ZEMA)"
     * )
     * @SWG\Tag(
     *   name="Missions",
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
     * @Rest\Get("/missions/rers/{code}/{mission}")
     *
     * @param string $code
     * @param string $mission
     *
     * @return View
     */
    public function stations(string $code, string $mission): View
    {
        $data = $this->fetchData(
            $this->ratpMissionsService,
            'mission',
            [
                'code'    => $code,
                'mission' => strtoupper($mission),
            ],
            (int)getenv('CACHE_MISSIONS')
        );

        return $this->appView($data);
    }
}
