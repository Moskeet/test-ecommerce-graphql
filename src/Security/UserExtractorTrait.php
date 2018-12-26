<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

trait UserExtractorTrait
{
    /**
     * @param TokenStorageInterface $tokenStorage
     *
     * @return User|null
     */
    private function extractUser(TokenStorageInterface $tokenStorage)
    {
        if (($token = $tokenStorage->getToken()) === null) {
            return null;
        }

        if (($user = $token->getUser()) instanceof User) {
            return $user;
        }

        return null;
    }
}
