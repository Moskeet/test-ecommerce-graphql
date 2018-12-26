<?php

namespace App\Entity\Repository;

use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return Transaction|null
     */
    public function getLatestByUser(User $user) :?Transaction
    {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->where('t.owner = :user_id')
            ->setParameter(':user_id', $user->getId())
            ->orderBy('t.id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return array
     */
    public function getStateChangable() :array
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where($qb->expr()->notIn('t.status', [Transaction::STATUS_ACCEPTED, Transaction::STATUS_DECLINED]));

        return $qb->getQuery()->execute();
    }
}
