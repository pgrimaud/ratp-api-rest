<?php
namespace ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TrafficController extends Controller
{
    /**
     * @Route("/traffic", name="traffic")
     * @Method({"GET"})
     */
    public function trafficAction(Request $request)
    {
        return new JsonResponse(['payload' => 'OK']);
    }
}
