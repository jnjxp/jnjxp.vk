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
 * @category  Action
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2019 Jake Johns
 * @license   http://jnj.mit-license.org/2019 MIT License
 * @link      http://jakejohns.net
 */

declare(strict_types = 1);

namespace Jnjxp\Vk\Action;

use Aura\Auth\Exception as Error;
use Aura\Auth\Service\LoginService;
use Jnjxp\Vk\Responder\ResponderInterface as Responder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Login
 *
 * @category Action
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  https://jnj.mit-license.org/ MIT License
 * @link     https://jakejohns.net
 *
 * @see AbstractHandler
 */
class Login extends AbstractHandler
{
    /**
     * __construct
     *
     * @param LoginService $service   login service
     * @param Responder    $responder responder
     *
     * @access public
     */
    public function __construct(LoginService $service, Responder $responder)
    {
        parent::__construct($service, $responder);
    }

    /**
     * Get Input
     *
     * @param Request $request request
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
     * @param Request $request request
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
            $this->getSession($request)->regenerateId();

            $response = $this->responder->authenticated($request, $auth);

        } catch (Error\UsernameMissing | Error\PasswordMissing $exception) {
            $response = $this->responder->invalid($request, $exception);
        } catch (Error $exception) {
            $response = $this->responder->authenticationFailed($request, $exception);
        } catch (\Throwable $exception) {
            $response = $this->responder->error($request, $exception);
        }

        return $response;
    }
}
