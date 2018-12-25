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
            throw new \LogicException(sprintf('token = null'));
            return null;
        }

        if (($user = $token->getUser()) instanceof User) {
            return $user;
        }

        throw new \LogicException(sprintf('$user !instance, but %s', get_class($user)));
        return null;
    }
}
