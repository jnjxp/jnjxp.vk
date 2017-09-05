<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Web\Action\Authenticate;

use Psr\Http\Message\ServerRequestInterface as Request;
use Vperyod\AuthHandler\AuthRequestAwareTrait;

class Input
{
    use AuthRequestAwareTrait;

    public function __invoke(Request $request)
    {
        return [
            'auth'        => $this->getAuth($request),
            'credentials' => $this->getCredentials($request)
        ];
    }

    protected function getCredentials(Request $request)
    {
        $post = $request->getParsedBody();

        return [
            'username' => $post['username'] ?? null,
            'password' => $post['password'] ?? null,
        ];
    }
}
