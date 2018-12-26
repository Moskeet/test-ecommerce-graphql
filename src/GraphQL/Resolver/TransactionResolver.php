<?php

namespace App\GraphQL\Resolver;

use App\Entity\Transaction;
use App\Entity\User;
use App\GraphQL\Exception\NotAuthorizedException;
use App\Security\UserExtractorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransactionResolver implements ResolverInterface
{
    use InvokeTrait;
    use UserExtractorTrait;


    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage
    ) {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $id
     *
     * @return Transaction
     */
    public function getTransaction(string $id) :Transaction
    {
        $this->checkRoleAllowed('ROLE_ADMIN');

        return $this->em->find(Transaction::class, $id);
    }

    /**
     * @return string
     */
    public function getTransactionStatus() :string
    {
        $user = $this->checkRoleAllowed('ROLE_USER');
        /** @var Transaction|null $transaction */
        $transaction = $this->em
            ->getRepository(Transaction::class)
            ->getLatestByUser($user)
        ;

        if (!$transaction) {
            throw new \LogicException('You have no transactions.');
        }

        return $transaction->getStatus();
    }

    /**
     * @param string $id
     *
     * @return Transaction[]|array
     */
    public function getTransactions(string $id) :array
    {
        $this->checkRoleAllowed('ROLE_ADMIN');

        return $this->em
            ->getRepository(Transaction::class)
            ->findBy([
                'owner' => $id,
            ])
        ;
    }

    /**
     * @param Transaction $transaction
     *
     * @return int
     */
    public function id(Transaction $transaction) :int
    {
        return $transaction->getId();
    }

    /**
     * @param Transaction $transaction
     *
     * @return int
     */
    public function ownerId(Transaction $transaction) :int
    {
        return $transaction->getOwner()->getId();
    }

    /**
     * @param Transaction $transaction
     *
     * @return string
     */
    public function owner(Transaction $transaction) :string
    {
        return $transaction->getOwner()->getUsername();
    }

    /**
     * @param Transaction $transaction
     *
     * @return string
     */
    public function description(Transaction $transaction) :string
    {
        return $transaction->getDescription();
    }

    /**
     * @param Transaction $transaction
     *
     * @return float
     */
    public function totalPrice(Transaction $transaction) :float
    {
        return round($transaction->getTotalPrice(), 2);
    }

    /**
     * @param string $role
     *
     * @return User
     */
    private function checkRoleAllowed(string $role) :User
    {
        $user = $this->extractUser($this->tokenStorage);

        if (!$user || !$user->hasRole($role)) {
            throw new NotAuthorizedException();
        }

        return $user;
    }
}
