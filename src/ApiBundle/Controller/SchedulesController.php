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
     *          "description"="Code of transport line (e.g. 8)"
     *      },
     *      {
     *          "name"="station",
     *          "dataType"="string",
     *          "description"="Slug of the station (e.g. bonne+nouvelle)"
     *      },
     *      {
     *          "name"="way",
     *          "dataType"="string",
     *          "description"="Way on the line (A, R or A+R)"
     *      }
     *   },
     *   parameters={
     *    {
     *       "name"="id",
     *       "dataType"="string",
     *       "required"=false,
     *       "description"="(optional) id of line which have several destinations"
     *    }
     *  }
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
            'id'      => $request->get('id') ? $request->get('id') : $request->get('ID')
        ];

        $payload = $this->get('api.schedules')->get('schedules', $parameters);

        if (!$payload) {
            return $this->get('api.response')->notFound();
        }

        return $this->get('api.response')->format($payload);
    }
}
