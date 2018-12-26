<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class BasketItem
{
    use IdTrait;

    /**
     * @var Basket
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Basket",
     *     inversedBy="basketItems"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $basket;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Item"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $amount;

    public function __construct()
    {
        $this->amount = 0;
    }

    /**
     * @return Basket
     */
    public function getBasket() :Basket
    {
        return $this->basket;
    }

    /**
     * @param Basket $value
     *
     * @return $this
     */
    public function setBasket(Basket $value) :self
    {
        $this->basket = $value;

        return $this;
    }

    /**
     * @return Item
     */
    public function getItem() :Item
    {
        return $this->item;
    }

    /**
     * @param Item $value
     *
     * @return $this
     */
    public function setItem(Item $value) :self
    {
        $this->item = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount() :int
    {
        return $this->amount;
    }

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setAmount(int $value) :self
    {
        $this->amount = $value;

        return $this;
    }
}
