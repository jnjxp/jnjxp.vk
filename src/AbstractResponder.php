<?php
/**
 * Voight-Kampff Authentication
 *
 * PHP version 5
 *
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Responder
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2015 Jake Johns
 * @license   http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Aura\Payload_Interface\PayloadInterface as Payload;
use Aura\Payload_Interface\PayloadStatus as Status;

use Aura\View\View;

/**
 * AbstractResponder
 *
 * @category Responder
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link     https://github.com/jnjxp/jnjxp.vk
 *
 * @abstract
 */
abstract class AbstractResponder
{
    /**
     * View
     *
     * @var View
     *
     * @access protected
     */
    protected $view;

    /**
     * Destination
     *
     * @var string
     *
     * @access protected
     */
    protected $destination = '/';

    /**
     * View script
     *
     * @var string
     *
     * @access protected
     */
    protected $viewScript;

    /**
     * Error view script
     *
     * @var string
     *
     * @access protected
     */
    protected $errorViewScript = 'auth/error';

    /**
     * Messenger attribute
     *
     * @var string
     *
     * @access protected
     */
    protected $messengerAttribute = 'jnjxp/molniya:messenger';

    /**
     * Success message
     *
     * @var string
     *
     * @access protected
     */
    protected $successMessage;

    /**
     * Request
     *
     * @var Request
     *
     * @access protected
     */
    protected $request;

    /**
     * Response
     *
     * @var Response
     *
     * @access protected
     */
    protected $response;

    /**
     * Payload
     *
     * @var Payload
     *
     * @access protected
     */
    protected $payload;

    /**
     * __construct
     *
     * @param View $view View Renderer
     *
     * @access public
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * Set Destination
     *
     * @param string $uri Destination path
     *
     * @return $this
     *
     * @access public
     */
    public function setDestination($uri)
    {
        $this->destination = $uri;
        return $this;
    }

    /**
     * Set view script
     *
     * @param string $script View script to render
     *
     * @return $this
     *
     * @access public
     */
    public function setViewScript($script)
    {
        $this->viewScript = $script;
        return $this;
    }

    /**
     * Set error view script
     *
     * @param string $script Error view script
     *
     * @return $this
     *
     * @access public
     */
    public function setErrorViewScript($script)
    {
        $this->errorViewScript = $script;
        return $this;
    }

    /**
     * Set messenger attribute
     *
     * @param string $attr Request attribute holding a flash messenger
     *
     * @return $this
     *
     * @access public
     */
    public function setMessengerAttribute($attr)
    {
        $this->messengerAttribute = $attr;
        return $this;
    }

    /**
     * Set success message
     *
     * @param string $message Flash message to set on success
     *
     * @return $this
     *
     * @access public
     */
    public function setSuccessMessage($message)
    {
        $this->successMessage = $message;
        return $this;
    }

    /**
     * Render appropriate view or redirect based on payload status
     *
     * @param Request  $request  PSR7 Request
     * @param Response $response PSR7 Response
     * @param Payload  $payload  Domain Payload
     *
     * @return Response
     *
     * @access public
     */
    public function __invoke(
        Request $request,
        Response $response,
        Payload $payload = null
    ) {
        $this->request  = $request;
        $this->response = $response;
        $this->payload  = $payload;

        $method = $this->getMethodForPayload();
        $this->$method();
        return $this->response;
    }


    /**
     * Get Method For Payload
     *
     * @return string
     *
     * @access protected
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
     * Render a view
     *
     * @param string $script Name of view to render
     * @param array  $data   Array of data to assign
     *
     * @return void
     *
     * @access protected
     */
    protected function render($script, array $data = array())
    {
        $view = $this->view;
        $view->setView($script);
        $view->addData(['request' => $this->request]);
        $view->addData($data);
        $this->response->getBody()->write($view());
    }

    /**
     * Flash Message if messenger is present
     *
     * @return void
     *
     * @access protected
     */
    protected function flashMessage()
    {
        if ($msg = $this->request->getAttribute($this->messengerAttribute)) {
            $msg->success($this->successMessage);
        }
    }

    /**
     * Unknown status
     *
     * @return void
     *
     * @access protected
     */
    protected function unknown()
    {
        $this->response = $this->response->withStatus(500);
        $this->response->getBody()->write(
            'Unknown authentication payload status: '
            . $this->payload->getStatus()
        );
    }

    /**
     * No Content - render forms
     *
     * @return void
     *
     * @access protected
     */
    protected function noContent()
    {
        $this->response = $this->response->withStatus(200);
        $this->render($this->viewScript);
    }

    /**
     * Success
     *
     * @return void
     *
     * @access protected
     */
    protected function success()
    {
        $this->flashMessage();

        $this->response = $this->response
            ->withStatus(302)
            ->withHeader('Location', $this->destination);
    }

    /**
     * Failure
     *
     * @return void
     *
     * @access protected
     */
    protected function failure()
    {
        $this->response = $this->response->withStatus(400);

        $this->render(
            $this->viewScript,
            [
                'failed' => true,
                'input' => $this->payload->getInput(),
                'error' => $this->payload->getOutput()
            ]
        );
    }

    /**
     * Error
     *
     * @return void
     *
     * @access protected
     */
    protected function error()
    {
        $this->response = $this->response->withStatus(500);
        $this->render(
            $this->errorViewScript,
            ['error' => $this->payload->getOutput()]
        );
    }
}
