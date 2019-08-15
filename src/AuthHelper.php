<?php

declare(strict_types=1);

namespace Jnjxp\Vk;

use Zend\Expressive\Authentication\UserInterface as User;

class AuthHelper implements AuthHelperInterface
{
    protected $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function getUser() : ?User
    {
        return $this->user;
    }

    public function isAuthenticated() : bool
    {
        return isset($this->user);
    }

    public function hasRole(string $role) : bool
    {
        $user = $this->getUser();
        if (! $user) {
            return false;
        }
        return in_array($role, (array) $user->getRoles());
    }
}
