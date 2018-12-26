<?php

namespace App\GraphQL\Resolver;

use App\Entity\BasketItem;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class BasketItemResolver implements ResolverInterface
{
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
        return round($basketItem->getAmount(), 2);
    }
}
