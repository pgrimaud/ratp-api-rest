<?php
namespace RatpApi\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class StationsController extends Controller
{
    /**
     * @ApiDoc(
     *    description="Get stations of a specific line",
     *    section="Stations",
     *    requirements={
     *      {
     *          "name"="type",
     *          "dataType"="string",
     *          "description"="Type of transport (rers, metros, bus, tramways, noctiliens)"
     *      },
     *     {
     *          "name"="code",
     *          "dataType"="string",
     *          "description"="Code of transport line (e.g. 8)"
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
     * @Rest\Get("/stations/{type}/{code}")
     *
     * @param Request $request
     * @param $type
     * @param $code
     *
     * @return View
     */
    public function stationsAction(Request $request, $type, $code)
    {
        $parameters = [
            'type' => $type,
            'code' => $code,
            'id'   => $request->get('id')
        ];

        $payload = $this->get('api.stations')->get('line', $parameters);

        if (!$payload) {
            return $this->get('api.response')->notFound();
        }

        return $this->get('api.response')->format($payload);
    }
}
