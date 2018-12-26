<?php

namespace App\GraphQL\Mutation;

use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Entity\Category;
use App\Entity\Transaction;
use App\Entity\User;
use App\GraphQL\Exception\NotAuthorizedException;
use App\Security\UserExtractorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransactionMutation
{
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
     * @param int $category
     * @param string $name
     * @param float $price
     *
     * @return Transaction
     */
    public function createAction() :Transaction
    {
        $user = $this->extractUser($this->tokenStorage);

        if (!$user || !$user->hasRole('ROLE_USER')) {
            throw new NotAuthorizedException();
        }

        $basket = $this->getBasket($user);
        $transaction = $this->generateTransaction($basket);
        $this->em->remove($basket);
        $this->em->persist($transaction);
        $this->em->flush();

        return $transaction;
    }

    /**
     * @param User $user
     *
     * @return Basket
     */
    private function getBasket(User $user) :Basket
    {
        $basket = $this->em->getRepository(Basket::class)
            ->findOneBy([
                'owner' => $user->getId(),
            ], [
                'id' => 'DESC',
            ])
        ;

        if (!$basket || !count($basket->getBasketItems())) {
            throw new \LogicException('Basket is empty, no need to create a transaction.');
        }

        return $basket;
    }

    /**
     * @param Basket $basket
     *
     * @return Transaction
     */
    private function generateTransaction(Basket $basket) :Transaction
    {
        $description = [];
        $totalPrice = 0;

        foreach ($basket->getBasketItems() as $basketItem) {
            /** @var BasketItem $basketItem */
            $itemsPrice = $basketItem->getItem()->getPrice() * $basketItem->getAmount();
            $totalPrice += $itemsPrice;
            $description[] = sprintf('%s %sx%d = %s',
                $basketItem->getItem()->getName(),
                round($basketItem->getItem()->getPrice(), 2),
                $basketItem->getAmount(),
                round($itemsPrice, 2)
            );
        }

        $transaction = new Transaction();
        $transaction
            ->setOwner($basket->getOwner())
            ->setDescription(implode('; ', $description))
            ->setTotalPrice($totalPrice)
        ;

        return $transaction;
    }
}
