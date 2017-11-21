<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Web;

use Aura\Router\Map;
use Jnjxp\Vk\Domain;

class Action
{
    const LOGIN        = self::class . '\\Login';
    const AUTHENTICATE = self::class . '\\Authenticate';
    const LOGOUT       = self::class . '\\Logout';

    public function __invoke(Map $map)
    {
        $map->get(self::LOGIN, '/login')->auth(false);

        $map->post(self::AUTHENTICATE, '/login', Domain\Login::class)
            ->auth(false);

        $map->post(self::LOGOUT, '/logout', Domain\Logout::class)
            ->auth(true);
    }
}

