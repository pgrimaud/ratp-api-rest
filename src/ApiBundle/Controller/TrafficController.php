<?php
namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class TrafficController extends Controller
{
    /**
     * @ApiDoc(
     *    description="Get all traffic on all lines"
     * )
     *
     * @Rest\View()
     * @Rest\Get("/traffic")
     *
     * @param Request $request
     * @return View
     */
    public function trafficAction(Request $request)
    {
        $payload = $this->get('api.traffic')->get('all');

        $view = View::create($payload);
        $view->setFormat('json');
        return $view;
    }
}
