<?php
namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SchedulesController extends Controller
{
    /**
     * @ApiDoc(
     *    description="Get schedules at a specific station on a specific line",
     *    section="Schedules",
     *    requirements={
     *      {
     *          "name"="type",
     *          "dataType"="string",
     *          "description"="Type of transport (rers, metros, bus, tramways, noctiliens)"
     *      },
     *      {
     *          "name"="code",
     *          "dataType"="string",
     *          "description"="Code of transport line"
     *      },
     *      {
     *          "name"="station",
     *          "dataType"="string",
     *          "description"="Name of the station"
     *      },
     *      {
     *          "name"="way",
     *          "dataType"="string",
     *          "description"="Way on the line (A or R)"
     *      }
     *   }
     * )
     *
     * @Rest\View()
     * @Rest\Get("/schedules/{type}/{code}/{station}/{way}")
     *
     * @param Request $request
     * @param $type
     * @param $code
     * @param $station
     * @param $way
     *
     * @return View
     */
    public function schedulesAction(Request $request, $type, $code, $station, $way)
    {
        $parameters = [
            'type'    => $type,
            'code'    => $code,
            'station' => $station,
            'way'     => $way,
        ];

        $payload = $this->get('api.schedules')->get('schedules', $parameters);

        if (!$payload) {
            return $this->get('api.response')->notFound();
        }

        return $this->get('api.response')->format($payload);
    }

}
