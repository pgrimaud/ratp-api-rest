<?php
namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class TrafficController extends Controller
{
    /**
     * @ApiDoc(
     *    description="Get traffic of all lines",
     *    section="Traffic"
     * )
     *
     * @Rest\View()
     * @Rest\Get("/traffic")
     *
     * @param Request $request
     *
     * @return View
     */
    public function trafficAction(Request $request)
    {
        $payload = $this->get('api.traffic')->get('all');
        return $this->get('api.response')->format($payload);
    }

    /**
     * @ApiDoc(
     *    description="Get traffic of a specific type of transport",
     *    section="Traffic",
     *    requirements={
     *      {
     *          "name"="type",
     *          "dataType"="string",
     *          "description"="Type of transport (rers, metros, bus, tramways)"
     *      }
     *   }
     * )
     *
     * @Rest\View()
     * @Rest\Get("/traffic/{type}")
     *
     * @param Request $request
     * @param $type
     *
     * @return View
     */
    public function trafficTransportAction(Request $request, $type)
    {
        $payload = $this->get('api.traffic')->get($type);

        return $this->get('api.response')->format($payload);
    }
}
