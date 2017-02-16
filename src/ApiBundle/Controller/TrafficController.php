<?php
namespace ApiBundle\Controller;

use ApiBundle\Services\ApiService;
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
        //  http://apixha.ixxi.net/APIX?keyapp=FvChCBnSetVgTKk324rO&cmd=getTrafficSituation&category=all&networkType=all&withText=true&apixFormat=json&tmp=1487092706473

        $payload = $this->get('api.traffic')->get('all');
        dump($payload);
        exit;
        exit;
        /** @var CacheItem $cachedData */
        $cachedData = $this->get('api.storage')->getCache($hash);

        if ($cachedData->isHit()) {
            $payload = unserialize($cachedData->get());
        } else {
            $traffic = $this->get('api.traffic')->getAllTraffic();
            $this->get('api.storage')->setCache($cachedData, $traffic);
            $payload = unserialize($cachedData->get());
        }

        $view = View::create($payload);
        $view->setFormat('json');
        return $view;
    }
}
