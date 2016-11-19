<?php
/**
 * Jnjxp\Vk
 *
 * PHP version 5
 *
 * Copyright (C) 2016 Jake Johns
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 *
 * @category  Responder
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2016 Jake Johns
 * @license   http://jnj.mit-license.org/2016 MIT License
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk\Responder;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * JsonResponderTrait
 *
 * @category Responder
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/ MIT License
 * @link     https://github.com/jnjxp/jnjxp.vk
 *
 * @trait
 */
trait JsonResponderTrait
{
    /**
     * JsonBody
     *
     * @param mixed $data data to encode
     *
     * @return void
     *
     * @access protected
     */
    protected function jsonBody($data)
    {
        if (isset($data)) {
            $response = $this->getResponse()
                ->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode($data));
            $this->setResponse($response);
        }
    }

    /**
     * Get Response
     *
     * @return Response
     *
     * @access protected
     */
    abstract protected function getResponse();

    /**
     * Set Response
     *
     * @param Response $response Response
     *
     * @return $this
     *
     * @access protected
     */
    abstract protected function setResponse(Response $response);
}

