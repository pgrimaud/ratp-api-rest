<?php
namespace RatpApi\ApiBundle\Controller;

use FOS\RestBundle\Exception\InvalidParameterException;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CoreController extends Controller
{
    /**
     * @Route("/", name="api_home")
     * @Method({"GET"})
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function indexAction(Request $request)
    {
        return new RedirectResponse($this->generateUrl('nelmio_api_doc_index'));
    }

    /**
     * @param Request $request
     * @param \Exception $exception
     * @return RedirectResponse
     */
    public function manageErrorAction(Request $request, \Exception $exception)
    {
        if ($request->server->get('REQUEST_SCHEME') == 'http' && $this->getParameter('schemes')) {
            $route = 'https://' . $request->server->get('SERVER_NAME') . $request->getRequestUri();
            return new RedirectResponse($route);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->get('api.response')->notFound();
        } elseif ($exception instanceof InvalidParameterException) {
            return $this->get('api.response')->invalidParameter($exception->getMessage());
        } else {
            return $this->get('api.response')->internalError();
        }
    }
}
