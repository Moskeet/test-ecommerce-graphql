<?php

namespace App\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class GraphQLToken extends AbstractToken
{
    /**
     * @param array $roles
     */
    public function __construct(array $roles = [])
    {
        parent::__construct($roles);

        // If the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    /**
     * @return mixed|string
     */
    public function getCredentials() :string
    {
        return '';
    }
}
