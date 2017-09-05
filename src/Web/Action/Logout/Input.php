<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Web\Action\Logout;

use Psr\Http\Message\ServerRequestInterface as Request;
use Vperyod\AuthHandler\AuthRequestAwareTrait;

class Input
{
    use AuthRequestAwareTrait;

    public function __invoke(Request $request)
    {
        return [$this->getAuth($request)];
    }
}
