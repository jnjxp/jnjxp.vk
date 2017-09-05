<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Web\Router;

use Jnjxp\Vk\Domain;

use Aura\Router\Map;

class Routes
{
    public function __invoke(Map $map)
    {
        $map->get('\\Login', '/login')->auth(false);

        $map->post('\\Authenticate', '/login', Domain\Login::class)
            ->auth(false);

        $map->post('\\Logout', '/logout', Domain\Logout::class)
            ->auth(true);
    }
}
