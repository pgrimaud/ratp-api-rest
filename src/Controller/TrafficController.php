<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;

class TrafficController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/traffic")
     *
     * @return JsonResponse
     */
    public function trafficAction()
    {
        // @todo test first traffic route
        return new JsonResponse(['traffic route']);
    }
}
