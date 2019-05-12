<?php

namespace App\Controller;

use FOS\RestBundle\Exception\InvalidParameterException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @return RedirectResponse
     */
    public function index()
    {
        return new RedirectResponse('documentation');
    }

    /**
     * @param \Exception $exception
     */
    public function error(\Exception $exception)
    {
        dump($exception);
        // @todo format api response

        if ($exception instanceof NotFoundHttpException) {
            exit('not found');
        } elseif ($exception instanceof InvalidParameterException) {
            exit('invalid parameter action');
        } else {
            exit('internal error');
        }
    }
}
