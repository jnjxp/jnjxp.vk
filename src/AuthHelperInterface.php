<?php

declare(strict_types=1);

namespace Jnjxp\Vk;

use Zend\Expressive\Authentication\UserInterface as User;

interface AuthHelperInterface
{
    public function getUser() : ?User;

    public function isAuthenticated() : bool;

    public function hasRole(string $role) : bool;
}
