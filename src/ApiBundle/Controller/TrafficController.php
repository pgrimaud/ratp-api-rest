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
     *    description="Get all traffic on all lines"
     * )
     *
     * @Rest\View()
     * @Rest\Get("/traffic")
     */
    public function trafficAction(Request $request)
    {
        //  http://apixha.ixxi.net/APIX?keyapp=FvChCBnSetVgTKk324rO&cmd=getTrafficSituation&category=all&networkType=all&withText=true&apixFormat=json&tmp=1487092706473

        $hash = $this->get('api.storage')->getHash($request->getRequestUri());

        // use cache here
        $cachedData = $this->get('api.storage')->getCache($hash);

        if ($cachedData->isHit()) {
            echo 'exist';
            exit;
        } else {

            $traffic = $this->get('api.traffic')->getAllTraffic();

            $this->get('api.storage')->setCache($cachedData, $hash, $traffic);
        }


        $view = View::create();
        $view->setFormat('json');
        return $view;
    }
}
