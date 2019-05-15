<?php

declare(strict_types = 1);

namespace Jnjxp\Vk\Router;

use Aura\Router\Map;
use Jnjxp\Vk\LoginHandler as Login;
use Jnjxp\Vk\LogoutHandler as Logout;

class RouteMapBuilder
{
    public function __invoke(Map $map)
    {
        $map->post(Login::class, '/login')->auth(false);
        $map->post(Logout::class, '/logout')->auth(true);
    }
}

