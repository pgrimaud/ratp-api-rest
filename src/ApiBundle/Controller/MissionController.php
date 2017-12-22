<?php
namespace RatpApi\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MissionController extends Controller
{
    /**
     * @ApiDoc(
     *    description="Get stations of a mission on a specific RER line",
     *    section="Missions",
     *    requirements={
     *      {
     *          "name"="code",
     *          "dataType"="string",
     *          "description"="Code of RER line (e.g. A)"
     *      },
     *      {
     *          "name"="mission",
     *          "dataType"="string",
     *          "description"="Name of mission (e.g. ZEMA)"
     *      }
     *   }
     * )
     *
     * @Rest\View()
     * @Rest\Get("/mission/rers/{code}/{mission}")
     *
     * @param Request $request
     * @param $code
     * @param $mission
     *
     * @return View
     */
    public function missionAction(Request $request, $code, $mission)
    {
        $parameters = [
            'code'    => $code,
            'mission' => $mission,
        ];

        $payload = $this->get('api.mission')->get('mission', $parameters);
        return $this->get('api.response')->format($payload);
    }
}
