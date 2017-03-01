<?php
namespace ApiBundle\Services\Core;

use ApiBundle\Helper\XmlResponseHelper;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class ResponseService extends CoreService
{
    /**
     * @var RequestStack $requestStack
     */
    protected $requestStack;

    /**
     * @var int $apiVersion
     */
    protected $apiVersion;

    /**
     * ResponseService constructor.
     * @param RequestStack $requestStack
     * @param $apiVersion
     */
    public function __construct(RequestStack $requestStack, $apiVersion)
    {
        $this->requestStack = $requestStack;
        $this->apiVersion   = $apiVersion;
    }

    /**
     * @param $payload
     * @param int $httpCode
     * @return View
     */
    public function format($payload, $httpCode = Response::HTTP_OK)
    {
        $formatParameter = $this->requestStack->getCurrentRequest()->query->get('_format');
        $format          = $formatParameter ? $formatParameter : 'json';

        if ($format == 'xml') {
            $result = new XmlResponseHelper();
            $result->setResult($payload);
            $result->setMetadata($this->getMetadata());
        } else {
            $result = [
                'result'    => $payload,
                '_metadata' => $this->getMetadata()
            ];
        }

        $view = View::create($result);
        $view->setFormat($format);
        $view->setStatusCode($httpCode);

        return $view;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return [
            'call'    => $this->getCall(),
            'date'    => date('c'),
            'version' => $this->apiVersion
        ];
    }

    /**
     * @return string
     */
    private function getCall()
    {
        $method = $this->requestStack->getCurrentRequest()->getMethod();
        $path   = $this->requestStack->getCurrentRequest()->getPathInfo();

        return $method . ' ' . $path;
    }

    /**
     * @return View
     */
    public function notFound()
    {
        $payload = [
            'code'    => Response::HTTP_NOT_FOUND,
            'message' => 'Resource not found'
        ];

        return $this->format($payload, Response::HTTP_NOT_FOUND);
    }

    /**
     * @return View
     */
    public function internalError()
    {
        $payload = [
            'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Internal Error'
        ];

        return $this->format($payload, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
