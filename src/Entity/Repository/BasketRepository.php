<?php

namespace App\Entity\Repository;

use App\Entity\Basket;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class BasketRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return Basket|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserBasket(User $user) :?Basket
    {
        $qb = $this->createQueryBuilder('b');
        $qb
            ->where('b.owner = :owner')
            ->setParameter(':owner', $user->getId())
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
