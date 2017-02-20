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
}