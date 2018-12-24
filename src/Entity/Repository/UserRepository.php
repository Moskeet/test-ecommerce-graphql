<?php

namespace App\Entity\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param string $apiToken
     *
     * @return User|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserByToken(string $apiToken) :?User
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->where('md5(u.password) = :api_token')
            ->setParameter(':api_token', $apiToken)
            ->orderBy('u.id')
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
