<?php
/**
 * Vk
 *
 * PHP version 7
 *
 * Copyright (C) 2019 Jake Johns
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 *
 * @category  Handler
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2019 Jake Johns
 * @license   http://jnj.mit-license.org/2019 MIT License
 * @link      http://jakejohns.net
 */

declare(strict_types = 1);

namespace Jnjxp\Vk;

use Aura\Auth;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * LoginHandler
 *
 * @see AbstractHandler
 */
class LoginHandler extends AbstractHandler
{
    /**
     * __construct
     *
     * @param Auth\Service\LoginService $service
     * @param ResponderInterface $responder
     *
     * @access public
     */
    public function __construct(
        Auth\Service\LoginService $service,
        ResponderInterface $responder
    ) {
        parent::__construct($service, $responder);
    }

    /**
     * Get Input
     *
     * @param Request $request
     *
     * @return array
     *
     * @access protected
     */
    protected function getInput(Request $request) : array
    {
        $post = $request->getParsedBody();

        return [
            'username' => $post['username'] ?? null,
            'password' => $post['password'] ?? null,
        ];
    }

    /**
     * Handle login request
     *
     * @param Request $request
     *
     * @return Response
     *
     * @access public
     */
    public function handle(Request $request) : Response
    {
        try {

            $auth  = $this->getAuth($request);
            $input = $this->getInput($request);

            $this->service->login($auth, $input);

            $response = $this->responder->authenticated($request);

        } catch (Auth\Exception\UsernameMissing| Auth\Exception\PasswordMissing $exception) {
            $response = $this->responder->invalid($request, 'Enter Required information');
        } catch (Auth\Exception $exception) {
            $response = $this->responder->notAuthenticated($request);
        } catch (\Throwable $exception) {
            $response = $this->responder->error($request, $exception);
        }

        return $response;
    }
}
