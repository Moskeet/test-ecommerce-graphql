<?php

namespace App\GraphQL\Resolver;

use App\Entity\Basket;
use App\Entity\BasketItem;
use App\GraphQL\Exception\NotAuthorizedException;
use App\Security\UserExtractorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BasketResolver implements ResolverInterface
{
    use InvokeTrait;
    use UserExtractorTrait;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage
    ) {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param bool $showBasket
     *
     * @return Basket
     */
    public function resolve(bool $showBasket = true) :Basket
    {
        $user = $this->extractUser($this->tokenStorage);

        if (!$user || !$user->hasRole('ROLE_ADMIN')) {
            throw new NotAuthorizedException();
        }

        if (!$showBasket) {
            return null;
        }

        $backet = $this->em
            ->getRepository(Basket::class)
            ->getUserBasket($user)
        ;

        return $backet
            ? $backet
            : new Basket()
        ;
    }

    /**
     * @return Basket
     */
    public function getBasket() :Basket
    {
        $user = $this->extractUser($this->tokenStorage);

        if (!$user || !$user->hasRole('ROLE_ADMIN')) {
            throw new NotAuthorizedException();
        }

        $backet = $this->em
            ->getRepository(Basket::class)
            ->getUserBasket($user)
        ;

        return $backet
            ? $backet
            : new Basket()
        ;
    }

    /**
     * @param Basket $basket
     *
     * @return int
     */
    public function totalTitles(Basket $basket) :int
    {
        return $basket->getBasketItems()->count();
    }

    /**
     * @param Basket $basket
     *
     * @return int
     */
    public function totalItems(Basket $basket) :int
    {
        $total = 0;

        foreach ($basket->getBasketItems()->toArray() as $basketItem) {
            /** @var BasketItem $basketItem */
            $total += $basketItem->getAmount();
        }

        return $total;

    }

    /**
     * @param Basket $basket
     *
     * @return float
     */
    public function totalPrice(Basket $basket) :float
    {
        $total = 0;

        foreach ($basket->getBasketItems()->toArray() as $basketItem) {
            /** @var BasketItem $basketItem */
            $total += $basketItem->getItem()->getPrice() * $basketItem->getAmount();
        }

        return round($total, 2);
    }

    /**
     * @param Basket $basket
     * @param Argument $args
     *
     * @return Connection
     */
    public function basketItems(Basket $basket, Argument $args) :Connection
    {
        $items = $basket->getBasketItems();
        $paginator = new Paginator(function ($offset, $limit) use ($items) {
            return array_slice($items, $offset, $limit ?? 10);
        });

        return $paginator->auto($args, count($items));
    }
}
