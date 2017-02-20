<?php
namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class LinesController extends Controller
{
    /**
     * @ApiDoc(
     *    description="Get a list of all lines",
     *    section="Lines"
     * )
     *
     * @Rest\View()
     * @Rest\Get("/lines")
     *
     * @param Request $request
     *
     * @return View
     */
    public function linesAction(Request $request)
    {
        $payload = $this->get('api.lines')->get('all');
        return $this->get('api.response')->format($payload);
    }

    /**
     * @ApiDoc(
     *    description="Get lines of a specific type of transport",
     *    section="Lines",
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
     * @Rest\Get("/lines/{type}")
     *
     * @param Request $request
     *
     * @return View
     */
    public function linesTypeAction(Request $request, $type)
    {
        $parameters = [
            'type' => $type
        ];

        $payload = $this->get('api.lines')->get('specific', $parameters);

        if (!$payload) {
            return $this->get('api.response')->notFound();
        }

        return $this->get('api.response')->format($payload);
    }

    /**
     * @ApiDoc(
     *    description="Get information of a specific line",
     *    section="Lines",
     *    requirements={
     *      {
     *          "name"="type",
     *          "dataType"="string",
     *          "description"="Type of transport (rers, metros, bus, tramways)"
     *      },
     *     {
     *          "name"="code",
     *          "dataType"="string",
     *          "description"="Code of transport line"
     *      }
     *   }
     * )
     *
     * @Rest\View()
     * @Rest\Get("/lines/{type}/{code}")
     *
     * @param Request $request
     * @param $type
     * @param $code
     *
     * @return View
     */
    public function linesInformationAction(Request $request, $type, $code)
    {
        $parameters = [
            'type' => $type,
            'code' => $code
        ];

        $payload = $this->get('api.lines')->get('line', $parameters);

        if (!$payload) {
            return $this->get('api.response')->notFound();
        }

        return $this->get('api.response')->format($payload);
    }
}
