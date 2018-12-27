<?php

namespace App\GraphQL\Resolver;

use App\Entity\BasketItem;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class BasketItemResolver implements ResolverInterface
{
    use InvokeTrait;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $id
     *
     * @return BasketItem|null
     */
    public function resolve(int $id) :?BasketItem
    {
        return $this->em->find(BasketItem::class, $id);
    }

    /**
     * @param BasketItem $basketItem
     *
     * @return int
     */
    public function name(BasketItem $basketItem) :string
    {
        return $basketItem->getItem()->getName();
    }

    /**
     * @param BasketItem $basketItem
     *
     * @return float
     */
    public function price(BasketItem $basketItem) :float
    {
        return round($basketItem->getItem()->getPrice(), 2);
    }

    /**
     * @param BasketItem $basketItem
     *
     * @return int
     */
    public function amount(BasketItem $basketItem) :int
    {
        return $basketItem->getAmount();
    }

    /**
     * @param BasketItem $basketItem
     *
     * @return float
     */
    public function totalPrice(BasketItem $basketItem) :float
    {
        return round($basketItem->getAmount() * $basketItem->getItem()->getPrice(), 2);
    }
}
