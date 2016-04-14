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
            $this->response = $this->response
                ->withHeader('Content-Type', 'application/json');
            $this->response->getBody()->write(json_encode($data));
        }
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

        $exception = $this->payload->getOutput();

        $error = sprintf(
            '%s: %s',
            get_class($exception),
            $exception->getMessage()
        );

        $this->jsonBody(['error' => $error]);
    }
}

