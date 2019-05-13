<?php

declare(strict_types=1);

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;

class TrafficController extends AppController
{
    /**
     * @SWG\Tag(
     *   name="Traffic"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="test"
     * )
     *
     * @Rest\View()
     * @Rest\Get("/traffic")
     */
    public function trafficAction(): View
    {
        return $this->appView(['metro' => 'ok']);
    }
}
