<?php

namespace App\GraphQL\Mutation;

use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Entity\Item;
use App\Entity\User;
use App\GraphQL\Exception\NotAuthorizedException;
use App\Security\UserExtractorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BasketMutation implements MutationInterface
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
     * @param int $item
     * @param int $amount
     *
     * @return Basket
     */
    public function basketAddItem(int $item, int $amount = 1) :Basket
    {
        $user = $this->checkUserAllowed();

        if ($amount <= 0) {
            throw new \LogicException('Amount should be greater than 0.');
        }

        $itemElement = $this->findItem($item);

        if (!$itemElement) {
            throw new \LogicException('Item was not found.');
        }

        $basket = $this->getCreateUserBasket($user);
        $basketItem = $this->searchCreateBasketItem($basket, $itemElement);
        $basketItem->setAmount($basketItem->getAmount() + $amount);
        $this->em->persist($basket);
        $this->em->flush();

        return $basket;
    }

    /**
     * @param int $item
     *
     * @return Basket
     */
    public function basketRemoveItem(int $item) :Basket
    {
        $user = $this->checkUserAllowed();
        $itemElement = $this->findItem($item);

        if (!$itemElement) {
            throw new \LogicException('Item was not found.');
        }

        $basket = $this->getCreateUserBasket($user);
        $basketItem = $this->searchCreateBasketItem($basket, $itemElement);
        $basket->removeBasketItem($basketItem);

        if ($basketItem->getId()) {
            $this->em->remove($basketItem);
            $this->em->flush();
        }

        return $basket;
    }

    /**
     * @return User
     */
    private function checkUserAllowed() :User
    {
        $user = $this->extractUser($this->tokenStorage);

        if (!$user || !$user->hasRole('ROLE_USER')) {
            throw new NotAuthorizedException();
        }

        return $user;
    }

    /**
     * @param int $itemId
     *
     * @return Item|null
     */
    private function findItem(int $itemId) :?Item
    {
        return $this->em->getRepository(Item::class)->find($itemId);
    }

    /**
     * @param User $user
     *
     * @return Basket
     */
    private function getCreateUserBasket(User $user) :Basket
    {
        $basket = $this->em
            ->getRepository(Basket::class)
            ->getUserBasket($user)
        ;

        if (!$basket) {
            $basket = new Basket();
            $basket->setOwner($user);
        }

        return $basket;
    }

    /**
     * @param Basket $basket
     *
     * @return BasketItem
     */
    private function searchCreateBasketItem(Basket $basket, Item $item) :BasketItem
    {
        foreach ($basket->getBasketItems()->toArray() as $iterationItem) {
            /** @var BasketItem $iterationItem */
            if ($iterationItem->getItem()->getId() === $item->getId()) {
                return $iterationItem;
            }
        }

        $basketItem = new BasketItem();
        $basketItem
            ->setBasket($basket)
            ->setItem($item)
        ;
        $basket->addBasketItem($basketItem);

        return $basketItem;
    }
}
