<?php
namespace ApiBundle\Controller;

use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/error404", name="api_not_found")
     * @Method({"GET"})
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function notFoundAction(Request $request)
    {
        if ($request->server->get('REQUEST_SCHEME') == 'http' && $this->getParameter('schemes')) {
            $route = 'https://' . $request->server->get('SERVER_NAME') . $request->getRequestUri();
            return new RedirectResponse($route);
        }

        return $this->get('api.response')->notFound();
    }

    /**
     * @Route("/error500", name="api_internal_error")
     * @Method({"GET"})
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function internalErrorAction(Request $request)
    {
        if ($request->server->get('REQUEST_SCHEME') == 'http' && $this->getParameter('schemes')) {
            $route = 'https://' . $request->server->get('SERVER_NAME') . $request->getRequestUri();
            return new RedirectResponse($route);
        }

        return $this->get('api.response')->internalError();
    }
}
