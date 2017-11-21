<?php
// @codingStandardsIgnoreFile


namespace Jnjxp\Vk\Web\Action;

use Aura\View\View;

use Aura\Payload_Interface\PayloadInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 *
 * A generic Responder.
 *
 * @package radar/adr
 *
 */
class AbstractResponder
{
    /**
     *
     * The domain payload (i.e. the output from the domain).
     *
     * @var PayloadInterface | null
     *
     */
    protected $payload;
    /**
     *
     * The HTTP request.
     *
     * @var Request
     *
     */
    protected $request;
    /**
     *
     * The HTTP response.
     *
     * @var Response
     *
     */
    protected $response;

    /**
     * View
     *
     * @var View
     *
     * @access protected
     */
    protected $view;

    /**
     * __construct
     *
     * @param View      $view DESCRIPTION
     * @param Generator $url  DESCRIPTION
     *
     * @access public
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * Builds and returns the Response using the Request and Payload.
     *
     * @param Request          $request  The HTTP request object.
     * @param Response         $response The HTTP response object.
     * @param PayloadInterface $payload  The domain payload object.
     *
     * @return Response
     */
    public function __invoke(
        Request $request,
        Response $response,
        PayloadInterface $payload = null
    ) {
        $this->request  = $request;
        $this->response = $response;
        $this->payload  = $payload;

        return $this->respond();
    }

    protected function respond()
    {
        $method = $this->getMethodForPayload();
        $this->$method();
        return $this->response;
    }

    protected function redirect($uri, $status = 302)
    {
        return $this->response = $this->response
            ->withStatus($status)
            ->withHeader('Location', (string) $uri);
    }

    /**
     *
     * Returns the Responder method to call, based on the Payload status.
     *
     * @return string
     *
     */
    protected function getMethodForPayload()
    {
        if (! $this->payload) {
            return 'noContent';
        }

        $method = str_replace('_', '', strtolower($this->payload->getStatus()));
        return method_exists($this, $method) ? $method : 'unknown';
    }

    /**
     *
     * Encodes data into the Response body as JSON.
     *
     * @param mixed $data The data to encode.
     *
     */
    protected function jsonBody($data)
    {
        if (isset($data)) {
            $this->response = $this->response->withHeader('Content-Type', 'application/json');
            $this->response->getBody()->write(json_encode($data));
        }
    }

    /**
     * Render a view to response
     *
     * @param mixed $script DESCRIPTION
     * @param mixed $data   DESCRIPTION
     *
     * @return void
     *
     * @access protected
     */
    protected function viewBody($script, array $data = null)
    {
        $view = $this->view;
        $view->setView($script);
        if ($data) {
            $view->addData($data);
        }
        $this->response->getBody()->write($view());
    }

    /**
     *
     * Builds a Response for PayloadStatus::ERROR.
     *
     */
    protected function error()
    {
        $this->response = $this->response->withStatus(500);
        $this->jsonBody([
            'input' => $this->payload->getInput(),
            'error' => $this->payload->getOutput(),
        ]);
    }

    /**
     *
     * Builds a Response when the payload status is not recognized.
     *
     */
    protected function unknown()
    {
        $this->response = $this->response->withStatus(500);
        $this->jsonBody([
            'error' => 'Unknown domain payload status',
            'status' => $this->payload->getStatus(),
        ]);
    }
}
