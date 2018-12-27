<?php

namespace App\GraphQL;

use App\Entity\User;
use App\GraphQL\Exception\NotAuthorizedException;

trait CheckRoleAllowedTrait
{
    /**
     * @param string $role
     *
     * @param User|null $user
     */
    private function checkRoleAllowed(string $role, User $user = null)
    {
        if (!$user || !$user->hasRole($role)) {
            throw new NotAuthorizedException();
        }
    }
}
