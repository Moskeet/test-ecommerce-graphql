<?php

namespace App\GraphQL\Resolver;

use App\Entity\Transaction;
use App\Entity\User;
use App\GraphQL\CheckRoleAllowedTrait;
use App\GraphQL\Exception\NotAuthorizedException;
use App\Security\UserExtractorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransactionResolver implements ResolverInterface
{
    use InvokeTrait;
    use UserExtractorTrait;
    use CheckRoleAllowedTrait;


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
        $user = $this->extractUser($this->tokenStorage);
        $this->checkRoleAllowed('ROLE_ADMIN', $user);

        return $this->em->find(Transaction::class, $id);
    }

    /**
     * @return string
     */
    public function getTransactionStatus() :string
    {
        $user = $this->extractUser($this->tokenStorage);
        $this->checkRoleAllowed('ROLE_USER', $user);
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
        $user = $this->extractUser($this->tokenStorage);
        $this->checkRoleAllowed('ROLE_ADMIN', $user);

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
}
