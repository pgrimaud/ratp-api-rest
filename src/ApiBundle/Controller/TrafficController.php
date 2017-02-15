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
     *    description="Récupère la liste des lieux de l'application"
     * )
     *
     * @Rest\View()
     * @Rest\Get("/traffic")
     */
    public function trafficAction(Request $request)
    {
        //  http://apixha.ixxi.net/APIX?keyapp=FvChCBnSetVgTKk324rO&cmd=getTrafficSituation&category=all&networkType=all&withText=true&apixFormat=json&tmp=1487092706473
        $payload = [
            'payload' => 'OK'
        ];


        $hash = $this->get('api.storage')->getHash($request->getRequestUri());

        dump($hash);exit;

        // use cache here
        $cachedCategories = $this->get('cache.app')->getItem($this->get('app'));
        $this->get('api.traffic')->getTraffic();


        $view = View::create($payload);
        $view->setFormat('json');

        return $view;
    }
}
