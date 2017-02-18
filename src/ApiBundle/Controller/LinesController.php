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
     *    description="Get list of all lines",
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
        /** @todo */
        return null;
    }
}