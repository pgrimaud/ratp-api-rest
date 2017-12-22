<?php
namespace RatpApi\ApiBundle\Controller;

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
     *          "description"="Type of transport (rers, metros, tramways)"
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
    public function trafficTypeAction(Request $request, $type)
    {
        $parameters = [
            'type' => $type
        ];

        $payload = $this->get('api.traffic')->get('specific', $parameters);

        if (!$payload) {
            return $this->get('api.response')->notFound();
        }

        return $this->get('api.response')->format($payload);
    }

    /**
     * @ApiDoc(
     *    description="Get traffic of a specific line",
     *    section="Traffic",
     *    requirements={
     *      {
     *          "name"="type",
     *          "dataType"="string",
     *          "description"="Type of transport (rers, metros, tramways)"
     *      },
     *     {
     *          "name"="code",
     *          "dataType"="string",
     *          "description"="Code of transport line (e.g. 8)"
     *      }
     *   }
     * )
     *
     * @Rest\View()
     * @Rest\Get("/traffic/{type}/{code}")
     *
     * @param Request $request
     * @param $type
     * @param $code
     *
     * @return View
     */
    public function trafficLineAction(Request $request, $type, $code)
    {
        $parameters = [
            'type' => $type,
            'code' => $code
        ];

        $payload = $this->get('api.traffic')->get('line', $parameters);

        if (!$payload) {
            return $this->get('api.response')->notFound();
        }

        return $this->get('api.response')->format($payload);
    }
}
